@extends('admin.layouts.app')

@section('title', 'Register Mechanic')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/redvers.jpeg') }}" alt="Redvers Logo" width="600" class="mb-2">
                    <h4 class="fw-bold text-primary">Register New Mechanic</h4>
                </div>

                <form method="POST" action="{{ route('admin.mechanics.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_photo" accept="image/*" class="form-control" onchange="previewImage(event, 'profilePreview')">
                            <img id="profilePreview" src="#" alt="Preview" class="mt-2 img-thumbnail" style="max-height: 120px; display: none;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.mechanics.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Create Mechanic</button>
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
