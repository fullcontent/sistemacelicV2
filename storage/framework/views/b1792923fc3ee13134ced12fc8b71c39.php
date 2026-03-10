

<?php $__env->startSection('title', 'Editar Usuário - Permissões'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Gerenciar Usuário: <?php echo e($user->name); ?></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>

    <div class="row">
        <div class="col-md-4">
            <!-- DADOS DO USUÁRIO -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile text-center">
                    <img class="profile-user-img img-fluid img-circle shadow-sm mb-3"
                         src="<?php echo e($user->adminlte_image()); ?>"
                         alt="User profile picture"
                         style="width: 100px; height: 100px; object-fit: cover;">
                         
                    <h3 class="profile-username text-center"><?php echo e($user->name); ?></h3>
                    <p class="text-muted text-center"><?php echo e($user->email); ?></p>

                    <div class="form-group text-left">
                        <label for="avatar" class="text-xs">Alterar Avatar</label>
                        <div class="input-group input-group-sm">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="avatar" name="avatar">
                                <label class="custom-file-label" for="avatar">Escolher arquivo</label>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Grupo</b> <a class="float-right badge <?php echo e($user->group_type == 'ADMIN' ? 'badge-danger' : ($user->group_type == 'CASTRO' ? 'badge-primary' : 'badge-info')); ?>"><?php echo e($user->group_type); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <a class="float-right">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                  <input type="checkbox" class="custom-control-input" id="statusSwitch" name="status" value="1" <?php echo e($user->status ? 'checked' : ''); ?>>
                                  <label class="custom-control-label" for="statusSwitch">Ativo / Inativo</label>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Nível Hierárquico Eficaz</b> <a class="float-right"><?php echo e($user->hierarchy_level); ?></a>
                        </li>
                    </ul>

                    <strong><i class="fas fa-user-tag mr-1"></i> Atribuir Roles Base (Padrões)</strong>
                    <p class="text-muted text-sm mt-2">Roles concedem blocos prontos de permissões.</p>
                    <div class="row">
                        <?php $__currentLoopData = $assignableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?php echo e($role->name); ?>" 
                                        <?php echo e(in_array($role->name, $userRoleNames) ? 'checked' : ''); ?>>
                                    <label class="form-check-label"><?php echo e($role->name); ?></label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php
                        /** @var \App\Models\User $currentUser */
                        $currentUser = Auth::user();
                    ?>
                    <?php if(in_array($currentUser->group_type, ['CASTRO', 'ADMIN']) && $currentUser->hierarchy_level <= 2): ?>
                        <hr>
                        <strong><i class="fas fa-key mr-1"></i> Segurança</strong>
                        <div class="form-group mt-2">
                            <label for="password">Nova Senha (Opcional)</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Deixe em branco para manter">
                            <small class="text-muted">Mudar apenas se necessário.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block mb-3"><b>Salvar Alterações Globais</b></button>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-default btn-block">Voltar</a>
        </div>

        <div class="col-md-8">
            <!-- MATRIZ DE PERMISSÕES -->
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-lock text-warning"></i> Matriz de Permissões Específicas</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-sm">Checkboxs <strong>marcados e travados (🔒)</strong> foram herdados pelas Roles. Para conceder algo além da role base, marque livremente.</p>
                    
                    <div class="row">
                        <?php $__currentLoopData = $permissionsGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-4">
                            <h5 class="text-uppercase font-weight-bold text-secondary border-bottom pb-2"><?php echo e($groupName); ?></h5>
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Verificamos se o carinha tem essa permissao APENAS VIA ROLE. Se for, ele é travado visualmente.
                                    $hasPermissionViaRole = false;
                                    // Verifica se PODE editar (Usuario logado nao pode dar permissao que ele mesmo nao tenha)
                                    /** @var \App\Models\User $currentUser */
                                    $currentUser = Auth::user();
                                    $canGrant = $currentUser->hasPermissionTo($permission->name);

                                    // Spatie allows testing this via user roles vs direct permissions, or we can just assume checked and disabled if inherited.
                                    // By simple logic, if it's in userPermissionNames but NOT in directPermissionNames, it's strictly via Role.
                                    if(in_array($permission->name, $userPermissionNames) && !in_array($permission->name, $directPermissionNames)) {
                                        $hasPermissionViaRole = true;
                                    }
                                    
                                    // Is it checked? (Either via Role or individually)
                                    $isChecked = in_array($permission->name, $userPermissionNames);
                                ?>
                                
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>"
                                        <?php echo e($isChecked ? 'checked' : ''); ?> 
                                        <?php echo e(($hasPermissionViaRole || !$canGrant) ? 'disabled' : ''); ?>>
                                    <label class="form-check-label <?php echo e($hasPermissionViaRole ? 'text-muted' : ''); ?>">
                                        <?php echo e($permission->name); ?> 
                                        <?php if($hasPermissionViaRole): ?> <i class="fas fa-lock text-xs text-muted" title="Herdada por Role"></i> <?php endif; ?>
                                        <?php if(!$canGrant): ?> <i class="fas fa-ban text-xs text-danger" title="Você não tem poder para conceder isto"></i> <?php endif; ?>
                                    </label>
                                    
                                    
                                    <?php if($isChecked && !$hasPermissionViaRole): ?>
                                        <input type="hidden" name="permissions[]" value="<?php echo e($permission->name); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistemacelicV2\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>