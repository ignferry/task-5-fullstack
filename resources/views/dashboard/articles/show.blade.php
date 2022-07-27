@extends('dashboard.layouts.main')

@section('dashboard-content')
    <div class="container">
        <div class="row my-3">
            <div class="col-lg-8">
                <h1 class="mb-4">{{ $article->title }}</h1>
        
                <a href="/dashboard/articles" class="btn btn-success"><span data-feather="arrow-left" class="align-text-bottom"></span> Back to my articles</a>
                <a href="/dashboard/articles/{{ $article->id }}/edit" class="btn btn-warning"><span data-feather="edit" class="align-text-bottom"></span> Edit</a>
                <form action="/dashboard/articles/{{ $article->id }}" method="article" class="d-inline">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">
                      <span data-feather="x-circle" class="align-text-bottom"></span> Delete
                    </button>
                </form>

                @if ($article->image)
                    <div style="max-height: 350px; overflow: hidden">
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top">
                    </div>
                @else
                    <img src="https://source.unsplash.com/1200x400" class="card-img-top">
                @endif
                
                <article class="my-4 fs-5">
                    {!! $article->content !!} 
                </article>
            </div>
        </div>
    </div>
@endsection
