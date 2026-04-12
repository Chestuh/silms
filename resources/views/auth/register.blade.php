@extends('layouts.app')

@section('title', 'Pre-Registration')

@section('content')
<div class="container-fluid min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header border-0 py-4" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                        <div class="text-center">
                            <h2 class="mb-2 text-primary fw-bold"><i class="bi bi-clipboard-check-fill me-2"></i>Student Pre-Registration</h2>
                            <p class="text-muted mb-0">Complete this form to apply for enrollment. All fields marked with <span class="text-danger">*</span> are required.</p>
                        </div>
                    </div>
                    <div class="card-body bg-light p-5">
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="preRegForm" class="needs-validation" novalidate>
                        @csrf

                        <!-- SECTION 1: APPLICANT INFORMATION -->
                        <div class="section-card mb-4 mt-4">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i> Applicant Information</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Applicant Category <span class="text-danger">*</span></label>
                                <select name="applicant_category" class="form-select" id="applicantCategory" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="grade7" {{ old('applicant_category') == 'grade7' ? 'selected' : '' }}>New Grade 7 Student</option>
                                    <option value="grade11" {{ old('applicant_category') == 'grade11' ? 'selected' : '' }}>New Grade 11 Student</option>
                                    <option value="grade12" {{ old('applicant_category') == 'grade12' ? 'selected' : '' }}>Grade 12 Student</option>
                                    <option value="transferee" {{ old('applicant_category') == 'transferee' ? 'selected' : '' }}>Transferee</option>
                                    <option value="returnee" {{ old('applicant_category') == 'returnee' ? 'selected' : '' }}>Returnee</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-none" id="transfereeGradeGroup">
                                <label class="form-label">If Transferee/Returnee: Grade Level <span class="text-danger" id="transfereeAsterisk">*</span></label>
                                <select name="transferee_grade" class="form-select" id="transfereeGradeSelect">
                                    <option value="">-- Select Grade --</option>
                                    <option value="7" {{ old('transferee_grade') == '7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="8" {{ old('transferee_grade') == '8' ? 'selected' : '' }}>Grade 8</option>
                                    <option value="9" {{ old('transferee_grade') == '9' ? 'selected' : '' }}>Grade 9</option>
                                    <option value="10" {{ old('transferee_grade') == '10' ? 'selected' : '' }}>Grade 10</option>
                                    <option value="11" {{ old('transferee_grade') == '11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="12" {{ old('transferee_grade') == '12' ? 'selected' : '' }}>Grade 12</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 {{ in_array(old('applicant_category'), ['grade11', 'grade12']) ? '' : 'd-none' }}" id="programGroup">
                                <label class="form-label">Preferred Program <span class="text-danger" id="programAsterisk">*</span></label>
                                <select name="preferred_program" class="form-select" id="preferredProgram">
                                    <option value="">-- Select Program --</option>
                                    <option value="ABM" {{ old('preferred_program') == 'ABM' ? 'selected' : '' }}>ABM (Accountancy, Business, Management)</option>
                                    <option value="GAS" {{ old('preferred_program') == 'GAS' ? 'selected' : '' }}>GAS (General Academic Strand)</option>
                                    <option value="STE" {{ old('preferred_program') == 'STE' ? 'selected' : '' }}>STE (Science, Technology, and Engineering)</option>
                                    <option value="TVL-IndustrialArts" {{ old('preferred_program') == 'TVL-IndustrialArts' ? 'selected' : '' }}>TVL - Industrial Arts</option>
                                    <option value="TVL-ICT" {{ old('preferred_program') == 'TVL-ICT' ? 'selected' : '' }}>TVL - Information and Communication Technology</option>                                   
                                    <option value="TVL-HomeEc" {{ old('preferred_program') == 'TVL-HomeEc' ? 'selected' : '' }}>TVL - Home Economics</option>
                                </select>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PERSONAL INFORMATION -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-card-text me-2"></i> Personal Information</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="has_no_middle_name" class="form-check-input" id="noMiddleName" {{ old('has_no_middle_name') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="noMiddleName">No Middle Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Extension Name</label>
                                <select name="extension_name" class="form-select">
                                    <option value="">-- None --</option>
                                    <option value="SR" {{ old('extension_name') == 'SR' ? 'selected' : '' }}>SR (Senior)</option>
                                    <option value="JR" {{ old('extension_name') == 'JR' ? 'selected' : '' }}>JR (Junior)</option>
                                    <option value="I" {{ old('extension_name') == 'I' ? 'selected' : '' }}>I</option>
                                    <option value="II" {{ old('extension_name') == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('extension_name') == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('extension_name') == 'IV' ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sex <span class="text-danger">*</span></label>
                                <select name="sex" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="text" name="date_of_birth" class="form-control" placeholder="MM-DD-YYYY" value="{{ old('date_of_birth') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                <select name="civil_status" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Citizenship <span class="text-danger">*</span></label>
                                <select name="citizenship" class="form-select" id="citizenship" required>
                                    <option value="">-- Select --</option>
                                    <option value="Filipino" {{ old('citizenship') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual" {{ old('citizenship') == 'Dual' ? 'selected' : '' }}>Dual</option>
                                    <option value="Other" {{ old('citizenship') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3 {{ old('citizenship') == 'Other' ? '' : 'd-none' }}" id="citizenshipOtherGroup">
                                <label class="form-label">Please Specify <span class="text-danger">*</span></label>
                                <input type="text" name="citizenship_other" class="form-control" value="{{ old('citizenship_other') }}">
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 3: CONTACT INFORMATION -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-telephone-fill me-2"></i> Contact Information</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telephone Number</label>
                                <input type="text" name="telephone_number" class="form-control" placeholder="(02) 1234-5678 or N/A" value="{{ old('telephone_number') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" name="mobile_number" class="form-control" placeholder="09XX-XXX-XXXX" value="{{ old('mobile_number') }}" required>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 4: ADDRESS INFORMATION -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-house-fill me-2"></i> Address Information</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Permanent Home Address <span class="text-danger">*</span></label>
                                <textarea name="permanent_address" id="permanentAddress" class="form-control" rows="2" required>{{ old('permanent_address') }}</textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="sameAsPermanent">
                                    <label class="form-check-label" for="sameAsPermanent">Same as Permanent Home Address</label>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Address <span class="text-danger">*</span></label>
                                <textarea name="current_address" id="currentAddress" class="form-control" rows="2" required>{{ old('current_address') }}</textarea>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 5: FAMILY INFORMATION -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Family Information</h5>
                                <p class="mb-0 small">Select which family members' information to provide:</p>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="family_members_indicator[]" value="father" class="form-check-input" id="familyFather" {{ (is_array(old('family_members_indicator')) && in_array('father', old('family_members_indicator'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="familyFather">Father</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="family_members_indicator[]" value="mother" class="form-check-input" id="familyMother" {{ (is_array(old('family_members_indicator')) && in_array('mother', old('family_members_indicator'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="familyMother">Mother</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="family_members_indicator[]" value="spouse" class="form-check-input" id="familySpouse" {{ (is_array(old('family_members_indicator')) && in_array('spouse', old('family_members_indicator'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="familySpouse">Spouse</label>
                            </div>
                        </div>

                        <!-- FATHER INFO -->
                        <div id="fatherSection" class="family-section border rounded p-3 mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Father's Information</h6>
                                <div>
                                    <small class="text-muted">Deceased or N/A?</small>
                                    <input type="checkbox" name="father_deceased" class="form-check-input ms-2" id="fatherDeceased">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="father_last_name" class="form-control" value="{{ old('father_last_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="father_first_name" class="form-control" value="{{ old('father_first_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="father_middle_name" class="form-control" value="{{ old('father_middle_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Telephone Number</label>
                                        <input type="text" name="father_telephone" class="form-control" placeholder="(02) 1234-5678 or N/A" value="{{ old('father_telephone') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" name="father_mobile" class="form-control" value="{{ old('father_mobile') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation') }}">
                                </div>
                            </div>
                        </div>

                        <!-- MOTHER INFO -->
                        <div id="motherSection" class="family-section border rounded p-3 mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Mother's Information</h6>
                                <div>
                                    <small class="text-muted">Deceased or N/A?</small>
                                    <input type="checkbox" name="mother_deceased" class="form-check-input ms-2" id="motherDeceased">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="mother_last_name" class="form-control" value="{{ old('mother_last_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="mother_first_name" class="form-control" value="{{ old('mother_first_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="mother_middle_name" class="form-control" value="{{ old('mother_middle_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Telephone Number</label>
                                        <input type="text" name="mother_telephone" class="form-control" placeholder="(02) 1234-5678 or N/A" value="{{ old('mother_telephone') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" name="mother_mobile" class="form-control" value="{{ old('mother_mobile') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation') }}">
                                </div>
                            </div>
                        </div>

                        <!-- SPOUSE INFO -->
                        <div id="spouseSection" class="family-section border rounded p-3 mb-3" style="display: none;">
                            <h6 class="mb-3">Spouse's Information</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="spouse_last_name" class="form-control" value="{{ old('spouse_last_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="spouse_first_name" class="form-control" value="{{ old('spouse_first_name') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="spouse_middle_name" class="form-control" value="{{ old('spouse_middle_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Telephone Number</label>
                                        <input type="text" name="spouse_telephone" class="form-control" placeholder="(02) 1234-5678 or N/A" value="{{ old('spouse_telephone') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" name="spouse_mobile" class="form-control" value="{{ old('spouse_mobile') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="spouse_occupation" class="form-control" value="{{ old('spouse_occupation') }}">
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 6: ACADEMIC INFORMATION -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-book-fill me-2"></i> Academic Information</h5>
                                <p class="mb-0 small">Enter graduation years in format: YYYY-YYYY (e.g., 2020-2021)</p>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Elementary - Year Graduated <span class="text-danger">*</span></label>
                                <input type="text" name="elementary_graduation_year" class="form-control" placeholder="YYYY-YYYY (e.g., 2015-2016)" value="{{ old('elementary_graduation_year') }}" required>
                                <small class="text-muted">e.g., 2015-2016</small>
                            </div>
                            <div class="col-md-6 mb-3 {{ in_array(old('applicant_category'), ['grade11', 'grade12']) ? '' : 'd-none' }}" id="juniorHighGroup">
                                <label class="form-label">Junior High - Year Graduated <span class="text-danger" id="juniorAsterisk">*</span></label>
                                <input type="text" name="junior_high_graduation_year" class="form-control" placeholder="YYYY-YYYY (e.g., 2018-2019)" value="{{ old('junior_high_graduation_year') }}">
                                <small class="text-muted">e.g., 2018-2019</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 {{ old('applicant_category') == 'grade11' ? '' : 'd-none' }}" id="highSchoolGroup">
                                <label class="form-label">High School - Year Graduated <span class="text-danger">*</span></label>
                                <input type="text" name="high_school_graduation_year" class="form-control" placeholder="YYYY-YYYY (e.g., 2024-2025)" value="{{ old('high_school_graduation_year') }}">
                                <small class="text-muted">e.g., 2024-2025</small>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 7: ACCOUNT CREDENTIALS -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-lock-fill me-2"></i> Account Credentials</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="cheezyflrs" pattern="^[A-Za-z0-9._%+-]+$" title="Choose your CSP username; this will become username@csp.edu" required>
                                <small class="text-muted">Enter your CSP username; your login email will be username@csp.edu</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Minimum 8 characters, including uppercase, lowercase, and 1 special character</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SECTION 8: EMERGENCY CONTACT -->
                        <div class="section-card mb-4 mt-5">
                            <div class="section-header bg-primary text-white rounded-top p-3">
                                <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Emergency Contact Information</h5>
                            </div>
                            <div class="section-body p-4 bg-white rounded-bottom border">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <input type="text" name="emergency_contact_relationship" class="form-control" placeholder="e.g., Parent, Guardian, Sibling" value="{{ old('emergency_contact_relationship') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Complete Address <span class="text-danger">*</span></label>
                                <textarea name="emergency_contact_address" class="form-control" rows="2" required>{{ old('emergency_contact_address') }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="emergency_contact_email" class="form-control" value="{{ old('emergency_contact_email') }}" required>
                            </div>
                        </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <div class="mt-5 mb-2 text-center">
                            <button type="submit" class="btn btn-lg px-5 py-3 text-white fw-bold" style="background: linear-gradient(45deg, #007bff, #0056b3); border: none; border-radius: 50px; box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);">
                                <i class="bi bi-send-fill me-2"></i> Submit Pre-Registration
                            </button>
                        </div>
                        <p class="text-muted text-center small">By submitting this form, you agree to the school's enrollment policies</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const applicantCategorySelect = document.getElementById('applicantCategory');
    const programGroup = document.getElementById('programGroup');
    const preferredProgramSelect = document.getElementById('preferredProgram');
    const programAsterisk = document.getElementById('programAsterisk');
    const juniorHighGroup = document.getElementById('juniorHighGroup');
    const juniorAsterisk = document.getElementById('juniorAsterisk');
    const citizenshipSelect = document.getElementById('citizenship');
    const citizenshipOtherGroup = document.getElementById('citizenshipOtherGroup');

    const transfereeGradeGroup = document.getElementById('transfereeGradeGroup');
    const transfereeGradeSelect = document.getElementById('transfereeGradeSelect');
    const highSchoolGroup = document.getElementById('highSchoolGroup');
    const highSchoolInput = document.querySelector('input[name="high_school_graduation_year"]');

    // Address same-as controls
    const sameAsPermanent = document.getElementById('sameAsPermanent');
    const permanentAddress = document.getElementById('permanentAddress');
    const currentAddress = document.getElementById('currentAddress');

    // Family member checkboxes
    const familyFatherCheckbox = document.getElementById('familyFather');
    const familyMotherCheckbox = document.getElementById('familyMother');
    const familySpouseCheckbox = document.getElementById('familySpouse');
    const fatherSection = document.getElementById('fatherSection');
    const motherSection = document.getElementById('motherSection');
    const spouseSection = document.getElementById('spouseSection');

    function updateProgramEligibility() {
        const category = applicantCategorySelect.value;
        const transfereeGrade = transfereeGradeSelect.value;
        const isGradeUpper = ['grade11', 'grade12'].includes(category) || (['transferee', 'returnee'].includes(category) && ['11', '12'].includes(transfereeGrade));

        programGroup.classList.toggle('d-none', !isGradeUpper);
        programAsterisk.classList.toggle('d-none', !isGradeUpper);
        juniorHighGroup.classList.toggle('d-none', !isGradeUpper);
        juniorAsterisk.classList.toggle('d-none', !isGradeUpper);

        if (isGradeUpper) {
            preferredProgramSelect.setAttribute('required', 'required');
            const juniorInput = document.querySelector('input[name="junior_high_graduation_year"]');
            if (juniorInput) juniorInput.setAttribute('required', 'required');
        } else {
            preferredProgramSelect.removeAttribute('required');
            const juniorInput = document.querySelector('input[name="junior_high_graduation_year"]');
            if (juniorInput) juniorInput.removeAttribute('required');
        }
    }

    // Handle applicant category change
    applicantCategorySelect.addEventListener('change', function() {
        const val = this.value;

        // Show transferee/returnee grade selection
        const isTransOrReturn = ['transferee', 'returnee'].includes(val);
        transfereeGradeGroup.classList.toggle('d-none', !isTransOrReturn);
        if(isTransOrReturn) transfereeGradeSelect.setAttribute('required','required'); else {
            transfereeGradeSelect.removeAttribute('required');
            transfereeGradeSelect.value = '';
        }

        // High school only for new Grade 11
        const isGrade11 = val === 'grade11';
        highSchoolGroup.classList.toggle('d-none', !isGrade11);
        if(isGrade11) highSchoolInput.setAttribute('required','required'); else highSchoolInput.removeAttribute('required');

        updateProgramEligibility();
    });

    transfereeGradeSelect.addEventListener('change', function() {
        updateProgramEligibility();
    });

    // Handle citizenship change
    citizenshipSelect.addEventListener('change', function() {
        const isOther = this.value === 'Other';
        citizenshipOtherGroup.classList.toggle('d-none', !isOther);
        if(isOther) {
            document.querySelector('input[name="citizenship_other"]').setAttribute('required', 'required');
        } else {
            document.querySelector('input[name="citizenship_other"]').removeAttribute('required');
        }
    });

    // Handle family member checkboxes
    function updateFamilySections() {
        fatherSection.style.display = familyFatherCheckbox.checked ? 'block' : 'none';
        motherSection.style.display = familyMotherCheckbox.checked ? 'block' : 'none';
        spouseSection.style.display = familySpouseCheckbox.checked ? 'block' : 'none';
    }

    familyFatherCheckbox.addEventListener('change', updateFamilySections);
    familyMotherCheckbox.addEventListener('change', updateFamilySections);
    familySpouseCheckbox.addEventListener('change', updateFamilySections);

    // Same-as-permanent-address logic
    sameAsPermanent.addEventListener('change', function() {
        if(this.checked) {
            currentAddress.value = permanentAddress.value;
            currentAddress.readOnly = true;
        } else {
            currentAddress.readOnly = false;
        }
    });
    permanentAddress.addEventListener('input', function() {
        if(sameAsPermanent.checked) currentAddress.value = this.value;
    });

    // Initialize on load
    applicantCategorySelect.dispatchEvent(new Event('change'));
    transfereeGradeSelect.dispatchEvent(new Event('change'));
    citizenshipSelect.dispatchEvent(new Event('change'));
    updateFamilySections();

    // Graduation year validation (YYYY-YYYY)
    document.querySelectorAll('input[name$="_graduation_year"]').forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            if(value && !/^\d{4}-\d{4}$/.test(value)) {
                this.classList.add('is-invalid');
                if(!this.parentElement.querySelector('.invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    feedback.textContent = 'Please use format: YYYY-YYYY (e.g., 2020-2021)';
                    this.parentElement.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentElement.querySelector('.invalid-feedback');
                if(feedback) feedback.remove();
            }
        });
    });

    // Birthdate MM-DD-YYYY validation
    const dobInput = document.querySelector('input[name="date_of_birth"]');
    if(dobInput) {
        dobInput.addEventListener('blur', function() {
            const v = this.value.trim();
            if(v && !/^\d{2}-\d{2}-\d{4}$/.test(v)) {
                this.classList.add('is-invalid');
                if(!this.parentElement.querySelector('.invalid-feedback')) {
                    const fb = document.createElement('div');
                    fb.className = 'invalid-feedback d-block';
                    fb.textContent = 'Please use format: MM-DD-YYYY';
                    this.parentElement.appendChild(fb);
                }
            } else {
                this.classList.remove('is-invalid');
                const fb = this.parentElement.querySelector('.invalid-feedback');
                if(fb) fb.remove();
            }
        });
    }

    // Username validation on submit
    const form = document.getElementById('preRegForm');
    form.addEventListener('submit', function(e) {
        const username = this.querySelector('input[name="username"]').value || '';
        if(!/^[A-Za-z0-9._%+-]+$/.test(username)) {
            e.preventDefault();
            alert('Please enter a valid CSP username using letters, numbers, and . _ % + - only.');
            return false;
        }
        // Validate DOB before submit
        const dob = this.querySelector('input[name="date_of_birth"]').value || '';
        if(dob && !/^\d{2}-\d{2}-\d{4}$/.test(dob)) {
            e.preventDefault();
            alert('Please enter Birthdate in MM-DD-YYYY format');
            return false;
        }
    });
});
</script>
@endpush

<style>
.section-card {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.section-header {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border: none;
}

.section-body {
    border: 1px solid #dee2e6 !important;
    border-top: none !important;
}

.family-section {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff !important;
    border-radius: 0.375rem;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
}

.text-danger {
    color: #dc3545;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4) !important;
}

.card {
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
