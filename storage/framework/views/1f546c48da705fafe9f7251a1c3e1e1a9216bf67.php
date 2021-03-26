<div class="col-12 col-md-4 d-none d-sm-none d-md-none d-lg-block">
	<div class="sticky-top sticky-sidebar">
	
	<?php if( isset($feed) && $feed->count() ): ?>
		<div class="lastId d-none"><?php echo e($feed->last()->id); ?></div>
	<?php endif; ?>

	<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('creators-sidebar')->dom;
} elseif ($_instance->childHasBeenRendered('UP3GwRA')) {
    $componentId = $_instance->getRenderedChildComponentId('UP3GwRA');
    $componentTag = $_instance->getRenderedChildComponentTagName('UP3GwRA');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('UP3GwRA');
} else {
    $response = \Livewire\Livewire::mount('creators-sidebar');
    $dom = $response->dom;
    $_instance->logRenderedChild('UP3GwRA', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>

	<br>
	</div>
</div><?php /**PATH C:\xampp\htdocs\test\new\fansonly-patrons-v1.8.1-null-chintanbhat\resources\views/posts/sidebar-desktop.blade.php ENDPATH**/ ?>