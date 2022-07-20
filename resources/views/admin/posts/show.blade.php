@extends('layouts.dashboard')

@section('content')
    <h1> {{ $post->title }} </h1>
    @if ($post->cover)
        <img src="{{ asset('storage/' . $post->cover) }}" alt=""> 
    @endif
    <p>Slug: {{ $post->slug }}</p>
    <p>Categoria: {{ $category ? $category->name : 'Nessuna categoria' }}</p>
    <p><strong>Tags: </strong>

        @forelse ($post->tags as $tag)
            {{$tag->name}}{{$loop->last ? '' : ', '}}
        @empty
            Nessun Tag
        @endforelse

    </p>
    <p>{{ $post->content }}</p>

    <div class="d-flex">
        <a class="btn btn-primary mr-3" href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">Modifica</a>

        <form action="{{ route('admin.posts.destroy', ['post' => $post->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Elimina</button>
        </form>
    </div>

@endsection