<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Lista apenas os usuários que o operador tem nível para ver (ou todos se ele for nivel <= 2)
     */
    public function index()
    {
        $currentUser = Auth::user();
        $query = User::with('roles');

        // Um Administrador CLIENTE só vê os da mesma empresa/grupo
        if ($currentUser->group_type === 'CLIENTE') {
            $query->where('group_type', 'CLIENTE');
            if ($currentUser->client_id) {
                $query->where('client_id', $currentUser->client_id);
            }
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tela de criação de usuário.
     */
    public function create()
    {
        $currentUser = Auth::user();

        // Apenas Admin Castro (2) ou superior podem criar
        if ($currentUser->hierarchy_level > 2) {
            abort(403);
        }

        $assignableRoles = Role::where('hierarchy_level', '>=', $currentUser->hierarchy_level)
            ->when($currentUser->group_type === 'CLIENTE', function ($q) {
                return $q->where('name', 'like', '%(CLIENTE)%');
            })
            ->get();

        return view('admin.users.create', compact('assignableRoles'));
    }

    /**
     * Persiste o novo usuário.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        if ($currentUser->hierarchy_level > 2) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Determina Group Type pelo domínio
        $groupType = 'CLIENTE';
        $email = strtolower($request->email);

        if ($email === 'bgc1988@gmail.com' || str_ends_with($email, '@castroli.com.br')) {
            $groupType = 'CASTRO';
        }

        if ($currentUser->group_type === 'CLIENTE') {
            $groupType = 'CLIENTE';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatarPath,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'group_type' => $groupType,
            'status' => true,
        ]);

        if ($request->has('roles')) {
            $allowedRoles = Role::where('hierarchy_level', '>=', $currentUser->hierarchy_level)
                ->pluck('name')
                ->toArray();
            $rolesToSync = array_intersect($request->roles, $allowedRoles);
            $user->syncRoles($rolesToSync);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Edição do Usuário e da Matrix.
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        // Bloquear edição se o alvo tiver nível maior ou igual na hierarquia
        // Nível 1 (Programador) NUNCA pode ser tocado por Nível 2 (Admin) se não for ele mesmo
        if ($currentUser->id !== $user->id && $user->hierarchy_level < $currentUser->hierarchy_level) {
            abort(403, 'Você não possui nível hierárquico suficiente para editar as permissões deste usuário superior.');
        }

        // Bloquear mixagem CASTRO / ADMIN / CLIENTE
        if ($currentUser->group_type === 'CLIENTE' && in_array($user->group_type, ['CASTRO', 'ADMIN'])) {
            abort(403, 'Acesso restrito apenas ao ambiente de clientes.');
        }

        // Pegamos as Roles permitidas que este current_user pode designar
        // Ele não pode designar algo que seja mais poderoso que ele mesmo.
        $assignableRoles = Role::where('hierarchy_level', '>=', $currentUser->hierarchy_level)
            ->when($currentUser->group_type === 'CLIENTE', function ($q) {
                return $q->where('name', 'like', '%(CLIENTE)%');
            })
            ->get();

        $allPermissions = Permission::orderBy('name')->get();
        // Agrupar visualmente pelo underline, ex: 'financeiro', 'servicos', 'admin'
        $permissionsGrouped = $allPermissions->groupBy(function ($perm) {
            return explode('_', $perm->name)[0]; // Retorna 'financeiro' a partir de 'financeiro_gerar_relatorio'
        });

        // Current Roles IDs para os Checkboxes
        $userRoleNames = $user->roles->pluck('name')->toArray();
        $userPermissionNames = $user->getAllPermissions()->pluck('name')->toArray();
        $directPermissionNames = $user->getDirectPermissions()->pluck('name')->toArray(); // Somente do usuário, nao via Roles

        return view('admin.users.edit', compact(
            'user',
            'assignableRoles',
            'permissionsGrouped',
            'userRoleNames',
            'userPermissionNames',
            'directPermissionNames'
        ));
    }

    /**
     * Salva as alterações da Matriz de Roles/Permissões
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($user->hierarchy_level < $currentUser->hierarchy_level && $currentUser->id !== $user->id) {
            abort(403, 'Você não possui nível para editar as permissões deste usuário.');
        }

        // Validação das Roles e Permissões envidas
        $request->validate([
            'roles' => 'array',
            'permissions' => 'array', // Custom permissions além da role
            'status' => 'boolean',
            'password' => 'nullable|string|min:8', // Validação opcional da senha
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $rolesToSync = $request->input('roles', []);
        $permissionsToSync = $request->input('permissions', []);

        // Auditoria: Somente roles que o cara logado tem poder para entregar
        $allowedRoles = Role::where('hierarchy_level', '>=', $currentUser->hierarchy_level)->pluck('name')->toArray();
        $rolesToSync = array_intersect($rolesToSync, $allowedRoles);

        // Processo de Update
        $user->syncRoles($rolesToSync);
        $user->syncPermissions($permissionsToSync); // Isso lida com direct permissions

        if ($request->has('status')) {
            $user->status = $request->boolean('status');
        }

        // Alteração de Senha (Apenas Programador / Administrador CASTRO)
        if ($request->filled('password')) {
            if (in_array($currentUser->group_type, ['CASTRO', 'ADMIN']) && $currentUser->hierarchy_level <= 2) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
            } else {
                abort(403, 'Você não possui permissão para alterar a senha de usuários.');
            }
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Dados, permissões e roles atualizadas com sucesso.');
    }

    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();
        if ($user->hierarchy_level < $currentUser->hierarchy_level) {
            abort(403);
        }

        $user->status = !$user->status;
        $user->save();

        return back()->with('success', 'Status do usuário alterado.');
    }
}
