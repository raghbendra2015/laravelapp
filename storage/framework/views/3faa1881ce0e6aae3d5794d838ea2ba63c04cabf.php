<?php $__env->startSection('content'); ?>

<div class="small-header transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li>Change Password</li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Change Password
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
                        <form data-parsley-validate="" method="POST" class="form-horizontal" name="change_password_form" action="<?php echo e(url('update-user-password')); ?>">
                            <?php echo e(csrf_field()); ?>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="col-sm-4 control-label">Old Password:<span class="asterisk">*</span></label>
                                    <div class="col-sm-8">
                                        <input data-parsley-required-message="<?php echo e(config('constant.change_password_page.OLD_PASSWORD_REQUIRED')); ?>" required="" id="old_password" type="password" class="form-control" name="old_password" value="" maxlength="30">
                                        <?php if($errors->has('old_password')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('old_password')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="col-sm-4 control-label">New Password:<span class="asterisk">*</span></label>
                                    <div class="col-sm-8">
                                        <input data-parsley-required-message="<?php echo e(config('constant.change_password_page.NEW_PASSWORD_REQUIRED')); ?>" required="" id="new_password" type="password" class="form-control" name="new_password" value="" maxlength="30">
                                <?php if($errors->has('new_password')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('new_password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label class="col-sm-4 control-label">Confirm Password:<span class="asterisk">*</span></label>
                                    <div class="col-sm-8">
                                       <input data-parsley-required-message="<?php echo e(config('constant.change_password_page.CONFIRM_PASSWORD_REQUIRED')); ?>" data-parsley-equalto="#new_password" required="" id="password_confirm" type="password" class="form-control" name="password_confirm" value="" maxlength="30" data-parsley-equalto-message="<?php echo e(config('constant.change_password_page.CONFIRM_PASSWORD_EQUAL')); ?>">
                                       <?php if($errors->has('password_confirm')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password_confirm')); ?></strong>
                                    </span>
                                <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                   <button class="btn btn-primary" type="submit">Save Changes</button>
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