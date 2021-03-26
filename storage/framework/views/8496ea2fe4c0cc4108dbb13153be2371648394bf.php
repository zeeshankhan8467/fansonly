<?php $__env->startSection('section_title'); ?>
	<strong>Payment Requests (Payouts)</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>

<div class="alert alert-secondary">
    When you mark a payment request as paid, you have to actually pay the user first manually to their bank account or paypal. This does NOT happen automatically.
</div>

<?php if($reqs): ?>
	<table class="table table-striped table-bordered table-responsive dataTable">
	<thead>
	<tr>
		<th>Email</th>
		<th>Name</th>
		<th>Profile</th>
        <th>Amount</th>
        <th>Payout Gateway</th>
        <th>Payout Details</th>
		<th>Date</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
		<?php $__currentLoopData = $reqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<tr>
			<td>
				<?php echo e($v->user->email); ?>

			</td>
			<td>
				<?php echo e($v->user->name); ?><br>
			</td>
			<td>
                <a href="<?php echo e(route('profile.show', ['username' => $v->user->profile->username])); ?>" target="_blank">
                    <?php echo e($v->user->profile->handle); ?>

                </a>
			</td>
			<td>
				<?php echo e(opt('payment-settings.currency_symbol') . $v->amount); ?>

			</td>
			<td>
				<?php echo e($v->user->profile->payout_gateway); ?>

            </td>
            <td>
                
				<?php echo nl2br(e($v->user->profile->payout_details)); ?>

			</td>
			<td>
                 <?php echo e($v->created_at->format('jS F Y')); ?><br>
                 <?php echo e($v->created_at->format('H:ia')); ?>

            </td>
            <td>
                <a href="/admin/payment-requests/markAsPaid/<?php echo e($v->id); ?>">
                    Mark as Paid
                </a>
            </td>
		</tr>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</tbody>
	</table>
<?php else: ?>
	No verification requests in database.
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/payment-requests.blade.php ENDPATH**/ ?>