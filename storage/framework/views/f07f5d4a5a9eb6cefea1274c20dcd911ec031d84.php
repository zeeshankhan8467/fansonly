<?php $__env->startSection('content'); ?>

<div class="jumbotron jumbotron-blue">
<div class="container">
<h1 class="text-center"><i class="fa fa-user"></i> <?php echo app('translator')->get('auth.resetPassword'); ?></h1>
<div class="text-center"><?php echo app('translator')->get('auth.resetPasswordText'); ?></div><!-- /.text-center -->
</div><!-- /.container -->
</div><!-- /.jumbotron -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="">

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.email')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right"><?php echo app('translator')->get('auth.email'); ?></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required>

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo app('translator')->get( 'auth.resetPassword' ); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/><br/><br/><br/>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>