<?php $__env->startSection('title', 'Dashboard - CELIC'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bem-vindo(a) ao Sistema CELIC</h3>
            </div>
            <div class="card-body">
                <p>Você está logado(a) como <?php echo e(auth()->user()->name); ?>!</p>
                <p>Nível atual de acesso: <?php echo e(auth()->user()->roles->pluck('name')->join(', ')); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script> console.log("Dashboard loaded!"); </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistemacelicV2\resources\views/dashboard.blade.php ENDPATH**/ ?>