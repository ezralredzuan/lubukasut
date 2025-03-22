@extends('layouts/contentNavbarLayout')

@section('title', 'Staff List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
    $('#orderTable').DataTable();

    // Save Order
    $('#orderForm').submit(function (e) {
      e.preventDefault();
      let id = $('#orderId').val();
      let url = id ? `/orders/update/${id}` : '/orders/store';

      $.post(url, {
      _token: '{{ csrf_token() }}',
      ReferenceNo: $('#ReferenceNo').val(),
      CustomerID: $('#CustomerID').val(),
      Address: $('#Address').val(),
      }, function (response) {
      alert(response.success);
      location.reload();
      }).fail(function () {
      alert('Error saving order');
      });
    });

    // Edit Order
    $('.edit-btn').click(function () {
      let id = $(this).data('id');
      $.get(`/orders/${id}`, function (order) {
      $('#orderId').val(order.OrderID);
      $('#ReferenceNo').val(order.ReferenceNo);
      $('#CustomerID').val(order.CustomerID);
      $('#Address').val(order.Address);
      $('#orderModal').modal('show');
      });
    });

    // Delete Order
    $('.delete-btn').click(function () {
      let id = $(this).data('id');
      if (confirm('Are you sure?')) {
      $.ajax({
        url: `/orders/${id}`,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function (response) {
        alert(response.success);
        location.reload();
        }
      });
      }
    });

    // Update Status
    $('.update-status').change(function () {
      let id = $(this).data('id');
      let status = $(this).val();
      $.post(`/orders/update/${id}`, { _token: '{{ csrf_token() }}', DeliveryStatus: status });
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
      Order List
    </h5>

    <button type="button" class="btn btn-primary mb-3" style="width: 220px;" data-bs-toggle="modal"
      data-bs-target="#orderModal">+ Add
      Order
    </button>

    <div class="d-flex justify-content-between align-items-center mt-3 px-4">
      <div>
      <form method="GET" action="{{ route('orders.index') }}">
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
      <form method="GET" action="{{ route('orders.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Brand Name"
        value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
      </div>
    </div><br>

    <div class="table-responsive text-nowrap">
      <table class="table">
      <thead class="table-light">
        <tr>
        <th>Order ID</th>
        <th>Reference No</th>
        <th>Customer</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Postal Code</th>
        <th>Delivery Status</th>
        <th>Shipped Date</th>
        <th>Action</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      <tbody class="table-border-bottom-0">
        @foreach($orders as $order)
      <tr>
      <td>{{ $order->OrderID }}</td>
      <td>{{ $order->ReferenceNo }}</td>
      <td>{{ $order->customer->FullName ?? 'N/A' }}</td>
      <td>{{ $order->Address }}</td>
      <td>{{ $order->City }}</td>
      <td>{{ $order->State }}</td>
      <td>{{ $order->PostalCode }}</td>
      <td>
        <select class="form-control update-status" data-id="{{ $order->OrderID }}">
        <option value="1" {{ $order->DeliveryStatus == 'Order Placed' ? 'selected' : '' }}>Order Placed
        </option>
        <option value="2" {{ $order->DeliveryStatus == 'In Process' ? 'selected' : '' }}>In Process
        </option>
        <option value="3" {{ $order->DeliveryStatus == 'Delivered' ? 'selected' : '' }}>Delivered</option>
        <option value="4" {{ $order->DeliveryStatus == 'Complete' ? 'selected' : '' }}>Complete</option>
        </select>
      </td>
      <td>{{ $order->ShippedDate ?? 'Not Shipped' }}</td>
      <td>
        <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item editStaffBtn" data-id="{{ $order->OrderID }}">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <button type="button" class="dropdown-item deleteStaffBtn" data-id="{{ $order->OrderID }}">
        <i class="ri-delete-bin-6-line me-1"></i> Delete
        </button>
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
      {{ $orders->appends(['per_page' => request('per_page'), 'search' => request('search')])->links('vendor.pagination.custom') }}
    </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="orderModal">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Add/Edit Order</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <form id="orderForm">
        @csrf
        <input type="hidden" id="orderId">
        <div class="form-group">
        <label>Reference No</label>
        <input type="text" id="ReferenceNo" class="form-control" required>
        </div>
        <div class="form-group">
        <label>Customer ID</label>
        <input type="text" id="CustomerID" class="form-control" required>
        </div>
        <div class="form-group">
        <label>Address</label>
        <input type="text" id="Address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
      </form>
      </div>
    </div>
    </div>
  </div>


@endsection