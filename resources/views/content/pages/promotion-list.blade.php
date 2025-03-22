@extends('layouts/contentNavbarLayout')

@section('title', 'Product - Product List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    data-bs-target="#promotionModal">+ Add Product</button>

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
        <th>No</th>
        <th>Promotion Name</th>
        <th>Promotion Code</th>
        <th>Discount(%)</th>
        <th>Valid Until</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($promotions as $index => $promotion)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $promotion->Name }}</td>
      <td>{{ $promotion->PromotionCode }}</td>
      <td>{{ $promotion->DiscountPercentage }}%</td>
      <td>{{ \Carbon\Carbon::parse($promotion->ValidUntilDate)->format('Y-m-d') }}</td>
      <td>
      <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item edit-product-btn">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <form>
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

    <script>
    $(document).ready(function () {
      $('.btn-delete').click(function () {
      var id = $(this).data('id');
      if (confirm('Are you sure you want to delete this promotion?')) {
        $.ajax({
        url: '/promotions/' + id,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (response) {
          location.reload();
        }
        });
      }
      });
    });
    </script>

  @endsection