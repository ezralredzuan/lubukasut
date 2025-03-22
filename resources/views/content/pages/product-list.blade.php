@extends('layouts/contentNavbarLayout')

@section('title', 'Product - Product List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function deleteProduct(productID) {
    if (confirm('Are you sure you want to delete this product?')) {
      fetch("{{ route('products.destroy', '') }}/" + productID, {
      method: "DELETE",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json"
      }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
        alert("Product deleted successfully!");
        location.reload(); // Refresh page after deletion
        } else {
        alert("Error deleting product.");
        }
      })
      .catch(error => console.error('Error:', error));
    }
    }

    $(document).ready(function () {
    $('.edit-product-btn').click(function () {
      let productID = $(this).data('id');
      let name = $(this).data('name');
      let gender = $(this).data('gender');
      let size = $(this).data('size');
      let price = $(this).data('price');
      let description = $(this).data('description');
      let brandID = $(this).data('brand_id');
      let staffID = $(this).data('staff_id');

      // Populate modal fields
      $('#editProductID').val(productID);
      $('#editProductName').val(name);
      $('#editProductGender').val(gender);
      $('#editProductSize').val(size);
      $('#editProductPrice').val(price);
      $('#editProductDescription').val(description);
      $('#editProductBrandID').val(brandID);
      $('#editProductStaffID').val(staffID);

      // Show the modal
      $('#editProductModal').modal('show');
    });
    });

    $('#editProductForm').submit(function (e) {
    e.preventDefault();

    let productID = $('#editProductID').val();
    let formData = new FormData(this);

    $.ajax({
      type: "POST",
      url: "{{ route('products.update', '') }}/" + productID,
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
      alert("Product updated successfully!");
      location.reload();
      },
      error: function (xhr) {
      console.log(xhr.responseText);
      alert("Failed to update product. Check console for details.");
      }
    });
    });

    $('#addProductForm').submit(function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    let fileInput = $('#productImage')[0];

    if (!fileInput.files.length) {
      alert("Please select an image file.");
      return;
    }

    formData.append('productImage', fileInput.files[0]); // Ensure the file is added

    $.ajax({
      type: "POST",
      url: "{{ route('products.store') }}",
      data: formData,
      processData: false,
      contentType: false,
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
      alert("Product added successfully!");
      location.reload();
      },
      error: function (xhr) {
      console.log(xhr.responseText);
      alert("Failed to add product. Check console for details.");
      }
    });
    });


  </script>
@endsection

@section('content')
  <div class="card mb-8">
    <div class="card-body">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="#">Inventory Management</a></li>
      <li class="breadcrumb-item active">Product List</li>
      </ol>
    </nav>
    </div>
  </div>

  <div class="card">
    <h5 class="card-header">Product List</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" style="width: 220px; margin-left: 15px;"
    data-bs-target="#addProductModal">+ Add Product</button>

    <div class="d-flex justify-content-between align-items-center mt-3 px-4">
    <div>
      <form method="GET" action="{{ route('products.index') }}">
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
      <form class="d-flex" method="GET" action="{{ route('products.index') }}">
      <input type="text" class="form-control me-2" name="search" placeholder="Search by Product Name"
        value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
    </div>
    <br>



    <div class="table-responsive">
    <table class="table">
      <thead>
      <tr>
        <th>Product ID</th>
        <th>Product Image</th>
        <th>Gender</th>
        <th>Name</th>
        <th>Size</th>
        <th>Price</th>
        <th>Description</th>
        <th>Brand</th>
        <th>Staff ID</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($products as $product)
      <tr>
      <td>{{ $product->ProductID }}</td>
      <td>
      <img src="{{ route('products.image', $product->ProductID) }}" width="100" height="100" alt="Product Image">
      </td>
      <td>{{ $product->Gender }}</td>
      <td>{{ $product->Name }}</td>
      <td>{{ $product->Size }}</td>
      <td>RM{{ $product->Price }}</td>
      <td>{{ $product->Description }}</td>
      <td>{{ $product->brand->BrandName }}</td>
      <td>{{ $product->StaffID }}</td>
      <td>{{ $product->created_at }}</td>
      <td>
      <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item edit-product-btn" data-id="{{ $product->ProductID }}"
        data-name="{{ $product->Name }}" data-gender="{{ $product->Gender }}" data-size="{{ $product->Size }}"
        data-price="{{ $product->Price }}" data-description="{{ $product->Description }}"
        data-brand_id="{{ $product->BrandID }}" data-staff_id="{{ $product->StaffID }}">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <form onsubmit="event.preventDefault(); deleteProduct({{ $product->ProductID }});">
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
    {{ $products->appends(['per_page' => request('per_page'), 'search' => request('search')])->links('vendor.pagination.custom') }}
    </div>
  </div>

  <!-- Add Product Modal -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="addProductForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="productID" name="productID" value="{{ random_int(100, 999) }}">

        <div class="mb-3">
        <label for="productName" class="form-label">Name</label>
        <input type="text" class="form-control" id="productName" name="name" required>
        </div>

        <div class="mb-3">
        <label for="brandID" class="form-label">Brand</label>
        <select class="form-control" id="brandID" name="brandID" required>
          @foreach ($brands as $brand)
        <option value="{{ $brand->BrandID }}">{{ $brand->BrandName }}</option>
      @endforeach
        </select>
        </div>

        <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <input type="text" class="form-control" id="gender" name="gender" required>
        </div>

        <div class="mb-3">
        <label class="form-label">Size</label>
        <div class="d-flex flex-wrap">
          @foreach ([3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12] as $size)
        <input type="radio" class="btn-check" id="size{{ $size }}" name="size" value="{{ $size }}"
        autocomplete="off" required>
        <label class="btn btn-outline-primary m-1" for="size{{ $size }}">{{ $size }}</label>
      @endforeach
        </div>
        </div>

        <div class="mb-3">
        <label for="productImage" class="form-label">Picture</label>
        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
        </div>

        <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="text" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <input type="hidden" id="staffID" name="staffID" value="1">

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="editProductForm">
        @csrf
        <input type="hidden" id="editProductID" name="id">
        <div class="mb-3">
        <label for="editProductName" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="editProductName" name="name" required>
        </div>
        <div class="mb-3">
        <label for="editProductBrandID" class="form-label">Brand</label>
        <select class="form-control" id="editProductBrandID" name="brandID" required>
          @foreach ($brands as $brand)
        <option value="{{ $brand->BrandID }}">{{ $brand->BrandName }}</option>
      @endforeach
        </select>
        </div>
        <div class="mb-3">
        <label for="editProductGender" class="form-label">Gender</label>
        <input type="text" class="form-control" id="editProductGender" name="gender" required>
        </div>
        <div class="mb-3">
        <label class="form-label">Size</label>
        <div class="d-flex flex-wrap">
          @foreach ([3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12] as $size)
        <input type="radio" class="btn-check" id="editSize{{ $size }}" name="size" value="{{ $size }}"
        autocomplete="off">
        <label class="btn btn-outline-primary m-1" for="editSize{{ $size }}">{{ $size }}</label>
      @endforeach
        </div>
        </div>
        <div class="mb-3">
        <label for="editProductPrice" class="form-label">Price</label>
        <input type="number" class="form-control" id="editProductPrice" name="price" required>
        </div>
        <div class="mb-3">
        <label for="editProductImage" class="form-label">Product Image</label>
        <input type="file" class="form-control" id="editProductImage" accept="image/*" name="productImage">
        </div>
        <div class="mb-3">
        <label for="editProductDescription" class="form-label">Description</label>
        <textarea class="form-control" id="editProductDescription" name="description" required></textarea>
        </div>
        <div class="mb-3" hidden>
        <label for="editProductStaffID" class="form-label">Staff</label>
        <input type="number" class="form-control" id="editProductStaffID" name="staffID" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
      </form>
      </div>
    </div>
    </div>
  </div>

@endsection