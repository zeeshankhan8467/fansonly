<?php $__env->startComponent('mail::message'); ?>

Hi <?php echo e($user->name); ?>,<br><br>

Good news, your profile <a href="<?php echo e(route('profile.show', ['username' => $user->profile->username])); ?>"><?php echo e($user->profile->handle); ?></a> has been successfully verified.<br>

You may start configuring your membership fee, withdrawal methods and begin taking payments from your fans.
<br><br>

Regards,<br>
<?php echo e(env('APP_NAME')); ?>


<?php echo $__env->renderComponent(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/emails/profileVerified.blade.php ENDPATH**/ ?>