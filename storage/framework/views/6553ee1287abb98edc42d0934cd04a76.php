

<?php $__env->startSection('title', 'Gestão de Usuários'); ?>

<?php $__env->startSection('content_header'); ?>
<h1>Gestão de Usuários</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <?php if(session('success')): ?>
            <?php if (isset($component)) { $__componentOriginal9d0273d6550ddf39dc9a547c96729fed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::resolve(['theme' => 'success','title' => 'Sucesso','dismissable' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php echo e(session('success')); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $attributes = $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $component = $__componentOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Usuários no Sistema</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Novo Usuário
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Grupo</th>
                            <th>Roles Nível Mín.</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($user->id); ?></td>
                                <td>
                                    <img src="<?php echo e($user->adminlte_image()); ?>" class="img-circle elevation-1 mr-2"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                    <?php echo e($user->name); ?>

                                </td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <span
                                        class="badge <?php echo e($user->group_type == 'ADMIN' ? 'badge-danger' : ($user->group_type == 'CASTRO' ? 'badge-primary' : 'badge-info')); ?>">
                                        <?php echo e($user->group_type); ?>

                                    </span>
                                </td>
                                <td>
                                    Nível <?php echo e($user->hierarchy_level); ?><br>
                                    <small
                                        class="text-muted"><?php echo e($user->roles->pluck('name')->join(', ') ?: 'Sem Role'); ?></small>
                                </td>
                                <td>
                                    <?php if($user->status): ?>
                                        <span class="badge badge-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        /** @var \App\Models\User $currentUser */
                                        $currentUser = Auth::user();
                                    ?>
                                    <?php if($currentUser->hierarchy_level <= 2 && ($currentUser->hierarchy_level <= $user->hierarchy_level || $currentUser->id === $user->id)): ?>
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                            class="btn btn-xs btn-default text-primary mx-1 shadow"
                                            title="Configurar Usuário (Permissões/Senha)">
                                            <i class="fa fa-lg fa-fw fa-user-cog"></i>
                                        </a>

                                        <form action="<?php echo e(route('admin.users.toggleStatus', $user)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit"
                                                class="btn btn-xs btn-default <?php echo e($user->status ? 'text-danger' : 'text-success'); ?> mx-1 shadow"
                                                title="<?php echo e($user->status ? 'Desativar' : 'Ativar'); ?>">
                                                <i class="fa fa-lg fa-fw <?php echo e($user->status ? 'fa-ban' : 'fa-check'); ?>"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fas fa-lock"></i> Restrito</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistemacelicV2\resources\views/admin/users/index.blade.php ENDPATH**/ ?>