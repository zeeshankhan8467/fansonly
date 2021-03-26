<div class="row">
<?php $__empty_1 = true; $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <div class="col-12 col-md-6 <?php if(isset($cols)): ?> col-lg-<?php echo e($cols); ?> <?php else: ?> col-lg-4 <?php endif; ?> mb-4">
        <div class="card shadow rounded">
            
            <img src="<?php echo e(secure_image($p->coverPicture, 520, 280)); ?>" class="img-fluid rounded"/>

           
            <div class="rounded-circle p-1 loop-rounded-pic">
                <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>">
                    <img src="<?php echo e(secure_image($p->profilePic, 100, 100)); ?>" class="rounded-circle img-fluid profilePicExtraSmall"/>
                </a>
            </div>

            <div class="profile-content rounded-bottom pt-1 badge-content">
                <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>" class="text-white text-bold text-wrap text-bold font-18">
                    <?php echo e($p->name); ?> <i class="fas fa-check-circle"></i> 
                </a>
                <br>
                <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>" class="text-white">
                    <?php echo e($p->handle); ?>

                </a>
                <br>
                <a href="<?php echo e(route('browseCreators', ['category' => $p->category_id, 'category_name' => str_slug($p->category->category) ])); ?>" class="text-white">
                    <small><i class="fas fa-tags"></i> <?php echo e($p->category->category); ?></small>
                </a>
            </div>

        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card shadow-sm p-3 text-center">
        <?php echo app('translator')->get('general.noCreators'); ?>
    </div>
<?php endif; ?>

</div>
<div class="container sidebarLinks">
<?php if(method_exists($creators, 'links')): ?>
<?php echo e($creators->links()); ?>

<?php endif; ?>
</div><?php /**PATH /Users/crivion/Sites/patron/resources/views/creators/loop.blade.php ENDPATH**/ ?>