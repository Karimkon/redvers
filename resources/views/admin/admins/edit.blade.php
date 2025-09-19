@extends('admin.layouts.app')

@section('title', 'Edit Admin User')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm p-4">
                <h4 class="fw-bold text-primary mb-4">Edit Admin User</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.admins.update', $admin) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" accept="image/*" class="form-control" onchange="previewImage(event, 'profilePreview')">
                            <img id="profilePreview" src="{{ $admin->profile_photo ? asset($admin->profile_photo) : '#' }}" alt="Preview" class="mt-2 img-thumbnail" style="max-height: 120px; {{ $admin->profile_photo ? '' : 'display:none;' }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event, targetId) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById(targetId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
@endsection
