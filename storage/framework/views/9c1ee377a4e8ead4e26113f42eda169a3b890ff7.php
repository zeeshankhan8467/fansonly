<?php $__env->startComponent('mail::message'); ?>

Hi Admin,<br>

<strong><?php echo e($user->name); ?></strong> just uploaded everything required and requested your verification so they can start earning on your great platform.

<br>

<a href="<?php echo e(route('admin-pvf')); ?>">
    View Verification Requests
</a>

<br><br>

Regards,<br>
<?php echo e(env('APP_NAME')); ?>


<?php echo $__env->renderComponent(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/emails/verificationRequested.blade.php ENDPATH**/ ?>