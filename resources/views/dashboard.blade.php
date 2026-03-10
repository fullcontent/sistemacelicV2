@extends('adminlte::page')

@section('title', 'Dashboard - CELIC')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bem-vindo(a) ao Sistema CELIC</h3>
            </div>
            <div class="card-body">
                <p>Você está logado(a) como {{ auth()->user()->name }}!</p>
                <p>Nível atual de acesso: {{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Add extra CSS here --}}
@stop

@section('js')
<script> console.log("Dashboard loaded!"); </script>
@stop