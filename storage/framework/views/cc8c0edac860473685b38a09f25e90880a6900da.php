<?php $__env->startSection('section_title'); ?>
<strong>Cloud Storage Settings</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>

<form method="POST" action="/admin/save-cloud-settings">
<?php echo e(csrf_field()); ?>


<div class="row">
    <div class="col-xs-12 col-md-4">
        <dt>Which storage to use as default?</dt>
        <dd>
            <select name="default_storage" class="form-control">
                <option value="public" <?php if(opt('default_storage', 'public') == 'public'): ?> selected <?php endif; ?>>Default: No cloud, just use my server</option>
                <option value="wasabi" <?php if(opt('default_storage', 'public') == 'wasabi'): ?> selected <?php endif; ?>>Wasabi Storage</option>
                <option value="digitalocean" <?php if(opt('default_storage', 'public') == 'digitalocean'): ?> selected <?php endif; ?>>DigitalOcean Spaces</option>
                <option value="backblaze" <?php if(opt('default_storage', 'public') == 'backblaze'): ?> selected <?php endif; ?>>Backblaze B2</option>
                <option value="vultr" <?php if(opt('default_storage', 'public') == 'vultr'): ?> selected <?php endif; ?>>VultR Object Storage</option>
                <option value="s3" <?php if(opt('default_storage', 'public') == 's3'): ?> selected <?php endif; ?>>Amazon AWS S3</option>
            </select>
        </dd>
        <br>
    </div>
</div>

<div class="row">

	<div class="col-xs-12 col-md-4">
    <h4>Wasabi Settings</h4>
    <a href="https://wasabi.com/cloud-storage-pricing/" target="_blank">https://wasabi.com/cloud-storage-pricing/</a>
    <hr>
	<dl>
		<dt>WAS Access Key</dt>
		<dd>
			<input type="text" name="WAS_ACCESS_KEY_ID" value="<?php echo e(opt('WAS_ACCESS_KEY_ID')); ?>" class="form-control" placeholder="">
		</dd>
		<br>
		<dt>WAS Secret Key</dt>
		<dd>
			<input type="text" name="WAS_SECRET_ACCESS_KEY" value="<?php echo e(opt('WAS_SECRET_ACCESS_KEY')); ?>" class="form-control" placeholder="">
        </dd>
        <br>
		<dt>WAS Region (example: eu-central-1)</dt>
		<dd>
			<input type="text" name="WAS_DEFAULT_REGION" value="<?php echo e(opt('WAS_DEFAULT_REGION')); ?>" class="form-control" placeholder="">
        </dd>
        <br>
		<dt>WAS Bucket Name</dt>
		<dd>
			<input type="text" name="WAS_BUCKET" value="<?php echo e(opt('WAS_BUCKET')); ?>" class="form-control" placeholder="">
		</dd>
        <br>
		<dd>
			<input type="submit" name="sb_settings" value="Save WAS Settings" class="btn btn-primary">	
		</dd>
	</dl>
    </div><!-- ./wasabi -->
    
    <div class="col-xs-12 col-md-4">
        <h4>DigitalOcean Spaces Settings</h4>
        <a href="https://www.digitalocean.com/products/spaces/" target="_blank">https://www.digitalocean.com/products/spaces/</a>
        <hr>
        <dl>
            <dt>DOS Access Key</dt>
            <dd>
                <input type="text" name="DOS_ACCESS_KEY_ID" value="<?php echo e(opt('DOS_ACCESS_KEY_ID')); ?>" class="form-control" placeholder="">
            </dd>
            <br>
            <dt>DOS Secret Key</dt>
            <dd>
                <input type="text" name="DOS_SECRET_ACCESS_KEY" value="<?php echo e(opt('DOS_SECRET_ACCESS_KEY')); ?>" class="form-control" placeholder="">
            </dd>
            <br>
            <dt>DOS Region (example: ams3, fra1, etc)</dt>
            <dd>
                <input type="text" name="DOS_DEFAULT_REGION" value="<?php echo e(opt('DOS_DEFAULT_REGION')); ?>" class="form-control" placeholder="">
            </dd>
            <br>
            <dt>DOS Bucket Name</dt>
            <dd>
                <input type="text" name="DOS_BUCKET" value="<?php echo e(opt('DOS_BUCKET')); ?>" class="form-control" placeholder="">
            </dd>
            <br>
            <dd>
                <input type="submit" name="sb_settings" value="Save DOS Settings" class="btn btn-primary">	
            </dd>
        </dl>
        </div><!-- ./digitalocean -->

        <div class="col-xs-12 col-md-4">
            <h4>BackBlaze B2 Settings</h4>
            <a href="https://www.backblaze.com/b2/cloud-storage.html" target="_blank">https://www.backblaze.com/b2/cloud-storage.html</a>
            <hr>
            <dl>
                <dt>B2 Account ID</dt>
                <dd>
                    <input type="text" name="BACKBLAZE_ACCOUNT_ID" value="<?php echo e(opt('BACKBLAZE_ACCOUNT_ID')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>B2 <strong>Master</strong> Application Key</dt>
                <dd>
                    <input type="text" name="BACKBLAZE_APP_KEY" value="<?php echo e(opt('BACKBLAZE_APP_KEY')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>B2 Bucket Name</dt>
                <dd>
                    <input type="text" name="BACKBLAZE_BUCKET" value="<?php echo e(opt('BACKBLAZE_BUCKET')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>B2 ENDPOINT (Bucket Region, example: s3.eu-central-003.backblazeb2.com)</dt>
                <dd>
                    <input type="text" name="BACKBLAZE_REGION" value="<?php echo e(opt('BACKBLAZE_REGION')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dd>
                    <input type="submit" name="sb_settings" value="Save B2s Settings" class="btn btn-primary">	
                </dd>
            </dl>
        </div><!-- ./BackBlaze B2 -->

        <div class="col-xs-12 col-md-4">
            <h4>VultR Cloud Storage</h4>
            <a href="https://www.vultr.com/docs/vultr-object-storage" target="_blank">https://www.vultr.com/docs/vultr-object-storage</a>
            <hr>
            <dl>
                <dt>VultR Access Key</dt>
                <dd>
                    <input type="text" name="VULTR_ACCESS_KEY_ID" value="<?php echo e(opt('VULTR_ACCESS_KEY_ID')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>VultR Secret Key</dt>
                <dd>
                    <input type="text" name="VULTR_SECRET_ACCESS_KEY" value="<?php echo e(opt('VULTR_SECRET_ACCESS_KEY')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>VultR Region (example: ewr1, etc)</dt>
                <dd>
                    <input type="text" name="VULTR_DEFAULT_REGION" value="<?php echo e(opt('VULTR_DEFAULT_REGION')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>VultR Bucket Name</dt>
                <dd>
                    <input type="text" name="VULTR_BUCKET" value="<?php echo e(opt('VULTR_BUCKET')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dd>
                    <input type="submit" name="sb_settings" value="Save VultR Settings" class="btn btn-primary">	
                </dd>
            </dl>
        </div><!-- ./VultR -->

        <div class="col-xs-12 col-md-4">
            <h4>Amazon AWS S3</h4>
            <a href="https://aws.amazon.com/s3/" target="_blank">https://aws.amazon.com/s3/</a>
            <hr>
            <dl>
                <dt>AWS Access Key</dt>
                <dd>
                    <input type="text" name="AWS_ACCESS_KEY_ID" value="<?php echo e(opt('AWS_ACCESS_KEY_ID')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>AWS Secret Key</dt>
                <dd>
                    <input type="text" name="AWS_SECRET_ACCESS_KEY" value="<?php echo e(opt('AWS_SECRET_ACCESS_KEY')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>AWS Region (example: us-east-2, etc)</dt>
                <dd>
                    <input type="text" name="AWS_DEFAULT_REGION" value="<?php echo e(opt('AWS_DEFAULT_REGION')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dt>AWS Bucket Name</dt>
                <dd>
                    <input type="text" name="AWS_BUCKET" value="<?php echo e(opt('AWS_BUCKET')); ?>" class="form-control" placeholder="">
                </dd>
                <br>
                <dd>
                    <input type="submit" name="sb_settings" value="Save AWS S3 Settings" class="btn btn-primary">	
                </dd>
            </dl>
        </div><!-- ./VultR -->

</div>

</form>

</div><!-- ./row -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/cloud-settings.blade.php ENDPATH**/ ?>