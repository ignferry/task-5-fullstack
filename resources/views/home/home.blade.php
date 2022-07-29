@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($articles as $article)
                <div class="col">
                    <div class="card h-100">
                        <a href="/articles/{{ $article->id }}">
                            <img src="@if($article->image) {{ asset('storage/' . $article->image) }} @else https://source.unsplash.com/400x400 @endif" 
                                class="card-img" alt="" style="min-height:200px">
                            
                            <div class="card-img-overlay d-flex p-0 text-white align-items-end">
                                <div class="flex-fill p-4" style="background-color: rgba(0,0,0,0.5)">
                                    <h5 class="card-title flex-fill text-center p-0">{{ $article->title }}</h5>
                                        <p class="text-center p-0">@if($article->category){{ $article->category->name }} -@endif {{ $article->user->name }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <nav aria-label="pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <li class="page-item @if($pagination->current_page == 1)disabled @endif">
                    <a class="page-link" href="/articles?page={{ $pagination->current_page-1 }}">Previous</a>
                </li>
    
                <li class="page-item @if ($pagination->current_page == 1) d-none @endif">
                    <a class="page-link" href="/articles?page={{ $pagination->current_page-1 }}">{{ $pagination->current_page-1 }}</a>
                </li>
    
                <li class="page-item active">
                    <span class="page-link">
                        {{ $pagination->current_page }}
                    </span>
                </li>
    
                <li class="page-item @if ($pagination->current_page == $pagination->last_page) d-none @endif">
                    <a class="page-link" href="/articles?page={{ $pagination->current_page+1 }}">{{ $pagination->current_page+1 }}</a>
                </li>
    
                <li class="page-item @if($pagination->current_page == $pagination->last_page)disabled @endif">
                    <a class="page-link" href="/articles?page={{ $pagination->current_page+1 }}">Next</a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
