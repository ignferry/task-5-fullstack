@extends('dashboard.layouts.main')

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      @can('admin')
        <h1>All Articles</h1>
      @else
        <h1>My Articles</h1>
      @endcan
    </div>

    {{-- @if (session()->has('success'))
      <div class="alert alert-success col-lg-8" role="alert">
        {{ session('success') }}
      </div>
    @endif --}}
    <div class="table-responsive col-lg-8">
      <a href="/dashboard/articles/create" class="btn btn-primary mb-3">Create new article</a>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Title</th>
              <th scope="col">Category</th>
              @can('admin')
                <th scope="col">Author</th>
              @endcan
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($articles as $article)
              <tr>
                <td>{{ $article->title }}</td>
                <td>
                  @if($article->category)
                    {{ $article->category->name }}
                  @endif
                </td>
                @can('admin')
                  <td>{{ $article->user->name }}</td>
                @endcan
                
                <td>
                  <a href="/dashboard/articles/{{ $article->id }}" class="badge bg-info"><span data-feather="eye" class="align-text-bottom"></span></a>
                  <a href="/dashboard/articles/{{ $article->id }}/edit" class="badge bg-warning"><span data-feather="edit" class="align-text-bottom"></span></a>

                  <!-- Button trigger delete modal -->
                  <button type="button" class="badge bg-danger border-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $article->id }}">
                    <span data-feather="x-circle" class="align-text-bottom"></span>
                  </button>

                  <form action="/dashboard/articles/{{ $article->id }}" method="post" class="d-inline">
                    @csrf
                    @method('delete')
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $article->id }}" tabindex="-1" aria-labelledby="deleteModal{{ $article->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Delete Article : {{ $article->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Are you sure?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete Category</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <nav aria-label="pagination" class="d-flex justify-content-center">
          <ul class="pagination">
            <li class="page-item @if($pagination->current_page == 1)disabled @endif">
              <a class="page-link" href="/dashboard/articles?page={{ $pagination->current_page-1 }}">Previous</a>
            </li>

            <li class="page-item @if ($pagination->current_page == 1) d-none @endif">
              <a class="page-link" href="/dashboard/articles?page={{ $pagination->current_page-1 }}">{{ $pagination->current_page-1 }}</a>
            </li>

            <li class="page-item active">
              <span class="page-link">
                {{ $pagination->current_page }}
              </span>
            </li>

            <li class="page-item @if ($pagination->current_page == $pagination->last_page) d-none @endif">
              <a class="page-link" href="/dashboard/articles?page={{ $pagination->current_page+1 }}">{{ $pagination->current_page+1 }}</a>
            </li>

            <li class="page-item @if($pagination->current_page == $pagination->last_page)disabled @endif">
              <a class="page-link" href="/dashboard/articles?page={{ $pagination->current_page+1 }}">Next</a>
            </li>
          </ul>
        </nav>
      </div>
@endsection
