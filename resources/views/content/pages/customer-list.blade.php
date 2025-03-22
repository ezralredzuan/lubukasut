@extends('layouts/contentNavbarLayout')

@section('title', 'Customer List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function () {

    // Open the 'Add Customer' Modal
    $('#addCustomerBtn').click(function () {
      $('#createCustomerForm')[0].reset();
      $('#customerModal').modal('show');
    });

    // Open the 'Edit Customer' Modal
    $(document).on('click', '.editCustomer', function () {
      let id = $(this).data('id');
      $.get(`/customers/${id}/edit`, function (data) {
      $('#editCustomerID').val(data.CustomerID);
      $('#editUsername').val(data.Username);
      $('#editPassword').val(data.Password);
      $('#editFullName').val(data.FullName);
      $('#editNoPhone').val(data.NoPhone);
      $('#editEmail').val(data.Email);
      $('#editAddress').val(data.Address);
      $('#editCity').val(data.City);
      $('#editState').val(data.State);
      $('#editPostalCode').val(data.PostalCode);
      $('#editCustomerModal').modal('show');
      });
    });

    // Create Customer
    $('#createCustomerForm').submit(function (e) {
      e.preventDefault();
      $.ajax({
      url: '/customers',
      type: 'POST',
      data: $(this).serialize(),
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        alert("Member added successfully!");
        location.reload();
      },
      error: function (xhr) {
        console.error('Error:', xhr.responseText);
        if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let errorMessages = "";
        $.each(errors, function (key, value) {
          errorMessages += value[0] + "\n";
        });
        alert("Validation Failed:\n" + errorMessages);
        } else {
        alert("An error occurred. Please check the console.");
        }
      }
      });
    });



    // Update Customer
    $('#editCustomerForm').submit(function (e) {
      e.preventDefault();
      let id = $('#editCustomerID').val();
      $.ajax({
      url: `/customers/${id}`,
      type: 'PUT',
      data: $(this).serialize(),
      success: function () {
        alert("Member updated successfully!");
        $('#editCustomerModal').modal('hide');
        location.reload();
      },
      error: function (xhr) {
        alert("Error: " + xhr.responseJSON.message);
      }
      });
    });


    //Delete Button
    $(document).on("click", ".deleteCustomerBtn", function () {
      let CustomerID = $(this).data("id");

      if (confirm("Are you sure you want to delete this member?")) {
      $.ajax({
        url: "/customers/" + CustomerID,
        type: "DELETE",
        data: {
        _token: $('meta[name="csrf-token"]').attr("content") // CSRF token
        },
        success: function (response) {
        alert("The Member Has Been Successfully Deleted");
        location.reload();
        },
        error: function (xhr) {
        console.log(xhr.responseText); // Debugging errors
        },
      });
      }
    });



    });
  </script>
@endsection

@section('content')

  <div class="card mb-7" style="height: 60px; width: 1390px;">
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
  <div class="card mb-8">
    <div class="card-body">
    <h5 class="card-header">Customer List</h5>
    <button type="button" class="btn btn-primary" id="addCustomerBtn">+ Add Customer</button>

    <div class="d-flex justify-content-between align-items-center mt-3 px-4">
      <div>
      <form method="GET" action="{{ route('customers.index') }}">
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
      <form method="GET" action="{{ route('customers.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Customer Name"
        value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
      </div>
    </div><br>

    <div class="table-responsive text-nowrap">
      <table class="table">
      <thead class="table-light">
        <tr>
        <th>Customer ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>No Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Postal Code</th>
        <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($customers as $customer)
      <tr>
      <td>{{ $customer->CustomerID }}</td>
      <td>{{ $customer->Username }}</td>
      <td>{{ $customer->FullName }}</td>
      <td>{{ $customer->NoPhone }}</td>
      <td>{{ $customer->Email }}</td>
      <td>{{ $customer->Address }}</td>
      <td>{{ $customer->City }}</td>
      <td>{{ $customer->State }}</td>
      <td>{{ $customer->PostalCode }}</td>
      <td>
        <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item editCustomer" data-id="{{ $customer->CustomerID }}">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <button type="button" class="dropdown-item deleteCustomerBtn" data-id="{{ $customer->CustomerID }}">
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
    </div>
  </div>

  <!-- Create Customer Modal -->
  <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="customerModalLabel">Add New Customer</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close" hidden>
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <div class="modal-body">
      <form id="createCustomerForm" action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-group">
        <label>Username</label>
        <input type="text" id="Username" class="form-control" name="Username" required>
        </div>
        <div class="form-group">
        <label>Password</label>
        <input type="password" id="Password" class="form-control" name="Password" required>
        </div>
        <div class="form-group">
        <label>Full Name</label>
        <input type="text" id="FullName" class="form-control" name="FullName" required>
        </div>
        <div class="form-group">
        <label>No Phone</label>
        <input type="text" id="NoPhone" class="form-control" name="NoPhone" required>
        </div>
        <div class="form-group">
        <label>Email</label>
        <input type="email" id="Email" class="form-control" name="Email" required>
        </div>
        <div class="form-group">
        <label>Address</label>
        <textarea class="form-control" id="Address" name="Address" required></textarea>
        </div>
        <div class="form-group">
        <label>City</label>
        <input type="text" id="City" class="form-control" name="City" required>
        </div>
        <div class="form-group">
        <label>State</label>
        <input type="text" id="State" class="form-control" name="State" required>
        </div>
        <div class="form-group">
        <label>Postal Code</label>
        <input type="text" id="PostalCode" class="form-control" name="PostalCode" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
      </div>
    </div>
    </div>
  </div>

  <!-- Edit Customer Modal -->

  <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <div class="modal-body">
      <form id="editCustomerForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="CustomerID" id="editCustomerID">
        <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="Username" id="editUsername" required>
        </div>
        <div class="form-group">
        <label>Full Name</label>
        <input type="text" class="form-control" name="FullName" id="editFullName" required>
        </div>
        <div class="form-group">
        <label>No Phone</label>
        <input type="text" class="form-control" name="NoPhone" id="editNoPhone" required>
        </div>
        <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="Email" id="editEmail" required>
        </div>
        <div class="form-group">
        <label>Address</label>
        <textarea class="form-control" name="Address" id="editAddress" required></textarea>
        </div>
        <div class="form-group">
        <label>City</label>
        <input type="text" class="form-control" name="City" id="editCity" required>
        </div>
        <div class="form-group">
        <label>State</label>
        <input type="text" class="form-control" name="State" id="editState" required>
        </div>
        <div class="form-group">
        <label>Postal Code</label>
        <input type="text" class="form-control" name="PostalCode" id="editPostalCode" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
      </div>
    </div>
    </div>
  </div>
@endsection