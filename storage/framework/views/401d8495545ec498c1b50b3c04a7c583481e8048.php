<?php $__env->startSection('content'); ?>

<div class="content animate-panel">

    <div class="row">
        <div class="col-lg-3">
            <div class="hpanel stats hyellow">
                <div class="panel-body h-150 list">
                    <div class="stats-title pull-left">
                        <h4>Admin</h4>
                    </div>
                    <div class="stats-icon pull-right">
                        <i class="pe-7s-user fa-4x"></i>
                    </div>
                    <div class="m-t-xl">
                        <h1 class="text-success">Admin</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>