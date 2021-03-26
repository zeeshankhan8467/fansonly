<?php $__env->startPush( 'extraJS' ); ?>
<script>
// audience size slider
AUDIENCE_MIN = <?php echo e(opt('SL_AUDIENCE_MIN', 10)); ?>;
AUDIENCE_MAX = <?php echo e(opt('SL_AUDIENCE_MAX', 9000)); ?>;
AUDIENCE_PREDEFINED_NO = <?php echo e(opt('SL_AUDIENCE_PRE_NUM', 100)); ?>;
AUDIENCE_SL_STEP = <?php echo e(opt('SL_AUDIENCE_STEP', 100)); ?>;

// membership fee slider
MEMBERSHIP_FEE_MIN = <?php echo e(opt('MSL_MEMBERSHIP_FEE_MIN', 9)); ?>;
MEMBERSHIP_FEE_MAX = <?php echo e(opt('MSL_MEMBERSHIP_FEE_MAX', 999)); ?>;
MEMBERSHIP_FEE_PRESET = <?php echo e(opt('MSL_MEMBERSHIP_FEE_PRESET', 9)); ?>;
MEMBERSHIP_FEE_STEP = <?php echo e(opt('MSL_MEMBERSHIP_FEE_STEP', 1)); ?>;
</script>

<script src="<?php echo e(asset('js/homepage-sliders.js')); ?>?v=<?php echo e(microtime()); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="container-fluid website-header">
  <div class="container">
  <div class="row">
  <div class="col-md-6 col-xs-12 text-left">
  <h2 class="bold pt-0 pt-md-4"><?php echo e(opt('homepage_headline')); ?></h2>

  <div class="col-xs-12 d-block d-sm-none">
    <?php if($headerImage = opt('homepage_header_image')): ?>
      <img src="<?php echo e(asset($headerImage)); ?>" class="img-fluid mb-4">
    <?php else: ?>
      <img src="<?php echo e(asset('images/Business_SVG.svg')); ?>" class="img-fluid mb-4">
    <?php endif; ?>
  </div>

  <div class="py-2 font-20">
    <?php echo clean(opt('homepage_intro')); ?>

  </div>

  <p>
      <a class="btn btn-danger btn-md" href="<?php echo e(route('browseCreators')); ?>" role="button"><?php echo app('translator')->get('homepage.exploreCreators'); ?></a>
  </p>
  </div>

  <div class="col-md-4 offset-md-2 d-none d-sm-block text-right">
    <?php if($headerImage = opt('homepage_header_image')): ?>
      <img src="<?php echo e(asset($headerImage)); ?>" class="img-fluid mb-4">
    <?php else: ?>
      <img src="<?php echo e(asset('images/Business_SVG.svg')); ?>" class="img-fluid mb-4">
    <?php endif; ?>
    <br><br><br><br>
  </div><!-- /.col-6 text-right -->
  </div>
</div>
</div>

<div class="container-fluid bg-white">
    <div class="container pt-5 pb-4">
      <div class="homepage-intro text-bluenavi">
        <?php echo clean(opt('home_callout_formatted')); ?>

      </div>
    </div> <!-- /container -->
</div>

<div class="jumbotron">
<div class="container">
<div class="row">
<div class="col">
  <h2 class="bold"><?php echo e(opt('homepage_left_title')); ?></h2>
  <?php echo clean(opt('home_left_content')); ?>

</div><!-- /.col -->
<div class="col">
  <h2 class="bold"><?php echo e(opt('homepage_right_title')); ?></h2>
  <?php echo clean(opt('home_right_content')); ?>

</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.jumbotron -->

<?php if((int) opt('homepage_creators_count') > 0): ?>
<div class="container">
  <h2 class="bold text-center mb-4"><?php echo app('translator')->get('homepage.randomCreators'); ?></h2>

   <?php echo $__env->make('creators.loop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

   <div class="text-center">
    <a class="btn btn-danger mt-2" href="<?php echo e(route('browseCreators')); ?>" role="button"><?php echo app('translator')->get('homepage.browseCreators'); ?></a>
  </div>

</div><!-- /.container -->
<br/><br/>
<?php endif; ?>

<div class="jumbotron">
<div class="container">
<h2 class="bold text-center"><?php echo app('translator')->get('homepage.feesExplained'); ?></h2>

<div class="justify-content-md-center row">
  <div class="col-md-8">
  <div class="progress">
  <div class="progress-bar progress-90" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><?php echo app('translator')->get('homepage.yourMoney'); ?></div>
  <div class="progress-bar bg-success progress-10" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?php echo app('translator')->get('homepage.theFees'); ?></div>
</div>
</div><!-- /.col-md-8 -->
</div><!-- /.justify-conte -->
</div><!-- /.jumbotron -->

<p class="text-center">
    <br/>
    <?php echo e(__('homepage.feesExplained1', [ 'site_fee' => opt( 'payment-settings.site_fee' ) . '%'])); ?><br/>
    <?php echo app('translator')->get( 'homepage.feesExplained2' ); ?>
    <br/>
</p>
</div><!-- /.container -->

<?php if(opt('hideEarningsSimulator', 'Show') == 'Show'): ?> 
  <div class="container">
    <h2 class="bold text-center"><?php echo app('translator')->get( 'homepage.earningsSimulator' ); ?></h2>
    <br/>

    <div class="row">
    <div class="col-md-4 offset-md-2">
        <h5><?php echo app('translator')->get( 'homepage.audienceSize' ); ?> <span class="text-muted audience-size">1000</span></h5>
        <div id="slider-audience"></div>
    </div><!-- /.col-md-3 ( audience size ) -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->

    <div class="col-md-4">
        <h5><?php echo app('translator')->get( 'homepage.membershipFee' ); ?> <span class="text-muted package-price"><?php echo e(opt( 'payment-settings.currency_symbol' )); ?>9</span></h5>
        <div id="slider-package"></div>
    </div><!-- /.col-md-3 ( audience size ) -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->
        
    </div><!-- /.row -->
    
    <br/>
    <hr/>
    <div class="text-center">
    <h3 class="bold">
    <span class="per-month"><?php echo e(opt( 'payment-settings.currency_symbol' )); ?>85.5</span> <?php echo app('translator')->get( 'homepage.perMonth' ); ?>
    </h3><!-- /.bold -->    

    <?php echo e(__('homepage.calcNote', [ 'site_fee' => opt('payment-settings.site_fee').'%'])); ?>

    
    <br/><br/>
    <a href="<?php echo e(route('startMyPage')); ?>" class="btn btn-danger"><?php echo app('translator')->get('homepage.startCreatorProfile'); ?></a>
    </div><!-- /.text-center -->

    <br/><br/>

  </div><!-- /.container -->
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/home.blade.php ENDPATH**/ ?>