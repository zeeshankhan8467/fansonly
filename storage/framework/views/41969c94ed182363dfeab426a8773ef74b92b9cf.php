<?php $__env->startSection('section_title'); ?>
	<strong>Site Entry Popup</strong>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('section_body'); ?>

<div class="well">
    This will create an entry popup required to be "confirmed" to be able to browse the site or send the user away if he disagrees
</div>

<div class="row">
<div class="col-xs-12 col-md-4">

<form method="POST" action="/admin/save/entry-popup">
    <?php echo csrf_field(); ?>

    <label>Enable Site Entry Popup?</label>
    <select name="site_entry_popup" class="form-control">
        <option value="Yes" <?php if(opt('site_entry_popup', 'No') == 'Yes'): ?> selected <?php endif; ?>>Yes</option>
        <option value="No" <?php if(opt('site_entry_popup', 'No') == 'No'): ?> selected <?php endif; ?>>No (default)</option>
    </select>
    <br>
    
    <label>Entry Popup Title</label>
    <input type="text" name="entry_popup_title" value="<?php echo e(opt('entry_popup_title', 'Entry popup title')); ?>" class="form-control" />
    <br>

    <label>Entry Popup Message</label>
    <input type="text" name="entry_popup_message" value="<?php echo e(opt('entry_popup_message', 'Entry popup message')); ?>" class="form-control" />
    <br>

    <label>Confirm button text</label>
    <input type="text" name="entry_popup_confirm_text" value="<?php echo e(opt('entry_popup_confirm_text', 'Continue')); ?>" class="form-control" />
    <br>

    <label>Cancel button text</label>
    <input type="text" name="entry_popup_cancel_text" value="<?php echo e(opt('entry_popup_cancel_text', 'Cancel')); ?>" class="form-control" />
    <br>

    <label>Cancel button redirect away URL:</label>
    <input type="text" name="entry_popup_awayurl" value="<?php echo e(opt('entry_popup_awayurl', 'https://google.com')); ?>" class="form-control" />
    <br>

    <input type="submit" name="sbPopup" value="Save Settings" class="btn btn-primary" />
</form>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/entry-popup.blade.php ENDPATH**/ ?>