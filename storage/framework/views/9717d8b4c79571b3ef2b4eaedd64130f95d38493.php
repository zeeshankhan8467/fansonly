<?php $__env->startSection('section_title'); ?>
	<strong>Categories Overview</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>

<div class="row">
	<div class="col-xs-12 col-md-6">
		<?php if( empty( $catname ) ): ?>
		<form method="POST" action="/admin/add_category">
		<?php else: ?>
		<form method="POST" action="/admin/update_category">
		<input type="hidden" name="catID" value="<?php echo e($catID); ?>">
		Category Name:
		<?php endif; ?>
			<?php echo e(csrf_field()); ?>

			<input type="text" name="catname" value="<?php echo e($catname); ?>" class="form-control">
			<br/>
			<input type="submit" name="sb" value="Save Category" class="btn btn-primary">
		</form>
	</div><!-- /.col-xs-12 col-md-6 -->
</div><!-- /.row -->

<br/>
<hr/>

<?php if($categories): ?>
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>ID</th>
		<th>Category</th>
		<th>Users</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td>
				<?php echo e($c->id); ?>

			</td>
			<td>
				<?php echo e($c->category); ?>

			</td>
			<td>
				<?php echo e($c->profile_count); ?>

			</td>
			<td>
				 <div class="btn-group">
				 	<a class="btn btn-primary btn-xs" href="/admin/categories?update=<?php echo e($c->id); ?>">
				 		<i class="glyphicon glyphicon-pencil"></i>
				 	</a>
    				<a href="/admin/categories?remove=<?php echo e($c->id); ?>" onclick="return confirm('Are you sure you want to remove this category from database?');" class="btn btn-danger btn-xs">
						<i class="glyphicon glyphicon-remove"></i>
					</a>
				</div>
			</td>
		</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</tbody>
	</table>
<?php else: ?>
	No categories in database.
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/categories.blade.php ENDPATH**/ ?>