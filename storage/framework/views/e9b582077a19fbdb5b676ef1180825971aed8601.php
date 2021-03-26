<?php $__env->startSection('seo_title'); ?> PayPal - <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
		<div class="card shadow-sm">

			<div class="alert alert-secondary text-center">
			<h5>
				<?php echo app('translator')->get('general.subscribeInfo', [
					'user' => '<a href="'.route('profile.show', [
						'username' => $user->profile->username]).'">'.$user->profile->handle.'</a>',
						'amount'   => opt('payment-settings.currency_symbol') . number_format($user->profile->finalPrice,2)
				]); ?>
			</h5>
			</div>


			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalform" id="paypalform">
			<input type="hidden" name="business" value="<?php echo e(opt('paypal_email', 'email@paypal.com')); ?>"/>
			<input type="hidden" name="return" value="<?php echo e(route('profile.show', ['username' => $user->profile->username])); ?>" />
			<input type="hidden" name="cancel_return" value="<?php echo e(route('profile.show', ['username' => $user->profile->username])); ?>"/>
			<input type="hidden" name="notify_url" value="<?php echo e(route('paypalProcessing', ['creator' => $user->id, 'subscriber' => auth()->id()])); ?>"/>
			<input type="hidden" name="item_name" value="Subscription to <?php echo e($user->profile->handle); ?>"/>
			<input type="hidden" name="a3" value="<?= $user->profile->finalPrice ?>"/>
			<input type="hidden" name="p3" value="1"/>
			<input type="hidden" name="t3" value="M"/>
			<input type="hidden" name="src" value="1"/>
			<input type="hidden" name="currency_code" value="<?php echo e(opt('payment-settings.currency_code')); ?>"/>
			<input type="hidden" name="cmd" value="_xclick-subscriptions"/>
			<input type="hidden" name="rm" value="2"/>
			</form>

			<div class="text-center mb-3">
				<img src="<?php echo e(asset('images/paypal-btn.png')); ?>" alt='paypal' class="img-fluid col-6" id="imgPP"/>
			</div>

			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('extraJS'); ?>
<script>
window.onload = function(){
  document.forms['paypalform'].submit();
}
$("#imgPP").click(function() {
	document.forms['paypalform'].submit();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/subscribe/paypal.blade.php ENDPATH**/ ?>