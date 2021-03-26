<div class="card mb-4" data-post-id="<?php echo e($post->id); ?>">

	<div class="row p-4">

		<div class="col-3 col-sm-3 col-md-3 col-lg-2">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 ml-md-3 shadow">
				<a href="<?php echo e($post->profile->url); ?>">
					<img src="<?php echo e(secure_image($post->profile->profilePic, 75, 75)); ?>" alt="" class="img-fluid">
				</a>
			</div>
		</div>
		
		<div class="col-9 col-sm-9 col-md-9 col-lg-10">
			<div class="mt-1 clearfix">

			<div class="float-left">
				<span class="text-secondary"><?php echo e($post->profile->name); ?></span>
				<br>
				<a href="<?php echo e($post->profile->url); ?>">
					<?php echo e($post->profile->handle); ?>

				</a>
				<br>
				<span class="text-muted">
					<small>
						<i class="fas fa-calendar-alt mr-1"></i> <?php echo e($post->created_at->diffForHumans( )); ?>

					</small>
				</span>
			</div>


			<div class="float-right">
				<div class="dropdown dropleft">
					<a href="" class="btn text-secondary dropdown-toggle postActionsDropdown" data-toggle="dropdown" id="dropdown-<?php echo e($post->id); ?>" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-angle-double-down"></i>
					</a>
					<div class="dropdown-menu" aria-labelledby="dropdown-<?php echo e($post->id); ?>">
						<?php if( $post->isCreator() ): ?>
				    		<a class="dropdown-item" href="<?php echo e(route('editPost', ['post' => $post->id])); ?>"><?php echo app('translator')->get('post.editPost'); ?></a>
				    		<a class="dropdown-item delete-post" href="<?php echo e(route('deletePost', ['post' => $post])); ?>" data-id="<?php echo e($post->id); ?>"><?php echo app('translator')->get('post.deletePost'); ?></a>
				    	<?php endif; ?>
				    	<a class="dropdown-item copyLink" href="javascript:void(0);" data-clipboard-text="<?php echo e($post->slug); ?>">
				    		<?php echo app('translator')->get( 'post.copyLink' ); ?>
				    	</a>
				    	<a class="dropdown-item" href="<?php echo e($post->slug); ?>">
				    		<?php echo app('translator')->get( 'post.postLink' ); ?>
				    	</a>
				  	</div>
				</div>
			</div>
			</div>
		</div>

	</div>

<?php if( $post->userHasAccess() ): ?> 
	
	<div class="pl-4 pr-4 pt-0 pb-0">
		<?php echo clean(turnLinksIntoAtags(nl2br($post->text_content)), 'youtube'); ?>

	</div>

	<?php echo $__env->make('posts.post-media', ['post' => $post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


	<div class="border-top px-3 py-3">
		<h4 class="d-inline">
			<a href="<?php if(auth()->check()): ?> javascript:void(0); <?php else: ?> <?php echo e(route( 'login' )); ?> <?php endif; ?>" class="text-danger noHover <?php if(auth()->check()): ?> lovePost <?php endif; ?> <?php if( auth()->check() && auth()->user()->hasLiked( $post )): ?> d-none <?php endif; ?>" data-id="<?php echo e($post->id); ?>">
				<i class="far fa-heart"></i> <span class="post-likes-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->likes->count()); ?></span>
			</a>
			<a href="javascript:void(0);" class="text-danger noHover unlovePost <?php if( (auth()->check() && !auth()->user()->hasLiked( $post )) OR !auth()->check() ): ?> d-none <?php endif; ?>" data-id="<?php echo e($post->id); ?>">
				<i class="fas fa-heart"></i> <span class="post-likes-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->likes->count()); ?></span>
			</a>
		</h4>		
		&nbsp;&nbsp;

		<h4 class="d-inline">
		<a href="<?php if(auth()->check()): ?> javascript:void(0); <?php else: ?> <?php echo e(route( 'login' )); ?> <?php endif; ?>" class="text-secondary noHover <?php if(auth()->check()): ?> loadComments <?php endif; ?>" data-id="<?php echo e($post->id); ?>">
			<i class="far fa-comments"></i> <span class="post-comments-count" data-id="<?php echo e($post->id); ?>"><?php echo e($post->comments->count()); ?></span>
		</a>
		</h4>
		&nbsp;&nbsp;

		<?php echo $__env->make('tips.tip-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<div class="leave-comment mt-3 mb-2 d-none" data-id="<?php echo e($post->id); ?>">

			<input type="text" name="new-comment-<?php echo e($post->id); ?>" class="form-control leave-comment-inp" placeholder="<?php echo app('translator')->get('post.writeCommentAndPressEnter'); ?>" required="required" data-id="<?php echo e($post->id); ?>">

		</div>

		<div class="post-<?php echo e($post->id); ?>-lastId d-none"></div>

		<div class="appendComments d-none" data-id="<?php echo e($post->id); ?>"></div>

		<?php if( $post->comments->count() > opt( 'commentsPerPost', 5 ) ): ?>
		<a class="loadMoreComments d-none" href="javascript:void(0);" data-id="<?php echo e($post->id); ?>">
			<?php echo app('translator')->get( 'post.loadMoreComments' ); ?>
		</a>

		<div class="noMoreComments d-none text-secondary" data-id="<?php echo e($post->id); ?>">
			<i class="fas fa-exclamation-triangle"></i> <?php echo app('translator')->get( 'post.noMoreComments' ); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else: ?>
	

	<div class="pt-1 pb-2 pl-3">
		<?php if($post->media_type != 'None'): ?>
			<?php echo clean($post->text_content); ?>

		<?php endif; ?>
	</div>

	<div class="locked-post p-5 text-center text-secondary">
		<br><br>
	
		<h1 class="display-2">
		<?php if($post->media_type == 'None'): ?>
			<i class="fas fa-align-left"></i>
		<?php elseif($post->media_type == 'Image'): ?>
			<i class="fas fa-image"></i>
		<?php elseif($post->media_type == 'Video'): ?>
			<i class="fas fa-video"></i>
		<?php elseif($post->media_type == 'Audio'): ?>
			<i class="fas fa-music"></i>
		<?php elseif($post->media_type == 'ZIP'): ?>
			<i class="fas fa-file-archive"></i>
		<?php endif; ?>
		</h1>

		<i class="fa fa-lock"></i> <?php echo app('translator')->get('post.locked'); ?>

		<br><br><br>
	</div>

	<?php if($post->profile->monthlyFee && $post->profile->minTip): ?>
	<div class="ml-2 p-2">
		<?php echo $__env->make('tips.tip-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
	<?php endif; ?>

<?php endif; ?>

</div>

<style>
.embed-container {
  --video--width: 1296;
  --video--height: 540;

  position: relative;
  padding-bottom: calc(var(--video--height) / var(--video--width) * 100%); /* 41.66666667% */
  overflow: hidden;
  max-width: 100%;
  background: black;
}

.embed-container iframe,
.embed-container object,
.embed-container embed {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style><?php /**PATH /Users/crivion/Sites/patrons/resources/views/posts/single.blade.php ENDPATH**/ ?>