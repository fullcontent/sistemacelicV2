@extends('adminlte::page')

@section('title', 'Novo Usuário')

@section('content_header')
<h1>Cadastrar Novo Usuário</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Dados Gerais</h3>
                </div>
                <div class="card-body">
                    <div class="form-group text-center mb-4">
                        <label>Avatar (Opcional)</label>
                        <div class="custom-file text-left">
                            <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror"
                                id="avatar" name="avatar">
                            <label class="custom-file-label" for="avatar">Escolher foto...</label>
                            @error('avatar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Senha Inicial</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required minlength="8">
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <label>Atribuir Perfil Inicial (Role)</label>
                    <div class="row">
                        @foreach($assignableRoles as $role)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                        value="{{ $role->name }}">
                                    <label class="form-check-label">{{ $role->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary shadow"><b>CRIAR USUÁRIO AGORA</b></button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop