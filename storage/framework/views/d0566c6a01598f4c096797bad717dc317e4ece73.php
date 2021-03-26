<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('dashboard.verify-profile'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'account_section' ); ?>

<div>
<form method="POST" action="<?php echo e(route( 'processVerification' )); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<div class="shadow-sm card add-padding">
<br/>
<h2 class="ml-2"><i class="fas fa-user-check mr-2"></i><?php echo app('translator')->get('dashboard.verify-profile'); ?></h2>
<?php echo app('translator')->get( 'dashboard.verification-text' ); ?>
<hr>

	<?php if( isset( $p ) AND $p->isVerified == 'No' ): ?>
	<div class="alert alert-warning" role="alert">
		<?php echo app('translator')->get( 'dashboard.send-for-verification' ); ?>
	</div>
	<?php endif; ?>

	<?php if( isset( $p ) AND $p->isVerified == 'Yes' ): ?>
	<div class='alert alert-success'>
		<h4><i class="fas fa-check-circle"></i> <?php echo app('translator')->get( 'dashboard.successfully-verified' ); ?> </h4>
	</div>
	<?php endif; ?>

	<?php if( isset( $p ) AND $p->isVerified == 'Pending' ): ?>
	<div class='alert alert-info'>
		<h4><i class="fas fa-check-circle"></i> <?php echo app('translator')->get( 'dashboard.pending-verification' ); ?> </h4>
	</div>
	<?php else: ?>

	<label><strong><?php echo app('translator')->get('dashboard.yourCountry'); ?></strong></label>
	<select name="country" class="form-control" required>
	<option value=""><?php echo app('translator')->get('profile.selectCountry'); ?></option>
	<?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<option value="<?php echo e($country); ?>"><?php echo e($country); ?></option>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</select>
	<br>


	<label><strong><?php echo app('translator')->get('dashboard.yourCity'); ?></strong></label>
	<input type="text" class="form-control" name="city" value="<?php echo e($p->city ?? old( 'city' )); ?>" required>
	<br>


	<label><strong><?php echo app('translator')->get('dashboard.yourFullAddress'); ?></strong></label>
	<textarea class="form-control" rows="5" name="address" required><?php echo e($p->address ?? old( 'address' )); ?></textarea>
	<br>

	<label><strong><?php echo app('translator')->get('dashboard.idUpload'); ?></strong></label>
    <input type="file" name="idUpload" accept="image/*" required>

    <br>

<div class="text-center">
  <br>
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="<?php echo app('translator')->get('dashboard.sendForApproval'); ?>">
</div><!-- /.white-bg add-padding -->

</form>

<?php endif; ?>

</div><!-- /.white-bg -->

<br/><br/>
</div><!-- /.white-smoke-bg -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'account' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/profile/verify.blade.php ENDPATH**/ ?>