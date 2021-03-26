<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('navigation.messages'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="white-smoke-bg pt-4 pb-3">
<div class="container no-padding">

    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('message')->dom;
} elseif ($_instance->childHasBeenRendered('9CRFGbB')) {
    $componentId = $_instance->getRenderedChildComponentId('9CRFGbB');
    $componentTag = $_instance->getRenderedChildComponentTagName('9CRFGbB');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('9CRFGbB');
} else {
    $response = \Livewire\Livewire::mount('message');
    $dom = $response->dom;
    $_instance->logRenderedChild('9CRFGbB', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
    

</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('extraCSS'); ?>
<style>
    #messages-container, #people-container {
        height: 500px;
        overflow: scroll;
    }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('extraJS'); ?>
<script>
    // listen to livewire growl messages
    window.livewire.on('scroll-to-last', function () {
        var elem = document.getElementById('messages-container');
        elem.scrollTop = elem.scrollHeight;
    });

    // reset message field
    window.livewire.on('reset-message', function () {
        var elem = document.getElementById('message-inp').value = "";
    });

    // scroll to last on switching users
    function hasClass(elem, className) {
        return elem.className.split(' ').indexOf(className) > -1;
    }

    function scrollToLast() {
        window.livewire.emit('scroll-to-last');
        window.livewire.emit('scrollToLast');
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/messages/inbox.blade.php ENDPATH**/ ?>