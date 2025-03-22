@extends('layouts/contentNavbarLayout')

@section('title', 'Product - Brand List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function deleteBrand(brandID) {
    if (confirm('Are you sure you want to delete this brand?')) {
      fetch("{{ route('brands.destroy', '') }}/" + brandID, {
      method: "DELETE",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        "Accept": "application/json"
      }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
        alert("Brand deleted successfully!");
        location.reload(); // Refresh the page
        } else {
        alert("Error deleting brand.");
        }
      })
      .catch(error => console.error('Error:', error));
    }
    }
  </script>
  <script>
    $(document).ready(function () {
    $('#addBrandForm').submit(function (e) {
      e.preventDefault();

      $.ajax({
      url: "{{ route('brands.store') }}", // Ensure this route exists
      type: "POST",
      data: $(this).serialize(),
      success: function (response) {
        alert(response.message); // Show success message
        $('#addBrandModal').modal('hide'); // Close modal
        location.reload(); // Refresh page to show new brand
      },
      error: function (xhr) {
        let errors = xhr.responseJSON.errors;
        if (errors.brandName) {
        $('#brandNameError').text(errors.brandName[0]); // Show validation error
        }
      }
      });
    });
    });

    $(document).ready(function () {
    // Open Edit Modal & Fill Data
    $('.edit-brand-btn').click(function () {
      let brandID = $(this).data('id');
      let brandName = $(this).data('name');

      $('#editBrandID').val(brandID);
      $('#editBrandName').val(brandName);
      $('#editBrandModal').modal('show');
    });

    // Handle Edit Form Submission
    $('#editBrandForm').submit(function (e) {
      e.preventDefault();
      let brandID = $('#editBrandID').val();
      let formData = $(this).serialize();

      $.ajax({
      url: "{{ url('/brands/update') }}/" + brandID, // Laravel route
      type: "PUT",
      data: formData,
      success: function (response) {
        alert(response.message);
        $('#editBrandModal').modal('hide');
        location.reload(); // Refresh page to show updates
      },
      error: function (xhr) {
        let errors = xhr.responseJSON.errors;
        if (errors.brandName) {
        $('#editBrandNameError').text(errors.brandName[0]); // Show validation error
        }
      }
      });
    });
    });
  </script>
@endsection

@section('content')

  <div class="card mb-8" style="height: 60px; width: 1418px; right: 13px;">
    <div class="card-body">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item">
        <a href="javascript:void(0);">Dashboard</a>
      </li>
      <li class="breadcrumb-item">
        <a href="javascript:void(0);">Inventory Management</a>
      </li>
      <li class="breadcrumb-item active">Brand List</li>
      </ol>
    </nav>
    </div>
  </div>

  <div class="row">
    <div class="card mb-6">
    <h5 class="card-header d-flex justify-content-between align-items-center">
      Brand List
    </h5>

    <button type="button" class="btn btn-primary" style="width: 220px;" data-bs-toggle="modal"
      data-bs-target="#addBrandModal">
      + Add Brand
    </button>

    <div class="d-flex justify-content-between align-items-center mt-3 px-4">
      <div>
      <form method="GET" action="{{ route('brands.index') }}">
        <label for="per_page" class="me-2">Show</label>
        <select name="per_page" id="per_page" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
        </select>
        <label class="ms-2">entries</label>
      </form>
      </div>
      <div>
      <form method="GET" action="{{ route('brands.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Brand Name"
        value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
      </div>
    </div>
    <br>

    <div class="table-responsive text-nowrap">
      <table class="table">
      <thead class="table-light">
        <tr>
        <th>ID</th>
        <th>Brand Name</th>
        <th>Created At</th>
        <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      <tbody class="table-border-bottom-0">
        @foreach ($brands as $brand)
      <tr>
      <td>{{ $brand->BrandID }}</td>
      <td>{{ $brand->BrandName }}</td>
      <td>{{ $brand->created_at }}</td>
      <td>
        <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item edit-brand-btn" data-id="{{ $brand->BrandID }}"
        data-name="{{ $brand->BrandName }}">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <form onsubmit="event.preventDefault(); deleteBrand({{ $brand->BrandID }});">
        <button type="submit" class="dropdown-item">
          <i class="ri-delete-bin-6-line me-1"></i> Delete
        </button>
        </form>
        </div>
        </div>
      </td>
      </tr>
    @endforeach
      </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
      {{ $brands->appends(['per_page' => request('per_page'), 'search' => request('search')])->links('vendor.pagination.custom') }}
    </div>
    </div>
  </div>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="addBrandModalLabel">Add New Brand</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="addBrandForm">
        @csrf
        <div class="mb-3">
        <label for="brandName" class="form-label">Brand Name</label>
        <input type="text" class="form-control" id="brandName" name="brandName" required>
        <span class="text-danger" id="brandNameError"></span>
        </div>
        <button type="submit" class="btn btn-primary">Save Brand</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <!-- Bootstrap Modal for Editing Brand -->
  <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="editBrandForm">
        @csrf
        @method('PUT') <!-- Use PUT method for updating -->
        <input type="hidden" id="editBrandID" name="brandID">
        <div class="mb-3">
        <label for="editBrandName" class="form-label">Brand Name</label>
        <input type="text" class="form-control" id="editBrandName" name="brandName" required>
        <span class="text-danger" id="editBrandNameError"></span>
        </div>
        <button type="submit" class="btn btn-primary">Update Brand</button>
      </form>
      </div>
    </div>
    </div>
  </div>
@endsection