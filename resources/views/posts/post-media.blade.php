@if( $post->media_type == 'Image' )


    @if( $post->disk == 'backblaze' )
        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}">
            <img src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" alt="" class="img-fluid"/>
        </a>
    @else
        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="{{ \Storage::disk($post->disk)->url($post->media_content) }}">
            <img src="{{ \Storage::disk($post->disk)->url($post->media_content) }}" alt="" class="img-fluid"/>
        </a>
    @endif

@elseif( $post->media_type == 'Video' )

<div class="embed-responsive embed-responsive-16by9">
<video controls @if(opt('enableMediaDownload', 'No') == 'No') controlsList="nodownload" @endif preload="metadata" disablePictureInPicture>
    @if( $post->disk == 'backblaze' )
        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}#t=0.5" type="video/mp4" />
    @else
        <source src="{{ \Storage::disk($post->disk)->url($post->video_url) }}#t=0.5" type="video/mp4" />
    @endif
    @lang('post.videoTag')
</video>
</div>

@elseif( $post->media_type == 'Audio' )

<div class="p-2">
<audio class="w-100 mb-4" controls @if(opt('enableMediaDownload', 'No') == 'No') controlsList="nodownload" @endif>
    @if( $post->disk == 'backblaze' )
        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" type="audio/mp3">
    @else
        <source src="{{ \Storage::disk($post->disk)->url($post->audio_url) }}" type="audio/mp3">
    @endif
    @lang('post.audioTag')
</audio>
</div>

@elseif( $post->media_type == 'ZIP' )

<h5>
    <a href="{{ route('downloadZip', ['post' => $post]) }}" target="_blank" class="ml-4 mb-3">
        <i class="fas fa-file-archive"></i> @lang('v16.zipDownload')
    </a>
</h5><br>

@endif
