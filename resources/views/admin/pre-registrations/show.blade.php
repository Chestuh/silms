@extends('layouts.app')

@section('title', 'Pre-Registration Details')

        @section('content')
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="d-flex align-items-center mb-4">
            <h2 class="mb-0">Pre-Registration Details</h2>
            <a href="{{ route('admin.pre-registrations.index') }}" class="btn btn-secondary ms-auto">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <!-- STATUS CARD -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Application Status</h6>
                        <p class="lead">
                            @if($preRegistration->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($preRegistration->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Submission Date</h6>
                        <p class="lead">{{ \Carbon\Carbon::parse($preRegistration->created_at)->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Applicant Classification</h6>
                        <p class="lead">
                            @switch($preRegistration->applicant_category)
                                @case('grade7')
                                    New Grade 7 Student
                                    @break
                                @case('grade11')
                                    New Grade 11 Student
                                    @break
                                @case('grade12')
                                    Grade 12 Student
                                    @break
                                @case('transferee')
                                    Transferee
                                    @break
                                @case('returnee')
                                    Returnee
                                    @break
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Year Level</h6>
                        <p class="lead">{{ $preRegistration->year_level_label }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Program</h6>
                        <p class="lead">{{ $preRegistration->display_program }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERSONAL INFORMATION -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-person-fill"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="text-muted">Last Name</h6>
                        <p>{{ $preRegistration->last_name }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">First Name</h6>
                        <p>{{ $preRegistration->first_name }}</p>
                    </div>
                    <div class="col-md-2">
                        <h6 class="text-muted">Middle Name</h6>
                        <p>{{ $preRegistration->has_no_middle_name ? '(No Middle Name)' : ($preRegistration->middle_name ?? 'N/A') }}</p>
                    </div>
                    <div class="col-md-2">
                        <h6 class="text-muted">Extension</h6>
                        <p>{{ $preRegistration->extension_name ?? 'None' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <h6 class="text-muted">Sex</h6>
                        <p>{{ $preRegistration->sex }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Date of Birth</h6>
                        <p>{{ $preRegistration->date_of_birth ? \Carbon\Carbon::parse($preRegistration->date_of_birth)->format('m-d-Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Place of Birth</h6>
                        <p>{{ $preRegistration->place_of_birth }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Civil Status</h6>
                        <p>{{ $preRegistration->civil_status }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTACT AND ADDRESS INFORMATION -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Contact and Address Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Telephone Number</h6>
                        <p>{{ $preRegistration->telephone_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Mobile Number</h6>
                        <p>{{ $preRegistration->mobile_number }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Email Address</h6>
                        <p><a href="mailto:{{ $preRegistration->email }}">{{ $preRegistration->email }}</a></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Permanent Home Address</h6>
                        <p>{{ $preRegistration->permanent_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Current Address</h6>
                        <p>{{ $preRegistration->current_address }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Citizenship</h6>
                        <p>{{ $preRegistration->citizenship }}{{ $preRegistration->citizenship === 'Other' ? ' - ' . $preRegistration->citizenship_other : '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACADEMIC INFORMATION -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-book-fill"></i> Academic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Elementary Graduation</h6>
                        <p>{{ $preRegistration->elementary_graduation_year }}</p>
                    </div>
                    @if($preRegistration->junior_high_graduation_year)
                        <div class="col-md-4">
                            <h6 class="text-muted">Junior High Graduation</h6>
                            <p>{{ $preRegistration->junior_high_graduation_year }}</p>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <h6 class="text-muted">High School Graduation</h6>
                        <p>{{ $preRegistration->high_school_graduation_year }}</p>
                    </div>
                </div>
                @if($preRegistration->applicant_category === 'grade11' || $preRegistration->applicant_category === 'grade12')
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6 class="text-muted">Preferred Program</h6>
                            <p>{{ $preRegistration->preferred_program ?? 'Not specified' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- FAMILY INFORMATION -->
        @if($preRegistration->family_information)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-people-fill"></i> Family Information</h5>
                </div>
                <div class="card-body">
                    @php $familyInfo = json_decode($preRegistration->family_information, true) ?? []; @endphp
                    
                    @if(isset($familyInfo['father']))
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2"><strong>Father</strong>
                                @if($familyInfo['father']['deceased'])
                                    <span class="badge bg-secondary ms-2">Deceased</span>
                                @endif
                            </h6>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Name</small>
                                    <p>{{ $familyInfo['father']['first_name'] }} {{ $familyInfo['father']['middle_name'] }} {{ $familyInfo['father']['last_name'] }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Telephone</small>
                                    <p>{{ $familyInfo['father']['telephone'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Mobile</small>
                                    <p>{{ $familyInfo['father']['mobile'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Occupation</small>
                                    <p>{{ $familyInfo['father']['occupation'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($familyInfo['mother']))
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2"><strong>Mother</strong>
                                @if($familyInfo['mother']['deceased'])
                                    <span class="badge bg-secondary ms-2">Deceased</span>
                                @endif
                            </h6>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Name</small>
                                    <p>{{ $familyInfo['mother']['first_name'] }} {{ $familyInfo['mother']['middle_name'] }} {{ $familyInfo['mother']['last_name'] }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Telephone</small>
                                    <p>{{ $familyInfo['mother']['telephone'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Mobile</small>
                                    <p>{{ $familyInfo['mother']['mobile'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Occupation</small>
                                    <p>{{ $familyInfo['mother']['occupation'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($familyInfo['spouse']))
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2"><strong>Spouse</strong></h6>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Name</small>
                                    <p>{{ $familyInfo['spouse']['first_name'] }} {{ $familyInfo['spouse']['middle_name'] }} {{ $familyInfo['spouse']['last_name'] }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Telephone</small>
                                    <p>{{ $familyInfo['spouse']['telephone'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Mobile</small>
                                    <p>{{ $familyInfo['spouse']['mobile'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Occupation</small>
                                    <p>{{ $familyInfo['spouse']['occupation'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- EMERGENCY CONTACT -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Emergency Contact</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Name</h6>
                        <p>{{ $preRegistration->emergency_contact_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Relationship</h6>
                        <p>{{ $preRegistration->emergency_contact_relationship }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Contact Number</h6>
                        <p>{{ $preRegistration->emergency_contact_number }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Address</h6>
                        <p>{{ $preRegistration->emergency_contact_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email</h6>
                        <p><a href="mailto:{{ $preRegistration->emergency_contact_email }}">{{ $preRegistration->emergency_contact_email }}</a></p>
                    </div>
                </div>
            </div>
        </div>

        @if($preRegistration->status === 'approved')
            <div class="alert alert-success">
                <strong>Approved on:</strong> {{ $preRegistration->approved_at ? \Carbon\Carbon::parse($preRegistration->approved_at)->format('M d, Y H:i') : 'N/A' }}
            </div>
        @elseif($preRegistration->status === 'rejected')
            <div class="alert alert-danger">
                <strong>Rejected on:</strong> {{ $preRegistration->rejected_at ? \Carbon\Carbon::parse($preRegistration->rejected_at)->format('M d, Y H:i') : 'N/A' }}<br>
                <strong>Reason:</strong> {{ $preRegistration->rejection_reason }}
            </div>
        @else
            <!-- ACTION BUTTONS FOR PENDING -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <p class="text-muted mb-3">Choose whether to approve or reject this pre-registration.</p>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.pre-registrations.approve', $preRegistration) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Approve this pre-registration? A student account will be created with an auto-generated School ID.')">
                                <i class="bi bi-check-lg"></i> Approve Pre-Registration
                            </button>
                        </form>
                        <button class="btn btn-danger btn-lg" onclick="rejectModal()">
                            <i class="bi bi-x-lg"></i> Reject Pre-Registration
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Pre-Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pre-registrations.reject', $preRegistration) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this pre-registration is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Pre-Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const rejectModalElement = document.getElementById('rejectModal');
const rejectModalInstance = new bootstrap.Modal(rejectModalElement);

function rejectModal() {
    rejectModalInstance.show();
}
</script>
@endpush

@endsection
