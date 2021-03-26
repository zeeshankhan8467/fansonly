<div>
    <a class="nav-link" href="<?php echo e(route('notifications.index')); ?>">
        <?php echo app('translator')->get('navigation.myNotifications'); ?> 
        <small><?php echo e(auth()->user()->unreadNotifications()->count()); ?> new</small>
    </a>
</div>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/notifications-icon.blade.php ENDPATH**/ ?>