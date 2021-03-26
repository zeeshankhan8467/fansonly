<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('homepage.browseCreators'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'content' ); ?>
<div class="white-smoke-bg">
<br/>

<div class="container pt-3 pb-5">
    <h3 class="mb-4 text-center"><?php echo app('translator')->get('homepage.browseCreators'); ?></h3>
        <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('browse-creators', ['category' => $category])->dom;
} elseif ($_instance->childHasBeenRendered('iSNwAtm')) {
    $componentId = $_instance->getRenderedChildComponentId('iSNwAtm');
    $componentTag = $_instance->getRenderedChildComponentTagName('iSNwAtm');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('iSNwAtm');
} else {
    $response = \Livewire\Livewire::mount('browse-creators', ['category' => $category]);
    $dom = $response->dom;
    $_instance->logRenderedChild('iSNwAtm', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
    </div>
</div>


</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('extraCSS'); ?>
<link href="<?php echo e(url(asset('css/bootstrap-select.min.css'))); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('extraJS'); ?>
<script src="<?php echo e(url(asset('js/bootstrap-select.min.js'))); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\test\fansonly-patrons\resources\views/creators/browse.blade.php ENDPATH**/ ?>