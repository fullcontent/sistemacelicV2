@extends('adminlte::page')

@section('title', 'Gestão de Usuários')

@section('content_header')
<h1>Gestão de Usuários</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <x-adminlte-alert theme="success" title="Sucesso" dismissable>
                {{ session('success') }}
            </x-adminlte-alert>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Usuários no Sistema</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
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
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <img src="{{ $user->adminlte_image() }}" class="img-circle elevation-1 mr-2"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge {{ $user->group_type == 'ADMIN' ? 'badge-danger' : ($user->group_type == 'CASTRO' ? 'badge-primary' : 'badge-info') }}">
                                        {{ $user->group_type }}
                                    </span>
                                </td>
                                <td>
                                    Nível {{ $user->hierarchy_level }}<br>
                                    <small
                                        class="text-muted">{{ $user->roles->pluck('name')->join(', ') ?: 'Sem Role' }}</small>
                                </td>
                                <td>
                                    @if($user->status)
                                        <span class="badge badge-success">Ativo</span>
                                    @else
                                        <span class="badge badge-danger">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        /** @var \App\Models\User $currentUser */
                                        $currentUser = Auth::user();
                                    @endphp
                                    @if($currentUser->hierarchy_level <= 2 && ($currentUser->hierarchy_level <= $user->hierarchy_level || $currentUser->id === $user->id))
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-xs btn-default text-primary mx-1 shadow"
                                            title="Configurar Usuário (Permissões/Senha)">
                                            <i class="fa fa-lg fa-fw fa-user-cog"></i>
                                        </a>

                                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-xs btn-default {{ $user->status ? 'text-danger' : 'text-success' }} mx-1 shadow"
                                                title="{{ $user->status ? 'Desativar' : 'Ativar' }}">
                                                <i class="fa fa-lg fa-fw {{ $user->status ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock"></i> Restrito</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@stop