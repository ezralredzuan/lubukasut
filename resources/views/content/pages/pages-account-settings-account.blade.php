@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
  @vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
    <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
          class="ri-group-line me-1_5"></i>Account</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i
          class="ri-notification-4-line me-1_5"></i>Notifications</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i
          class="ri-link-m me-1_5"></i>Connections</a></li>
      </ul>
    </div>
    <div class="card mb-6">
      <!-- Account -->
      <div class="card-body">
      <div class="d-flex align-items-start align-items-sm-center gap-6">
        <img src="{{asset('assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
        id="uploadedAvatar" />
        <div class="button-wrapper">
        <label for="upload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
          <span class="d-none d-sm-block">Upload new photo</span>
          <i class="ri-upload-2-line d-block d-sm-none"></i>
          <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
        </label>
        <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4">
          <i class="ri-refresh-line d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Reset</span>
        </button>

        <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
        </div>
      </div>
      </div>

      <!-- /Account -->
    </div>
    <div class="card">
      <h5 class="card-header">Delete Account</h5>
      <div class="card-body">
      <form id="formAccountDeactivation" onsubmit="return false">
        <div class="form-check mb-6 ms-3">
        <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
        <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
        </div>
        <button type="submit" class="btn btn-danger deactivate-account" disabled="disabled">Deactivate
        Account</button>
      </form>
      </div>
    </div>
    </div>
  </div>
@endsection