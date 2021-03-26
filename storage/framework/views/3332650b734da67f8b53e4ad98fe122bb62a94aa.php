<?php $__env->startSection('section_title'); ?>
<strong>Add plan manually for <?php echo e($user->profile->handle); ?></strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>


<form method="POST" enctype="multipart/form-data" class="form-horizontal">
<?php echo e(csrf_field()); ?>


<div class="row">
<div class="col-md-4 col-xs-12">
<strong>Select Creator:</strong><br>
<select name="creator" class="select2 form-control">
<?php $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<option value="<?php echo e($c->user->id); ?>"><?php echo e($c->handle); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<br><br>
<strong>Until</strong>
<br>
<div class="row">
<div class="col-xs-12 col-md-4">
	Day<br>
	<select class="form-control" name="dd">
		<?php for( $i=1;$i<=31;$i++ ): ?>
		<option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
		<?php endfor; ?>
	</select>
</div>
<div class="col-xs-12 col-md-4">
	Month<br>
	<select class="form-control" name="mm">
		<?php for( $i=1;$i<=12;$i++ ): ?>
		<option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
		<?php endfor; ?>
	</select>
</div>
<div class="col-xs-12 col-md-4">
	Year<br>
	<select class="form-control" name="yy">
		<?php for( $i = date( 'Y' ); $i<= date( 'Y' )+100; $i++ ): ?>
		<option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
		<?php endfor; ?>
	</select>
</div>
</div>
<br>
<input type="submit" name="sb" class="btn btn-primary" value="Add Fan">
</div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/admin/add-plan-manually.blade.php ENDPATH**/ ?>