@extends('layouts/contentNavbarLayout')

@section('title', 'Inquiries - Inquiries List')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function deleteInquiry(inquiryID) {
    if (confirm('Are you sure you want to delete this inquiry?')) {
      fetch("{{ route('inquiries.destroy', '') }}/" + inquiryID, {
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
        alert("Inquiry deleted successfully!");
        location.reload();
        } else {
        alert("Error deleting inquiry.");
        }
      })
      .catch(error => console.error('Error:', error));
    }
    }
  </script>
@endsection

@section('content')
  <div class="card mb-8">
    <div class="card-body">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item active">Inquiries List</li>
      </ol>
    </nav>
    </div>
  </div>

  <div class="card">
    <h5 class="card-header">Inquiries List</h5>
    <div class="table-responsive">
    <table class="table">
      <thead>
      <tr>
        <th>No</th>
        <th>Email</th>
        <th>Inquiries ID</th>
        <th>Inquiries Title</th>
        <th>Description</th>
        <th>Date Created</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($inquiries as $index => $inquiry)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $inquiry->Email }}</td>
      <td>{{ $inquiry->InquiriesID }}</td>
      <td>{{ $inquiry->InquiriesTitle }}</td>
      <td>{{ $inquiry->Description }}</td>
      <td>{{ $inquiry->DateCreated }}</td>
      <td>
      <form onsubmit="event.preventDefault(); deleteInquiry({{ $inquiry->InquiriesID }});">
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
      </form>
      </td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
    {{ $inquiries->links('vendor.pagination.custom') }}
    </div>
  </div>
@endsection