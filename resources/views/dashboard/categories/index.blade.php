@extends('dashboard.layouts.main')

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Article Categories</h1>
    </div>



    @if (session()->has('success'))
      <div class="alert alert-success col-lg-6" role="alert">
        {{ session('success') }}
      </div>
    @endif

    <div class="table-responsive col-lg-6">
      <!-- Button trigger create modal -->
      <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Create new category
      </button>

      <form action="/dashboard/categories" method="post" class="d-inline">
        @csrf
        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <label for="newCategoryName" class="col-form-label">Enter new category name: </label>
                <input type="text" name="name" id="newCategoryName" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create Category</button>
              </div>
            </div>
          </div>
        </div>
      </form>

        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Category Name</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td>
                  <!-- Button trigger edit modal -->
                  <button type="button" class="badge bg-warning border-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                    <span data-feather="edit" class="align-text-bottom"></span>
                  </button>

                  <form action="/dashboard/categories/{{ $category->id }}" method="post" class="d-inline">
                    @csrf
                    @method('put')
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModal{{ $category->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Category : {{ $category->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <label for="updatedCategoryName{{ $category->name }}" class="col-form-label">Enter new category name: </label>
                            <input type="text" name="name" id="updatedCategoryName{{ $category->name }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>

                  <!-- Button trigger delete modal -->
                  <button type="button" class="badge bg-danger border-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                    <span data-feather="x-circle" class="align-text-bottom"></span>
                  </button>

                  <form action="/dashboard/categories/{{ $category->id }}" method="post" class="d-inline">
                    @csrf
                    @method('delete')
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModal{{ $category->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Delete Category : {{ $category->name }}</h5>
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
      </div>
@endsection