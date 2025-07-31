@extends('admin.layouts.app')

@section('title', 'Edit Mechanic - Redvers')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <h4 class="text-center fw-bold text-primary mb-4">Edit Mechanic</h4>

                <form method="POST" action="{{ route('admin.mechanics.update', $mechanic->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $mechanic->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $mechanic->phone) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" value="{{ old('email', $mechanic->email) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_photo" class="form-control" onchange="previewImage(event, 'previewImg')">
                            @if($mechanic->profile_photo)
                                <img src="{{ asset($mechanic->profile_photo) }}" id="previewImg" class="img-thumbnail mt-2" style="max-height: 120px;">
                            @else
                                <img id="previewImg" src="#" class="img-thumbnail mt-2" style="max-height: 120px; display: none;">
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.mechanics.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Mechanic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event, id) {
        const reader = new FileReader();
        reader.onload = function () {
            const img = document.getElementById(id);
            img.src = reader.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
@endsection
