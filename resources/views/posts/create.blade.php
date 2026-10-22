@extends('layouts.app')
@section('title', 'Novo post')
@section('content')
    <div class="page-head"><h1>Novo post</h1></div>
    <form method="POST" action="{{ route('posts.store') }}">
        @include('posts._form', ['submit' => 'Publicar post'])
    </form>
@endsection
