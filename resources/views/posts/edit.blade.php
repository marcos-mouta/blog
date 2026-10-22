@extends('layouts.app')
@section('title', 'Editar post')
@section('content')
    <div class="page-head"><h1>Editar post</h1></div>
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @method('PUT')
        @include('posts._form', ['submit' => 'Salvar alterações'])
    </form>
@endsection
