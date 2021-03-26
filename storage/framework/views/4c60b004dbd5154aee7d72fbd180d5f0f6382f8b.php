
<?php if( $feed->count() ): ?>

	<?php $__currentLoopData = $feed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<?php $__env->startComponent( 'posts.single', [ 'post' => $post ] ); ?> <?php echo $__env->renderComponent(); ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
	<div class="card shadow p-4 mb-4 text-center">
		<h3 class="text-secondary">
			<i class="fas fa-comment-slash"></i> <?php echo app('translator')->get( 'post.noPosts', [ 'handle' => $profile->handle ] ); ?>
		</h3>
	</div>
<?php endif; ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/posts/feed.blade.php ENDPATH**/ ?>