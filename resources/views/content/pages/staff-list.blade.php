@extends('layouts/contentNavbarLayout')

@section('title', 'Staff List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
    // CSRF Token setup
    $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    // Create Staff
    $(document).on("submit", "#staffForm", function (e) {
      e.preventDefault(); // Prevent page reload

      var formData = new FormData(this); // Capture form data

      $.ajax({
      url: "{{ route('staff.store') }}", // Ensure this matches your Laravel route
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (response) {
        alert("Staff added successfully!");
        $("#CreateStaffModal").modal("hide"); // Hide modal after success
        location.reload(); // Refresh to show new data
      },
      error: function (xhr) {
        console.log(xhr.responseText); // Debugging error messages
      },
      });
    });

    // Edit Staff
    $(document).on("click", ".editStaffBtn", function () {

      let staffId = $(this).data("id"); // Get the clicked StaffID


      $.ajax({
      url: "/staff/" + staffId + "/edit", // Laravel route for fetching staff data
      type: "GET",
      success: function (response) {
        if (response) {
        $("#editStaffID").val(response.StaffID);
        $("#editStaffName").val(response.StaffName);
        $("#editAddress").val(response.Address);
        $("#editNoPhone").val(response.NoPhone);
        $("#editEmail").val(response.Email);
        $("#editHiredDate").val(response.HiredDate);
        $("#editRole").val(response.Role);
        $("#editUsername").val(response.Username);

        // Check if StaffPic exists
        if (response.StaffPic) {
          $("#editStaffPreview").attr("src", response.StaffPic);
        } else {
          $("#editStaffPreview").attr("src", "/default-user.png");
        }

        // Show the modal
        $("#EditStaffModal").modal("show");
        }
      },
      error: function (xhr) {
        console.error("Error fetching staff data:", xhr.responseText);
      },
      });
    });




    //Store Edit data
    $(document).on("submit", "#editStaffForm", function (e) {
      e.preventDefault();

      let staffID = $("#editStaffID").val();
      let formData = new FormData(this);

      $.ajax({
      url: "/staff/" + staffID,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        "X-HTTP-Method-Override": "PUT",
      },
      success: function (response) {
        alert("Staff updated successfully!");
        $("#EditStaffModal").modal("hide");
        location.reload();
      },
      error: function (xhr) {
        console.log(xhr.responseText);
      },
      });
    });


    //Delete Button
    $(document).on("click", ".deleteStaffBtn", function () {
      let staffID = $(this).data("id");

      if (confirm("Are you sure you want to delete this staff?")) {
      $.ajax({
        url: "/staff/" + staffID,
        type: "DELETE",
        data: {
        _token: $('meta[name="csrf-token"]').attr("content") // CSRF token
        },
        success: function (response) {
        alert(response.message);
        location.reload();
        },
        error: function (xhr) {
        console.log(xhr.responseText); // Debugging errors
        },
      });
      }
    });

    $(document).ready(function () {
      function previewImage(input, targetImage) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        $(targetImage).attr('src', e.target.result).show(); // Set preview and show image
        };
        reader.readAsDataURL(input.files[0]); // Read image file
      }
      }

      // For Create Staff Modal
      $('#createStaffPic').on('change', function () {
      previewImage(this, '#createStaffPreview');
      });

      // For Edit Staff Modal
      $('#editStaffPic').on('change', function () {
      previewImage(this, '#editStaffPreview');
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
      Staff List
    </h5>

    <button type="button" class="btn btn-primary" style="width: 220px;" data-bs-toggle="modal"
      data-bs-target="#CreateStaffModal">
      + Add Staff
    </button>

    <div class="d-flex justify-content-between align-items-center mt-3 px-4">
      <div>
      <form method="GET" action="{{ route('staff.index') }}">
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
      <form method="GET" action="{{ route('staff.index') }}" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Brand Name"
        value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
      </div>
    </div><br>

    <!-- Staff Table -->
    <div class="table-responsive text-nowrap">
      <table class="table">
      <thead class="table-light">
        <tr>
        <th>ID</th>
        <th>Picture</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Hired Date</th>
        <th>Role</th>
        <th>Actions</th>

        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      <tbody class="table-border-bottom-0">
        @foreach ($staffs as $staff)
      <tr>
      <td>{{ $staff->StaffID }}</td>
      <td>
        @if($staff->StaffPic)
      <img src="data:image/jpeg;base64,{{ base64_encode($staff->StaffPic) }}" width="50" height="50"
      class="rounded-circle">
    @else
    <img src="default-user.png" width="50" height="50" class="rounded-circle">
  @endif
      </td>
      <td>{{ $staff->StaffName }}</td>
      <td>{{ $staff->Address }}</td>
      <td>{{ $staff->NoPhone }}</td>
      <td>{{ $staff->Email }}</td>
      <td>{{ $staff->HiredDate }}</td>
      <td>{{ $staff->role->RoleName }}</td>

      <td>
        <div class="dropdown">
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ri-more-2-line"></i>
        </button>
        <div class="dropdown-menu">
        <a href="#" class="dropdown-item editStaffBtn" data-id="{{ $staff->StaffID }}">
        <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <button type="button" class="dropdown-item deleteStaffBtn" data-id="{{ $staff->StaffID }}">
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
      {{ $staffs->appends(['per_page' => request('per_page'), 'search' => request('search')])->links('vendor.pagination.custom') }}
    </div>
    </div>
  </div>

  <!-- Create Staff Modal -->
  <div id="CreateStaffModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title">Add New Staff</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      <form id="staffForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Profile Picture Upload -->
        <div class="text-center">
        <img id="createStaffPreview" src="/default-user.png" alt="Staff Profile Picture" class="rounded-circle"
          style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ddd;">
        </div>

        <!-- Upload Image -->
        <div class="mb-3 text-center">
        <label for="createStaffPic" class="form-label">Upload Picture</label>
        <input type="file" id="createStaffPic" name="StaffPic" class="form-control mt-2" accept="image/*">
        </div>

        <div class="mb-3">
        <label for="StaffName">Staff Name</label>
        <input type="text" class="form-control" name="StaffName" id="StaffName">
        </div>

        <div class="mb-3">
        <label for="Address">Address</label>
        <input type="text" class="form-control" name="Address" id="Address">
        </div>

        <div class="mb-3">
        <label for="NoPhone">Phone Number</label>
        <input type="text" class="form-control" name="NoPhone" id="NoPhone">
        </div>

        <div class="mb-3">
        <label for="Email">Email</label>
        <input type="email" class="form-control" name="Email" id="Email">
        </div>

        <div class="mb-3">
        <label for="HiredDate">Hired Date</label>
        <input type="date" class="form-control" name="HiredDate" id="HiredDate">
        </div>

        <div class="mb-3">
        <label for="Role">Role</label>
        <select class="form-control" name="Role" id="Role">
          <option value="1">Admin</option>
          <option value="2">Staff</option>
        </select>
        </div>

        <div class="mb-3">
        <label for="Username">Username</label>
        <input type="text" class="form-control" name="Username" id="Username">
        </div>

        <div class="mb-3">
        <label for="Password">Password</label>
        <input type="password" class="form-control" name="Password" id="Password">
        </div>

        <button type="submit" class="btn btn-primary">Add Staff</button>
      </form>
      </div>
    </div>
    </div>
  </div>


  <!-- Edit Staff Modal -->
  <div id="EditStaffModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title">Edit Staff</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      <form id="editStaffForm">
        <input type="hidden" name="StaffID" id="editStaffID">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Profile Picture Preview -->
        <div class="text-center">
        <img id="editStaffPreview" src="/default-user.png" alt="Staff Image" class="rounded-circle"
          style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ddd;">
        </div>


        <!-- File Upload for Picture -->
        <div class="mb-3 text-center">
        <label for="editStaffPic" class="form-label">Upload Picture</label>
        <input type="file" id="editStaffPic" name="StaffPic" class="form-control mt-2" accept="image/*">
        </div>
        <div class="text-center">
        <img id="editStaffPreview" src="{{ asset('default.jpg') }}" width="150" class="img-thumbnail"
          style="display: none;">
        </div>

        <div class="mb-3">
        <label for="editStaffName">Staff Name</label>
        <input type="text" class="form-control" name="StaffName" id="editStaffName">
        </div>

        <div class="mb-3">
        <label for="editAddress">Address</label>
        <input type="text" class="form-control" name="Address" id="editAddress">
        </div>

        <div class="mb-3">
        <label for="editNoPhone">Phone Number</label>
        <input type="text" class="form-control" name="NoPhone" id="editNoPhone">
        </div>

        <div class="mb-3">
        <label for="editEmail">Email</label>
        <input type="email" class="form-control" name="Email" id="editEmail">
        </div>

        <div class="mb-3">
        <label for="editHiredDate">Hired Date</label>
        <input type="date" class="form-control" name="HiredDate" id="editHiredDate">
        </div>

        <div class="mb-3">
        <label for="editRole">Role</label>
        <select class="form-control" name="Role" id="editRole">
          <option value="1">Admin</option>
          <option value="2">Staff</option>
        </select>
        </div>

        <div class="mb-3">
        <label for="editUsername">Username</label>
        <input type="text" class="form-control" name="Username" id="editUsername">
        </div>

        <div class="mb-3">
        <label for="editPassword">New Password (Leave blank to keep current)</label>
        <input type="password" class="form-control" name="Password" id="editPassword">
        </div>

        <button type="submit" class="btn btn-primary">Update Staff</button>
      </form>
      </div>
    </div>
    </div>
  </div>

@endsection