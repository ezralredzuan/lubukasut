@extends('layouts.contentNavbarLayout')

@section('title', 'Events Management')

@section('content')

  <div class="card">
    <h5 class="card-header">Events List</h5>
    <div class="table-responsive">
    <table class="table">
      <thead>
      <tr>
        <th>Event ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($events as $event)
      <tr>
      <td>{{ $event->EventID }}</td>
      <td>{{ $event->Title }}</td>
      <td>{{ $event->Status }}</td>
      <td>{{ $event->created_at->format('Y-m-d') }}</td>
      <td>{{ $event->updated_at->format('Y-m-d') }}</td>
      <td>
      <a href="{{ route('events.builder', $event->EventID) }}" class="btn btn-warning btn-sm">Edit Page</a>
      <a href="{{ route('events.view', $event->EventID) }}" class="btn btn-success btn-sm">View Page</a>
      </td>
      </tr>
    @endforeach
      </tbody>
    </table>
    </div>
  </div>

@endsection