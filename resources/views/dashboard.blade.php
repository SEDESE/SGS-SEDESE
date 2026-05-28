@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h4>Dashboard</h4>
<p class="text-muted">Bem-vindo, {{ auth()->user()->name }}.</p>
@endsection