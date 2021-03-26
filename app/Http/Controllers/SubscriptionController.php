<?php

namespace App\Http\Controllers;

use App\User;

use Stripe\Stripe;
use App\Subscription;
use App\Http\Requests;
use Illuminate\Http\Request;
use Stripe\Customer as StripeCustomer;
use Stripe\SetupIntent as StripeIntent;
use Stripe\StripeClient as StripeClient;
use Stripe\Checkout\Session as StripeSession;
use App\Notifications\InovicePaidNotification;
use App\Notifications\NewSubscriberNotification;

class SubscriptionController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['paypalProcessing']]);
	}

	// subscribe via credit card
	public function credit_card(User $user)
	{

		if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
		}

		try {

			// set stripe secret
			Stripe::setApiKey(opt('STRIPE_SECRET_KEY', '123'));

			// create product
			$product = \Stripe\Product::create([
				'name' => 'Subscription to ' . $user->profile->handle,
			]);

			$subscription = \Stripe\Subscription::create([
				'customer' => 'fan_' . auth()->id(),
				'items' => [[
					'price_data' => [
						'unit_amount' => $user->profile->finalPrice * 100,
						'currency' => opt('payment-settings.currency_code'),
						'product' => $product->id,
						'recurring' => [
							'interval' => 'month',
						],
					],
				]],
			]);

			// compute price
			$price = number_format($user->profile->finalPrice, 2);

			// get platform fee
			$platform_fee = opt('payment-settings.site_fee');

			$fee_amount = ($price * $platform_fee) / 100;

			// compute creator amount
			$creator_amount = number_format($price - $fee_amount, 2);

			// save this order in database
			$subPlan = new Subscription;
			$subPlan->creator_id = $user->id;
			$subPlan->subscriber_id = auth()->user()->id;
			$subPlan->subscription_id = $subscription->id;
			$subPlan->gateway = 'Card';
			$subPlan->subscription_date = now();
			$subPlan->subscription_expires = now();
			$subPlan->subscription_price = $price;
			$subPlan->creator_amount = $creator_amount;
			$subPlan->admin_amount = $fee_amount;
			$subPlan->save();

			alert(__('general.subscriptionProcessing'));

			return back();
		} catch (\Exception $e) {
			return back()->withErrors([$e->getMessage()]);
		}
	}

	// paypal route
	public function paypal(User $user)
	{
		if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
		}
		return view('subscribe.paypal', compact('user'));
	}

	public function paypalProcessing($creatorId, $subscriberId, Request $r)
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

			// find this creator
			$creator = User::findOrFail($creatorId);

			// find this subscriber
			$subscriber = User::findOrFail($subscriberId);

			// compute price
			$price = number_format($creator->profile->finalPrice, 2);

			// get platform fee
			$platform_fee = opt('payment-settings.site_fee');
			$fee_amount = ($price * $platform_fee) / 100;

			// compute creator amount
			$creator_amount = number_format($price - $fee_amount, 2);

			switch ($r->txn_type) {

				case 'subscr_signup':

					// save this order in database
					$subPlan = new Subscription;
					$subPlan->creator_id = $creator->id;
					$subPlan->subscriber_id = $subscriber->id;
					$subPlan->subscription_id = $r->subscr_id;
					$subPlan->gateway = 'PayPal';
					$subPlan->subscription_date = now();
					$subPlan->subscription_expires = now();
					$subPlan->subscription_price = $price;
					$subPlan->creator_amount = $creator_amount;
					$subPlan->admin_amount = $fee_amount;
					$subPlan->save();

					break;

				case 'subscr_payment':

					if ($r->payment_status != 'Completed') {
						\Log::info("Payment status is not completed: " . $r->payment_status);

						if (isset($r->pending_reason))
							\Log::info("Reason: " . $r->pending_reason);
					}
					// get subscription
					$subscription = Subscription::where('subscription_id', $r->subscr_id)->firstOrFail();

					// validate amount
					if ($r->mc_gross != $subscription->subscription_price) {
						\Log::error('On subscription #' . $subscription->id . ' received amount was ' . $r->mc_gross . ' while the cost of subscription is ' . $subscription->subscription_price);
						exit;
					}

					// update expires
					$subscription->subscription_expires = now()->addMonths(1);
					$subscription->save();

					// notify creator on site & email
					$creator = $subscription->creator;
					$creator->notify(new NewSubscriberNotification($subscriber));

					// update creator balance
					$creator->balance += $subscription->creator_amount;
					$creator->save();

					break;

				case 'subscr_cancel':

					// get subscription
					$subscription = Subscription::where('subscription_id', $r->subscr_id)->firstOrFail();

					$subscription->status = 'Canceled';
					$subscription->save();

					break;
			}


			$log = '';
			foreach ($_POST as $K => $V) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}

			// \Log::debug($log);
		} else {

			$log = '';
			foreach ($_POST as $K => $V) {
				$log .= $K . '=>' . $V . PHP_EOL;
			}

			\Log::info('Got Invalid Result for TXN_TYPE: ' . $_POST['txn_type']);
			\Log::info($log);
		}
	}

	// ccbill redirect
	public function ccbill(User $user)
	{

		if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
		}

		// make amount a decimal
        $amount = number_format($user->profile->finalPrice , 2);

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
		$initialPeriod = 30;

		// set infinite rebills
		$numRebills = 99;

        // generate hash: initialPrice, initialPeriod, recurringPrice, recurringPeriod, numRebills, currencyCode, salt
		$hash = md5($amount . $initialPeriod . $amount . $initialPeriod . $numRebills . $currencyCode . $salt);
		
        // redirect to CCBill payment
        $ccBillParams['clientAccnum'] = opt('ccbill_clientAccnum');
        $ccBillParams['clientSubacc'] = opt('ccbill_Subacc');
        $ccBillParams['currencyCode'] = $currencyCode;
        $ccBillParams['formDigest'] = $hash;
        $ccBillParams['initialPrice'] = $amount;
		$ccBillParams['initialPeriod'] = $initialPeriod;
		$ccBillParams['recurringPrice'] = $amount;
		$ccBillParams['recurringPeriod'] = $initialPeriod;
		$ccBillParams['numRebills'] = $numRebills;
        $ccBillParams['creator'] = $user->id;
		$ccBillParams['subscriber'] = auth()->id();

        // set form id
        $formId = opt('ccbill_flexid');

        // set base url for CCBill Gateway
        $baseURL = 'https://api.ccbill.com/wap-frontflex/flexforms/' . $formId;

        // build redirect url to CCbill Pay
        $urlParams = http_build_query($ccBillParams);
		$redirectUrl = $baseURL . '?' . $urlParams;

        return redirect($redirectUrl);

	}

	// paystack redirect
	public function payStack(User $user)
	{

		if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
		}

		// make amount in cents
        $amount = number_format($user->profile->finalPrice*100, 0);

        // get site currencies
		$siteCurrency = opt('payment-settings.currency_code', 'USD');

		try {
			
			/*
			- - - - - - - - - - - -
			Step 1 - Create a Plan
			- - - - - - - - - - - -
			*/
			$planCode = $this->_createPayStackPlan($user, $siteCurrency, $amount);

			/*
			- - - - - - - - - - - -
			Step 2 - Create a Subscription to the plan
			- - - - - - - - - - - -
			*/
			$result = $this->_createPayStackSubscription($planCode, $user);
			$subscrId = $result->data->subscription_code;
			

			/*
			- - - - - - - - - - - 
			Step 3 - Create subscription in database
			- - - - - - - - - - - 
			*/

			// compute price
			$price = number_format($user->profile->finalPrice, 2);

			// get platform fee
			$platform_fee = opt('payment-settings.site_fee');

			// compute admin amount
			$fee_amount = ($price * $platform_fee) / 100;

			// compute creator amount
			$creator_amount = number_format($price - $fee_amount, 2);

			// add subscription to database
			$subPlan = new Subscription;
			$subPlan->creator_id = $user->id;
			$subPlan->subscriber_id = auth()->id();
			$subPlan->subscription_id = $subscrId;
			$subPlan->gateway = 'PayStack';
			$subPlan->subscription_date = now();
			$subPlan->subscription_expires = now();
			$subPlan->subscription_price = $price;
			$subPlan->creator_amount = $creator_amount;
			$subPlan->admin_amount = $fee_amount;
			$subPlan->save();

			// sleep 2 seconds to allow webhooks processing
			sleep(2);

			alert(__('general.subscriptionProcessing'));

			return back();

		} catch(\Exception $e) {

			alert()->error($e->getMessage());
			return back();
		}
		
	}

	// create paystack plan
	public function _createPayStackPlan($user, $siteCurrency, $amount) {

		// create PayStack Plan
		$url = "https://api.paystack.co/plan";

		// set fields
		$fields = [
			'name' => auth()->user()->profile->handle .' fan of ' . $user->profile->handle,
			'interval' => 'monthly', 
			'amount' => $amount, 
			'currency' => $siteCurrency,
			[ 'creator' => $user->id,
			  'subscriber' => auth()->id() ]
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
			throw new \Exception('PayStack /plan endpoint cURL Error: ' . $e);

		// close the connection
		curl_close($ch);

		// decode result
		$result = json_decode($result);

		if(!$result->status)
			throw new \Exception('PayStack Returned this status while trying to create plan: ' . $result->message);

		// get plan code
		$planCode = $result->data->plan_code;

		return $planCode;

	}

	// create a paystack subscription
	public function _createPayStackSubscription($planCode, $user)
	{
		
		// get user default payment method 
		$pm = auth()->user()->paymentMethods()->where('is_default', 'Yes')->firstOrFail();
		$authCode = $pm->p_meta['authorization_code'];

		// set paystack redirect url
		$url = "https://api.paystack.co/subscription";
		$fields = [
			'customer' => auth()->user()->email,
			'plan' => $planCode,
			'authorization' => $authCode,
			'metadata' => [ 'creator' => $user->id,
						  'subscriber' => auth()->id() ]
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
			throw new \Exception('PayStack /subscription endpoint cURL Error: ' . $e);

		// close the connection
		curl_close($ch);

		// decode result
		$result = json_decode($result);
		
		// check status
		if (!$result->status)
			throw new \Exception('PayStack Returned this status while trying to create a subscription: ' . $result->message);

		return $result;

	}

}
