<?php $__env->startSection('seo_title'); ?> <?php echo e($page->page_title); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-0 offset-sm-0 offset-md-2">
            <div class="card shadow p-3">
            <h3><?php echo e($page->page_title); ?></h3>
            <hr>

            <?php echo clean($page->page_content); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/page.blade.php ENDPATH**/ ?>