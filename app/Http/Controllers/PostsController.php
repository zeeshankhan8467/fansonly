<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Events\PostCreatedOrUpdatedEvent;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{

    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['singlePost']]);
    }

    // single post
    public function singlePost(Post $post)
    {
        return view('posts.one', compact('post'));
    }

    // GET /feed
    public function feed()
    {

        // get current user feeds
        $feed = auth()->user()->feed();

        return view('user-feed', compact('feed'));
    }

    // edit post
    public function editPost(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        $profile = auth()->user()->profile;

        return view('posts.edit-post', compact('post', 'profile'));
    }

    // update post
    public function updatePost(Request $r, Post $post)
    {

        if ($post->user_id != auth()->id())
            abort(403);

        $this->validate($r, [
            'text_content' => 'required|min:2',
            'lock_type' => 'required|in:Free,Paid,free,paid'
        ]);

        $lock_type = ucfirst(strtolower($r->lock_type));

        // can post locked content?
        if (auth()->user()->profile->isVerified != 'Yes' && $lock_type == 'Paid') {
            alert(__('post.notVerifiedFreePostOnly'));
            return back();
        }

        // save post content
        $post->text_content = $r->text_content;
        $post->lock_type = ucfirst($r->lock_type);
        $post->save();

        // make event to listen to 
        event(new PostCreatedOrUpdatedEvent($post));

        // if has image
        if ($r->hasFile('imageUpload')) {

            // set media variable
            $imageUpload = $r->file('imageUpload');

            $v = Validator::make($r->all(), ['imageUpload' => 'image|mimes:jpeg,png,jpg,gif']);

            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {

                if( $imageUpload->getMimeType() == 'image/gif' ) {

                    // if it's a gif, resizing will break it, so upload as is
                    $fileName = $imageUpload->storePublicly('userPics', env('DEFAULT_STORAGE'));

                }else{
                    // get ext
                    $imageExt = $imageUpload->getClientOriginalExtension();

                    // resize
                    $imageUpload = Image::make($imageUpload);
                    
                    // resize for feed (auto width) - 100% quality ratio
                    $imageUpload->resize(1180, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode(null, 100)->orientate();

                    // compute a file name
                    $fileName = 'userPics/' . uniqid() . '.' . $imageExt;

                    // store the resized image
                    Storage::disk(env('DEFAULT_STORAGE'))->put($fileName, $imageUpload, 'public');

                }

                // update post info
                $post->media_type = 'Image';
                $post->media_content = $fileName;
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {

                return response()->json(['result' => false, 'message' => $e->getMessage()]);

            }
        }

        // if has video
        if ($r->hasFile('videoUpload')) {

            // set media variable
            $videoUpload = $r->file('videoUpload');

            $this->validate($r, ['videoUpload' => 'mimes:mp4,ogg,webm,mov,qt']);

            // store video
            $storePic = $videoUpload->storePublicly('userVids', env('DEFAULT_STORAGE'));

            $post->media_type = 'Video';
            $post->disk = env('DEFAULT_STORAGE');
            $post->media_content = $storePic;
            $post->save();
        }

        // if has audio
        if ($r->hasFile('audioUpload')) {

            // set media variable
            $audioUpload = $r->file('audioUpload');

            $this->validate($r, ['audioUpload' => 'mimes:mp3,ogg,wav']);

            // store video
            $storePic = $audioUpload->storePublicly('userAudio', env('DEFAULT_STORAGE'));

            $post->media_content = $storePic;
            $post->disk = env('DEFAULT_STORAGE');
            $post->media_type = 'Audio';
            $post->save();
        }

        // if has Zip File
        if($r->hasFile('zipUpload')) {

            // set media variable
            $zipUpload = $r->file('zipUpload');

            $v = Validator::make($r->all(), ['zipUpload' => 'mimes:zip']);

            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {

                // store zip file
                $storeZip = $zipUpload->storePublicly('userZips', env('DEFAULT_STORAGE'));

                $post->media_content = $storeZip;
                $post->media_type = 'ZIP';
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {
                    
                return response()->json(['result' => false, 'message' => $e->getMessage()]);
                
            }

        }

        alert(__('post.successfullyUpdatedPost'));

        return back();
    }

    // GET /post/remove-media/{post}
    public function deleteMedia(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        // delete from disk
        $mediaType = $post->media_type;
        $mediaFile = $post->media_content;

        Storage::disk($post->disk)->delete($mediaFile);


        $post->media_type = 'None';
        $post->media_content = null;
        $post->save();

        alert(__('post.successfullyRemovedMedia'));

        return back();
    }

    // GET /post/remove-post/{post}
    public function deletePost(Post $post)
    {
        if ($post->user_id != auth()->id())
            abort(403);

        // delete from disk
        $mediaType = $post->media_type;
        $mediaFile = $post->media_content;

        Storage::disk($post->disk)->delete($mediaFile);

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response()->json(['deleted' => true]);
    }

    // GET /ajax/feed
    public function ajaxFeed($lastId)
    {

        // get current user feeds
        $feed = auth()->user()->feed($lastId);

        if (!$feed->count()) {

            return response()->json(['view' => '', 'lastId' => 0]);
        }

        $view = view('posts.ajax-feed', compact('feed'));
        $lastId = $feed->last()->id;

        return response()->json(['view' => $view->render(), 'lastId' => $lastId]);
    }

    public function savePost(Request $r)
    {

        $v = Validator::make($r->all(), [
            'text_content' => 'required|min:2',
            'lock_type' => 'required|in:Free,Paid,free,paid'
        ]);

        $lock_type = ucfirst(strtolower($r->lock_type));

        // can post locked content?
        if (auth()->user()->profile->isVerified != 'Yes' && $lock_type == 'Paid') {
            return response()->json([
                'result' => false,
                'errors' => [__('post.notVerifiedFreePostOnly')],
            ]);
        }

        // save post content
        $post = new Post();
        $post->text_content = $r->text_content;
        $post->lock_type = $r->lock_type;
        $post->user_id = auth()->user()->id;
        $post->profile_id = auth()->user()->profile->id;
        $post->save();

        // make event to listen to 
        event(new PostCreatedOrUpdatedEvent($post));

        // if has image
        if ($r->hasFile('imageUpload')) {

            // set media variable
            $imageUpload = $r->file('imageUpload');

            $v = Validator::make($r->all(), ['imageUpload' => 'image|mimes:jpeg,png,jpg,gif']);

            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {

                if( $imageUpload->getMimeType() == 'image/gif' ) {

                    // if it's a gif, resizing will break it, so upload as is
                    $fileName = $imageUpload->storePublicly('userPics', env('DEFAULT_STORAGE'));

                }else{
                    // get ext
                    $imageExt = $imageUpload->getClientOriginalExtension();

                    // resize
                    $imageUpload = Image::make($imageUpload);
                    
                    // resize for feed (auto width) - 100% quality ratio
                    $imageUpload->resize(1180, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode(null, 100)->orientate();

                    // compute a file name
                    $fileName = 'userPics/' . uniqid() . '.' . $imageExt;

                    // store the resized image
                    Storage::disk(env('DEFAULT_STORAGE'))->put($fileName, $imageUpload, 'public');

                }

                // update post info
                $post->media_type = 'Image';
                $post->media_content = $fileName;
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {

                return response()->json(['result' => false, 'message' => $e->getMessage()]);

            }
        }

        // if has video
        if ($r->hasFile('videoUpload')) {

            // set media variable
            $videoUpload = $r->file('videoUpload');

            $v = Validator::make($r->all(), ['videoUpload' => 'mimes:mp4,ogg,webm,mov,qt']);
            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {
                $storePic = $videoUpload->storePublicly('userVids', env('DEFAULT_STORAGE'));

                $post->media_type = 'Video';
                $post->media_content = $storePic;
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {
                
                return response()->json(['result' => false, 'message' => $e->getMessage()]);

            }
        }

        // if has audio
        if ($r->hasFile('audioUpload')) {

            // set media variable
            $audioUpload = $r->file('audioUpload');

            $v = Validator::make($r->all(), ['audioUpload' => 'mimes:mp3,ogg,wav']);

            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {

                // store video
                $storePic = $audioUpload->storePublicly('userAudio', env('DEFAULT_STORAGE'));

                $post->media_content = $storePic;
                $post->media_type = 'Audio';
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {
                    
                return response()->json(['result' => false, 'message' => $e->getMessage()]);
                
            }
        }

        // if has Zip File
        if($r->hasFile('zipUpload')) {

            // set media variable
            $zipUpload = $r->file('zipUpload');

            $v = Validator::make($r->all(), ['zipUpload' => 'mimes:zip']);

            // if validator fails, return the message
            if ($v->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $v->getMessageBag()->toArray(),
                ]);
            }

            try {

                // store zip file
                $storeZip = $zipUpload->storePublicly('userZips', env('DEFAULT_STORAGE'));

                $post->media_content = $storeZip;
                $post->media_type = 'ZIP';
                $post->disk = env('DEFAULT_STORAGE');
                $post->save();

            }catch(\Exception $e) {
                    
                return response()->json(['result' => false, 'message' => $e->getMessage()]);
                
            }

        }


        return response()->json(['result' => true, 'post' => $post->id]);
    }

    // load by id via ajax: has auth() middleware
    public function loadAjaxSingle(Post $post, $profile = null)
    {

        if ($profile == null)
            $profile = auth()->user()->profile;


        return view('posts.single', compact('post', 'profile'));
    }

    // download zip: has auth middleware
    public function downloadZip(Post $post)
    {
        if( $post->userHasAccess() ) {

            if($post->disk == 'backblaze')
                $redirectTo = 'https://'. opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content;
            else
                $redirectTo = \Storage::disk($post->disk)->url($post->media_content);

            return redirect( $redirectTo );

        }else{
            echo __('v16.accessDenied');
        }
    }

    // redirect external urls
    public function externalLinkRedirect(Request $r)
    {
        $this->validate($r, ['url' => 'required|url']);
        
        return redirect($r->url);
    }
}
