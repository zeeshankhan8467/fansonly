@extends('welcome')

@push( 'extraJS' )
<script>
// audience size slider
AUDIENCE_MIN = {{ opt('SL_AUDIENCE_MIN', 10) }};
AUDIENCE_MAX = {{ opt('SL_AUDIENCE_MAX', 9000) }};
AUDIENCE_PREDEFINED_NO = {{ opt('SL_AUDIENCE_PRE_NUM', 100) }};
AUDIENCE_SL_STEP = {{ opt('SL_AUDIENCE_STEP', 100) }};

// membership fee slider
MEMBERSHIP_FEE_MIN = {{ opt('MSL_MEMBERSHIP_FEE_MIN', 9) }};
MEMBERSHIP_FEE_MAX = {{ opt('MSL_MEMBERSHIP_FEE_MAX', 999) }};
MEMBERSHIP_FEE_PRESET = {{ opt('MSL_MEMBERSHIP_FEE_PRESET', 9) }};
MEMBERSHIP_FEE_STEP = {{ opt('MSL_MEMBERSHIP_FEE_STEP', 1) }};
</script>

<script src="{{ asset('js/homepage-sliders.js') }}?v={{ microtime() }}"></script>
@endpush

@section('content')
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="container-fluid website-header">
  <div class="container">
  <div class="row">
  <div class="col-md-6 col-xs-12 text-left">
  <h2 class="bold pt-0 pt-md-4">{{ opt('homepage_headline') }}</h2>

  <div class="col-xs-12 d-block d-sm-none">
    @if($headerImage = opt('homepage_header_image'))
      <img src="{{ asset($headerImage) }}" class="img-fluid mb-4">
    @else
      <img src="{{ asset('images/Business_SVG.svg') }}" class="img-fluid mb-4">
    @endif
  </div>

  <div class="py-2 font-20">
    {!! clean(opt('homepage_intro')) !!}
  </div>

  <p>
      <a class="btn btn-danger btn-md" href="{{ route('browseCreators') }}" role="button">@lang('homepage.exploreCreators')</a>
  </p>
  </div>

  <div class="col-md-4 offset-md-2 d-none d-sm-block text-right">
    @if($headerImage = opt('homepage_header_image'))
      <img src="{{ asset($headerImage) }}" class="img-fluid mb-4">
    @else
      <img src="{{ asset('images/Business_SVG.svg') }}" class="img-fluid mb-4">
    @endif
    <br><br><br><br>
  </div><!-- /.col-6 text-right -->
  </div>
</div>
</div>

<div class="container-fluid bg-white">
    <div class="container pt-5 pb-4">
      <div class="homepage-intro text-bluenavi">
        {!! clean(opt('home_callout_formatted')) !!}
      </div>
    </div> <!-- /container -->
</div>

<div class="jumbotron">
<div class="container">
<div class="row">
<div class="col">
  <h2 class="bold">{{ opt('homepage_left_title') }}</h2>
  {!! clean(opt('home_left_content')) !!}
</div><!-- /.col -->
<div class="col">
  <h2 class="bold">{{ opt('homepage_right_title') }}</h2>
  {!! clean(opt('home_right_content')) !!}
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.jumbotron -->

@if((int) opt('homepage_creators_count') > 0)
<div class="container">
  <h2 class="bold text-center mb-4">@lang('homepage.randomCreators')</h2>

   @include('creators.loop')

   <div class="text-center">
    <a class="btn btn-danger mt-2" href="{{ route('browseCreators') }}" role="button">@lang('homepage.browseCreators')</a>
  </div>

</div><!-- /.container -->
<br/><br/>
@endif

<div class="jumbotron">
<div class="container">
<h2 class="bold text-center">@lang('homepage.feesExplained')</h2>

<div class="justify-content-md-center row">
  <div class="col-md-8">
  <div class="progress">
  <div class="progress-bar progress-90" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">@lang('homepage.yourMoney')</div>
  <div class="progress-bar bg-success progress-10" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">@lang('homepage.theFees')</div>
</div>
</div><!-- /.col-md-8 -->
</div><!-- /.justify-conte -->
</div><!-- /.jumbotron -->

<p class="text-center">
    <br/>
    {{ __('homepage.feesExplained1', [ 'site_fee' => opt( 'payment-settings.site_fee' ) . '%']) }}<br/>
    @lang( 'homepage.feesExplained2' )
    <br/>
</p>
</div><!-- /.container -->

@if(opt('hideEarningsSimulator', 'Show') == 'Show') 
  <div class="container">
    <h2 class="bold text-center">@lang( 'homepage.earningsSimulator' )</h2>
    <br/>

    <div class="row">
    <div class="col-md-4 offset-md-2">
        <h5>@lang( 'homepage.audienceSize' ) <span class="text-muted audience-size">1000</span></h5>
        <div id="slider-audience"></div>
    </div><!-- /.col-md-3 ( audience size ) -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->

    <div class="col-md-4">
        <h5>@lang( 'homepage.membershipFee' ) <span class="text-muted package-price">{{ opt( 'payment-settings.currency_symbol' )}}9</span></h5>
        <div id="slider-package"></div>
    </div><!-- /.col-md-3 ( audience size ) -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->

    <div class="col-md-1">&nbsp;</div><!-- /.col-md-1 -->
        
    </div><!-- /.row -->
    
    <br/>
    <hr/>
    <div class="text-center">
    <h3 class="bold">
    <span class="per-month">{{ opt( 'payment-settings.currency_symbol' )}}85.5</span> @lang( 'homepage.perMonth' )
    </h3><!-- /.bold -->    

    {{ __('homepage.calcNote', [ 'site_fee' => opt('payment-settings.site_fee').'%']) }}
    
    <br/><br/>
    <a href="{{ route('startMyPage') }}" class="btn btn-danger">@lang('homepage.startCreatorProfile')</a>
    </div><!-- /.text-center -->

    <br/><br/>

  </div><!-- /.container -->
@endif

@endsection
