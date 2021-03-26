@extends( 'welcome' )

@section('seo_title') @lang('navigation.editPost') - @endsection

@section( 'content' )
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">

@include('posts.sidebar-mobile')

<div class="col-12 col-md-8">

<form id="updatePostFrm" name="updatePostFrm" method="POST" enctype="multipart/form-data" action="{{ route('updatePost', ['post' => $post]) }}">
@csrf

<div class="card mb-4 p-4">
<div class="row">

	<div class="col-sm-2 d-md-block d-none d-sm-none">
		<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 ml-md-3 profilePicOnlineSm shadow">
			<img src="{{ $profile->profilePicture }}" alt="" width="75" height="75">
		</div>
	</div>
	<div class="col-12 col-sm-10" id="belowCreatePost">
		<textarea name="text_content" id="createPost" rows="1" class="form-control" placeholder="@lang('post.writeSomething')" required="required">{{ $post->text_content }}</textarea>

		<br>
		  <input type="hidden" class="postType" name="lock_type" value="{{  $post->lock_type }}">

		  <a href="javascript:void(0);" class="mr-2 noHover text-danger imageUploadLink" data-toggle="tooltip" title="@lang('post.imageUpload')">
		  	<h5 class="d-inline"><i class="fas fa-image"></i></h5>
		  </a>

		  <a href="javascript:void(0);" class="mr-2 noHover text-info videoUploadLink" data-toggle="tooltip" title="@lang('post.videoUpload')">
		  	<h5 class="d-inline"><i class="fas fa-video"></i></h5>
		  </a>

		  <a href="javascript:void(0);" class="mr-2 noHover text-secondary audioUploadLink" data-toggle="tooltip" title="@lang('post.audioUpload')">
		  	<h5 class="d-inline"><i class="fas fa-music"></i></h5>
		  </a>

		  <a href="javascript:void(0);" class="ml-1 mr-2 noHover text-dark zipUploadLink" data-toggle="tooltip" title="@lang('v16.zipUpload')">
			<h5 class="d-inline"><i class="fas fa-file-archive"></i></h5>
		  </a>

		  <a href="javascript:void(0);" class="togglePostType noHover @if($post->lock_type == 'Paid') d-none @endif" data-switch-to="paid" data-toggle="tooltip" title="@lang('post.freePost')">
		  	<h5 class="d-inline"><i class="fas fa-lock-open text-success"></i></h5>
		  </a>

		  <a href="javascript:void(0);" class="togglePostType noHover @if($post->lock_type == 'Free') d-none @endif" data-switch-to="free" data-toggle="tooltip" title="@lang('post.paidPost')">
		  	<h5 class="d-inline"><i class="fas fa-lock text-warning"></i></h5>
		  </a>

		  <input type="file" name="imageUpload" accept="image/*" class="d-none">
		  <input type="file" name="videoUpload" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="d-none">
		  <input type="file" name="audioUpload" accept="audio/mpeg" class="d-none">
		  <input type="file" name="zipUpload" accept="zip,application/zip,application/x-zip,application/x-zip-compressed,.zip" class="d-none">
		
		<button type="submit" class="btn btn-primary btnUpdatePost mb-2 ml-2">
			<i class="far fa-paper-plane mr-1"></i> @lang('post.updatePost')
		</button>

	</div>

	<div class="uploadName col-12"></div>

</div><!-- .row -->

</div><!-- ./card -->
</form>

@if($post->media_type != 'None')
<div class="card p-3">
<a class="btn btn-light remove-media col-3" href="{{  route('deleteMedia', ['post' => $post]) }}">
	<i class="fas fa-backspace"></i> 
	@lang('post.removeMedia')
</a>
<br>
@include('posts.post-media', ['post' => $post])
</div>
@endif

</div>

@include('posts.sidebar-desktop')

</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
@endsection

@push( 'extraJS' )
{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
<script>
	$( function() {

		$(".zipUploadLink").click(function () {
			$("input[name=zipUpload]").trigger('click');
		});

		var createPostTextarea = document.getElementById('createPost');

		createPostTextarea.style.overflow = 'hidden';
		createPostTextarea.style.height = createPostTextarea.scrollHeight + 'px';

		// auto expand textarea
		document.getElementById('createPost').addEventListener('keyup', function() {
		    this.style.overflow = 'hidden';
		    this.style.height = 0;
		    this.style.height = this.scrollHeight + 'px';
		}, false);

	});
</script>
@endpush