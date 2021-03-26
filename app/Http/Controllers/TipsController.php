<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tips;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\TipReceivedNotification;

class TipsController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['processPayPalTip']]);
    }

    // reidrect paypal to post
    public function redirectPayPalToPost($post)
    {
        alert()->success(__('general.thanksForTheTip'));
        return redirect(route('single-post', ['post' => $post]));
    }

    // my tips
    public function myTips()
    {
        $tipsReceived = auth()->user()->tipsReceived;
    }

    // send tip
    public function processTip(Post $post, Request $r)
    {

        $min = $post->profile->minTip;
        $max = opt('maxTipAmount', 999.00);

        if (!$min)
            $min = opt('minTipAmount', 1.99);

        $this->validate(
            $r,
            [
                'gateway' => 'required|in:Card,PayPal',
                'tipAmountForPost-' . $post->id => 'required|numeric|between:' . $min . ',' . $max,
            ],
            [
                'tipAmountForPost-' . $post->id . '.required' => __('general.tipAmountRequired')
            ]
        );

        // get creator
        $creator_id = $post->user_id;
        $creator = User::findOrFail($creator_id);

        // get tipper
        $tipper = auth()->user();

        // set amount
        $amount = $r->{'tipAmountForPost-' . $post->id};

        // owner of this post == tipper id?
        if ($tipper->id == $creator_id) {
            alert()->warning(__('general.dontTipYourself'));
            return back();
        }


        if ($r->gateway == 'PayPal') {
            return $this->sendToPayPal($post, $creator, $tipper, $amount);
        } elseif ($r->gateway == 'Card') {
            if (opt('card_gateway', 'Stripe') == 'Stripe') {
                return $this->processCardTip($post, $creator, $tipper, $amount);
            }elseif(opt('card_gateway', 'Stripe') == 'CCBill' ) {
                return $this->ccBillForm($post, $creator, $tipper, $amount);
            }elseif(opt('card_gateway', 'Stripe') == 'PayStack' ) {
                return $this->payStackTip($post, $creator, $tipper, $amount);
            }elseif(opt('card_gateway', 'Stripe') == 'TransBank' ) {
                return $this->sendToTransBankController($post, $creator, $tipper, $amount);
            }
        }
    }

    // send to paypal
    public function sendToPayPal($post, $creator, $tipper, $amount)
    {
        return view('tips.paypal', compact('post', 'creator', 'tipper', 'amount'));
    }

    // process paypal tip
    public function processPayPalTip($creator, $subscriber, $post, Request $r)
    {
        // STEP 1: read POST data
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);

        $myPost = array();

        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';

        // build req
        foreach ($myPost as $key => $value) {
            $value = urlencode($value);
            $req .= '& ' . trim(strip_tags($key)) . '=' . trim(strip_tags($value));
        }

        // STEP 2: POST IPN data back to PayPal to validate
        // $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // error?
        if (!($res = curl_exec($ch))) {
            \Log::error("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        } else {
            \Log::info('IPN_POSTED_SUCCESSFULLY');
        }
        curl_close($ch);

        // \Log::info('Result: ' . $res);
        // \Log::debug($r->all());

        // STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp($res, "VERIFIED") == 0) {

            // check that receiver_email is your Primary PayPal email
            $receiver_email = $r->receiver_email;

            if (opt('paypal_email', 'paypal@paypal.com') != $receiver_email) {
                \Log::info('RECEIVER_EMAIL = ' . $receiver_email);
                \Log::info('SHOULD_BE = ' . opt('paypal_email', 'paypal@paypal.com'));
                exit;
            }

            // check if payment status is completed
            if ($r->payment_status != "Completed") {
                \Log::info('Payment status is not Completed but: ' . $r->payment_status);
                exit;
            }

            // find this creator
            $creator = User::findOrFail($creator);

            // find this subscriber
            $subscriber = User::findOrFail($subscriber);

            // compute price
            $price = $r->mc_gross;

            // get platform fee
            $platform_fee = opt('payment-settings.site_fee');
            $fee_amount = ($price * $platform_fee) / 100;

            // compute creator amount
            $creator_amount = number_format($price - $fee_amount, 2);

            switch ($r->txn_type) {

                case 'web_accept':

                    // update creator balance
                    $creator->balance += $creator_amount;
                    $creator->save();

                    // create tip
                    $tip = new Tips;
                    $tip->amount = $r->mc_gross;
                    $tip->creator_amount = $creator_amount;
                    $tip->admin_amount = $fee_amount;
                    $tip->tipper_id = $subscriber->id;
                    $tip->creator_id = $creator->id;
                    $tip->post_id = $post;
                    $tip->gateway = 'PayPal';
                    $tip->save();

                    // notify creator by email and on site
                    $creator->notify(new TipReceivedNotification($tip));

                    break;
            }
        } else {
            \Log::info('PayPal Not VERIFIED:' . $res);
        }
    }

    // process card tip
    public function processCardTip($post, $creator, $tipper, $amount)
    {

        \Stripe\Stripe::setApiKey(opt('STRIPE_SECRET_KEY', 123));

        $user = auth()->user();
        $customer = 'fan_' . auth()->id();

        $tipper = $user;

        $pm = $user->paymentMethods()->where('is_default', 'Yes')->firstOrFail();
        $pm = $pm->p_meta;
        $pm_id = $pm['payment_method'];

        // compute price
        $price = $amount;

        // get platform fee
        $platform_fee = opt('payment-settings.site_fee');
        $fee_amount = ($price * $platform_fee) / 100;

        // compute creator amount
        $creator_amount = number_format($price - $fee_amount, 2);

        try {

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => opt('payment-settings.currency_code'),
                'customer' => $customer,
                'payment_method' => $pm_id,
                'off_session' => true,
                'confirm' => true,
            ]);

            // update creator balance
            $creator->balance += $creator_amount;
            $creator->save();

            // create tip
            $tip = new Tips;
            $tip->amount = $amount;;
            $tip->creator_amount = $creator_amount;
            $tip->admin_amount = $fee_amount;
            $tip->tipper_id = $tipper->id;
            $tip->creator_id = $creator->id;
            $tip->post_id = $post->id;
            $tip->gateway = 'Card';
            $tip->save();

            // notify creator by email and on site
            $creator->notify(new TipReceivedNotification($tip));

            alert()->info(__('general.tipSuccess'));
        } catch (\Stripe\Exception\CardException $e) {

            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            if ($payment_intent->status == 'requires_payment_method') {

                // setup stripe client
                $stripe = new \Stripe\StripeClient(
                    opt('STRIPE_SECRET_KEY', '123')
                );

                // confirm payment
                $confirm = $stripe->paymentIntents->confirm(
                    $payment_intent_id,
                    ['payment_method' => $pm_id],
                );

                // create tip
                $tip = new Tips;
                $tip->amount = $amount;;
                $tip->creator_amount = $creator_amount;
                $tip->admin_amount = $fee_amount;
                $tip->tipper_id = $tipper->id;
                $tip->creator_id = $creator->id;
                $tip->post_id = $post->id;
                $tip->gateway = 'Card';
                $tip->payment_status = 'Pending';
                $tip->intent = $payment_intent_id;
                $tip->save();


                // redirect user to confirmation
                return redirect($confirm->next_action->use_stripe_sdk->stripe_js);
            } else {

                alert()->error($e->getMessage());
            }
        }

        return redirect($post->slug);
    }

    // PayStack Form
    public function payStackTip($post, $creator, $tipper, $amount)
    {
        
        // make amount a decimal
        $amount = number_format($amount, 2);

        try {

            // get currency
            $currencyCode = opt('payment-settings.currency_code', 'USD');

            // get user default payment method 
            $pm = auth()->user()->paymentMethods()->where('is_default', 'Yes')->firstOrFail();
            $authCode = $pm->p_meta['authorization_code'];

            // set url
            $url = "https://api.paystack.co/transaction/charge_authorization";

        
            // set fields
            $fields = [
                'email' => auth()->user()->email,
                'amount' => $amount*100,
                'currency' => $currencyCode,
                'authorization_code' => $authCode,
                'metadata' => [ 'post' => $post->id,
                                'creator' => $creator->id,
                                'tipper' => $tipper->id ]
            ];

            $fields_string = http_build_query($fields);

            //open connection
            $ch = curl_init();
            
            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . opt('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ));
            
            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
            
            //execute post
            $result = curl_exec($ch);

            if($e = curl_error($ch) && !empty($e) )
                throw new \Exception('PayStack cURL Error: ' . $e);

            // close the connection
            curl_close($ch);

            // decode result
            $result = json_decode($result);

            if(!$result->status)
                throw new \Exception('PayStack Returned this status: ' . $result->message);

            // sleep to have time for processing
            sleep(2);

            // add message
            alert()->info(__('v17.tipProcessing'));

            // redirect to tipped post
            return redirect(route('single-post', ['post' => $post->id]));


        } catch(\Exception $e) {

            alert()->error($e->getMessage());
            return back();
        }

    }

    // send to transBank Controller for processing
    public function sendToTransBankController($post, $creator, $tipper, $amount)
    {
        
        return redirect(route('wbp-process-create', ['post' => $post->id, 
                                                     'creator' => $creator, 
                                                     'tipper' => $tipper, 
                                                     'amount' => $amount
                                                     ]));

    }

    // process CCBill Tip
    public function ccBillForm($post, $creator, $tipper, $amount)
    {

        // make amount a decimal
        $amount = number_format($amount, 2);

        // set ccbill currency codes
        $ccbillCurrencyCodes = [];
        $ccbillCurrencyCodes["USD"] = 840;
        $ccbillCurrencyCodes["EUR"] = 978;
        $ccbillCurrencyCodes["AUD"] = 036;
        $ccbillCurrencyCodes["CAD"] = 124;
        $ccbillCurrencyCodes["GBP"] = 826;
        $ccbillCurrencyCodes["JPY"] = 392;

        // get site currencies
        $siteCurrency = strtoupper(opt('payment-settings.currency_code', 'USD'));

        // do we have this site currency on CCBill as well? if not, default to USD
        if( isset($ccbillCurrencyCodes[$siteCurrency]) )
            $currencyCode = $ccbillCurrencyCodes[$siteCurrency];
        else
            $currencyCode = 840;

        // get salt
        $salt = opt('ccbill_salt');
        
        // set initial period
        $initialPeriod = 365;

        // generate hash: formPrice, formPeriod, currencyCode, salt
        $hash = md5($amount . $initialPeriod . $currencyCode . $salt);

        // redirect to CCBill payment
        $ccBillParams['clientAccnum'] = opt('ccbill_clientAccnum');
        $ccBillParams['clientSubacc'] = opt('ccbill_Subacc');
        $ccBillParams['currencyCode'] = $currencyCode;
        $ccBillParams['formDigest'] = $hash;
        $ccBillParams['initialPrice'] = $amount;
        $ccBillParams['initialPeriod'] = $initialPeriod;
        $ccBillParams['post'] = $post->id;
        $ccBillParams['creator'] = $creator->id;
        $ccBillParams['tipper'] = $tipper->id;

        // set form id
        $formId = opt('ccbill_flexid');

        // set base url for CCBill Gateway
        $baseURL = 'https://api.ccbill.com/wap-frontflex/flexforms/' . $formId;

        // build redirect url to CCbill Pay
        $urlParams = http_build_query($ccBillParams);
        $redirectUrl = $baseURL . '?' . $urlParams;

        return redirect($redirectUrl);

    }

}
