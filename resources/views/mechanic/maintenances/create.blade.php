@extends('mechanic.layouts.app')
@section('title', 'New Maintenance Record')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-tools me-2"></i> New Maintenance Record
            </h3>
            <p class="text-muted mb-0">Log maintenance activities for motorcycle units</p>
        </div>
        <a href="{{ route('mechanic.maintenances.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Please fix the following issues:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li class="mb-1">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Maintenance Form --}}
                    <form method="POST" action="{{ route('mechanic.maintenances.store') }}" class="needs-validation" novalidate>
                        @csrf

                        {{-- Motorcycle Selection Section --}}
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="bi bi-motorcycle me-2"></i> Motorcycle Assignment
                                </h6>
                                <div class="mb-0">
                                    <label for="motorcycle_unit_id" class="form-label fw-semibold">
                                        Select Motorcycle Unit <span class="text-danger">*</span>
                                    </label>
                                    <select name="motorcycle_unit_id" id="motorcycle_unit_id" 
                                            class="form-select select2 @error('motorcycle_unit_id') is-invalid @enderror" 
                                            required data-placeholder="Search by rider name, phone, email, or plate number...">
                                        <option value="">-- Choose a motorcycle unit --</option>
                                        @foreach($units as $unit)
                                            @php
                                                $rider = $unit->purchase?->user;
                                                $label = $rider
                                                    ? "{$unit->number_plate} ({$unit->motorcycle->type}) — {$rider->name} | {$rider->phone} | {$rider->email}"
                                                    : "{$unit->number_plate} ({$unit->motorcycle->type}) — Unassigned";
                                            @endphp
                                            <option value="{{ $unit->id }}" {{ old('motorcycle_unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('motorcycle_unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Issue Details Section --}}
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="bi bi-bug me-2"></i> Issue Details
                                </h6>
                                
                                {{-- Reported Issue --}}
                                <div class="mb-3">
                                    <label for="reported_issue" class="form-label fw-semibold">
                                        Reported Issue <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="reported_issue" id="reported_issue"
                                              class="form-control @error('reported_issue') is-invalid @enderror" 
                                              rows="3" required placeholder="Describe the reported problem or issue...">{{ old('reported_issue') }}</textarea>
                                    @error('reported_issue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Diagnosis --}}
                                <div class="mb-0">
                                    <label for="diagnosis" class="form-label fw-semibold">
                                        Diagnosis (Root Cause)
                                    </label>
                                    <textarea name="diagnosis" id="diagnosis"
                                              class="form-control @error('diagnosis') is-invalid @enderror" 
                                              rows="2" placeholder="What caused the issue? (Optional)">{{ old('diagnosis') }}</textarea>
                                    @error('diagnosis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Resolution Section --}}
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="bi bi-wrench me-2"></i> Resolution & Status
                                </h6>
                                
                                <div class="row">
                                    {{-- Action Taken --}}
                                    <div class="col-lg-8 mb-3">
                                        <label for="action_taken" class="form-label fw-semibold">
                                            Action Taken (Solution)
                                        </label>
                                        <textarea name="action_taken" id="action_taken"
                                                  class="form-control @error('action_taken') is-invalid @enderror" 
                                                  rows="2" placeholder="What was done to fix the issue? (Optional)">{{ old('action_taken') }}</textarea>
                                        @error('action_taken')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-lg-4 mb-3">
                                        <label for="status" class="form-label fw-semibold">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" id="status" 
                                                class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                🟡 Pending
                                            </option>
                                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                                🔄 In Progress
                                            </option>
                                            <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>
                                                ✅ Resolved
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Repair Date --}}
                                <div class="mb-0">
                                    <label for="repair_date" class="form-label fw-semibold">
                                        Repair/Service Date
                                    </label>
                                    <input type="date" name="repair_date" id="repair_date"
                                           class="form-control @error('repair_date') is-invalid @enderror" 
                                           value="{{ old('repair_date') }}" max="{{ date('Y-m-d') }}">
                                    @error('repair_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Leave blank if repair hasn't been completed yet
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted small">
                                <i class="bi bi-asterisk text-danger me-1"></i>
                                Required fields are marked with an asterisk
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('mechanic.maintenances.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle me-1"></i> Save Maintenance Record
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar with Help/Tips --}}
        <div class="col-lg-4 col-xl-5">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <h6 class="card-title text-primary fw-bold mb-3">
                        <i class="bi bi-lightbulb me-2"></i> Quick Tips
                    </h6>
                    <div class="small text-muted">
                        <div class="mb-3">
                            <strong class="text-dark">📍 Motorcycle Selection:</strong>
                            <p class="mb-1">Search by typing the rider's name, phone number, email, or plate number.</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong class="text-dark">🔍 Issue Description:</strong>
                            <p class="mb-1">Be specific about the problem. Include symptoms, when it occurs, and any error messages.</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong class="text-dark">⚙️ Status Guidelines:</strong>
                            <ul class="ps-3 mb-1">
                                <li><strong>Pending:</strong> Issue reported, not started</li>
                                <li><strong>In Progress:</strong> Currently being worked on</li>
                                <li><strong>Resolved:</strong> Issue fixed and tested</li>
                            </ul>
                        </div>
                        
                        <div class="mb-0">
                            <strong class="text-dark">📅 Repair Date:</strong>
                            <p class="mb-0">Only set this when the repair is actually completed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.2/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
    .card {
        transition: all 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-1px);
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        // Initialize Select2 for motorcycle selection
        $('#motorcycle_unit_id').select2({
            theme: 'bootstrap-5',
            placeholder: $('#motorcycle_unit_id').data('placeholder'),
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            matcher: function(params, data) {
                // If there are no search terms, return all data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Skip if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                // Search in the option text (case insensitive)
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }

                // Return null if no match
                return null;
            }
        }).focus(); // Auto-focus the select box

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto-resize textareas
        $('textarea').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endpush