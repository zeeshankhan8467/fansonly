<div>

    - note : you can login as any of these <a href="/admin/users">admin &raquo; users</a> to edit their content - <br>
    <br>

    <ul class="nav nav-tabs" role="tablist">
        <li <?php if($content_type == 'Image'): ?> class="active" <?php endif; ?>>
            <a href="javascript:void(0);" wire:click='tab("Image")'>Images (<?php echo e($counts['image']); ?>)</a>
        </li>
        <li <?php if($content_type == 'Video'): ?> class="active" <?php endif; ?>>
            <a href="javascript:void(0);" wire:click='tab("Video")'>Videos (<?php echo e($counts['video']); ?>)</a>
        </li>
        <li <?php if($content_type == 'Audio'): ?> class="active" <?php endif; ?>>
            <a href="javascript:void(0);" wire:click='tab("Audio")'>Audios (<?php echo e($counts['audio']); ?>)</a>
        </li>
        <li <?php if($content_type == 'ZIP'): ?> class="active" <?php endif; ?>>
            <a href="javascript:void(0);" wire:click='tab("ZIP")'>ZIP Files (<?php echo e($counts['zip']); ?>)</a>
        </li>
        <li <?php if($content_type == 'None'): ?> class="active" <?php endif; ?>>
            <a href="javascript:void(0);" wire:click='tab("None")'>Text Posts (<?php echo e($counts['text']); ?>)</a>
        </li>
    </ul>

    <div class="table-responsive">
    <table class="table table-responsive">
    <tr>
        <td>ID</td>
        <td>User</td>
        <td>Lock</td>
        <td>Date</td>
        <td>Text</td>
        <td>Media</td>
        <td>Delete</td>
    </tr>
    <?php $__empty_1 = true; $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?> 
    <tr>
        <td><?php echo e($p->id); ?></td>
        <td>
            <a href="<?php echo e(route('profile.show', ['username' => $p->profile->username])); ?>" target="_blank">
                <?php echo e($p->profile->handle); ?>

            </a>
        </td>
        <td>
            <?php if($p->lock_type == 'Paid'): ?>
                <span class="text text-danger">Locked</span>
            <?php else: ?>
                <span class="text text-success">Free</span>
            <?php endif; ?>
        </td>
        <td>
            <?php echo e($p->created_at->format('jS F Y')); ?><br>
            <?php echo e($p->created_at->format('H:i a')); ?>

        </td>
        <td>
            <article style="max-height: 200px; overflow: scroll;">
                <?php echo clean($p->text_content); ?>

            </article>
        </td>
        <td>
            <?php if($p->media_type == 'Image'): ?>
            <a href="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" data-toggle="lightbox">
                <img src="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" alt="" class="img-responsive" width="200"/>
            </a>
            <?php elseif($p->media_type == 'Video'): ?> 
            <video width="420" height="340" controls controlsList="nodownload" disablePictureInPicture>
                <source src="<?php echo e(\Storage::disk($p->disk)->url($p->video_url)); ?>" type="video/mp4" />
            </video>
            <?php elseif($p->media_type == 'Audio'): ?> 
            <audio class="w-100 mb-4" controls controlsList="nodownload">
                <source src="<?php echo e(\Storage::disk($p->disk)->url($p->audio_url)); ?>" type="audio/mp3">
            </audio>
            <?php elseif($p->media_type == 'ZIP'): ?>
                <a href="<?php echo e(\Storage::disk($p->disk)->url($p->media_content)); ?>" data-toggle="lightbox">
                    Download ZIP
                </a>
            <?php else: ?>
                No media
            <?php endif; ?>
        </td>
        <td>
            <a href="javascript:void(0);" class="text-danger" wire:click="confirmDelete(<?php echo e($p->id); ?>)" onclick="confirm('Confirm delete?') || event.stopImmediatePropagation()">
                Delete
            </a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        There are no contents of this type in database.
    <?php endif; ?>
    </div>
    </table>

    <?php echo e($contents->links()); ?>


</div>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/content-moderation.blade.php ENDPATH**/ ?>