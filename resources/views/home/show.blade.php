@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center m-4">
            <div class="col-md-8">
                <h1 class="mb-4">{{ $article->title }}</h1>
        
                <p>By : {{ $article->user->name }} in {{ $article->category->name }}</p>

                @if ($article->image)
                    <div style="max-height: 400px">
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->category->name }}">
                    </div>
                @else
                    <img src="https://source.unsplash.com/1200x400?{{ $article->category->name }}" class="card-img-top mt-2" alt="{{ $article->category->name }}">
                @endif
        
                <article class="my-4 fs-5">
                    {!! $article->content !!} 
                </article>
                <a href="/articles">Back to Home</a>
            </div>
        </div>
    </div>
@endsection