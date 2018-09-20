<?php $__env->startSection('content'); ?>

    <div class="small-header transition animated fadeIn">
        <div class="hpanel">
            <div class="panel-body">
                <div id="hbreadcrumb" class="pull-right">
                    <ol class="hbreadcrumb breadcrumb">
                        <li>Upload Leads</li>
                    </ol>
                </div>
                <h2 class="font-light m-b-xs">
                    Upload Leads
                </h2>
            </div>
        </div>
    </div>


    <div class="content animate-panel">
        <div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="hpanel">
                        <div class="panel-body">
                            <form data-parsley-validate="" method="POST" class="form-horizontal" name="upload_leads_form" action="<?php echo e(url('upload-leads')); ?>"  enctype="multipart/form-data">
                                <?php echo e(csrf_field()); ?>

                                <div class="row">
                                    <div class="form-group col-lg-6 upload"><span>
                <input id="upload_browse" type="file" name="upload_leads" required data-parsley-fileextension='' data-parsley-errors-container="#bottom-data" data-parsley-required-message="Please select file to upload.">
                <a href="" id="upload_link" class="yellow"></a></span>Browse a file to upload <i class="fa fa-cloud-upload yellow" aria-hidden="true"></i>

                                    </div>
                                    <span class="text-muted f-12">Allowed file type is Excel (.xlsx or .xls) </span>
                                </div>
                                <div class="row">
                                    <div id="upload_file_name" class="small"></div>
                                    <span class="clearfix"></span>
                                    <div id="bottom-data"></div>
                                    <?php if($errors->has('upload_rates')): ?>
                                        <div class="error"><?php echo e($errors->first('upload_leads')); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">Upload</button>
                                        <a class="btn btn-default" href="<?php echo e(url('dashboard')); ?>">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>