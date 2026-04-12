<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Instructor;
use App\Models\PreRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Only allow student pre-registration
        $data = $request->validate([
            'username' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9._%+-]+$/'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->symbols()],
            'applicant_category' => ['required', 'in:grade7,grade11,grade12,transferee,returnee'],
            'preferred_program' => ['nullable', 'in:ABM,GAS,STEM,TVL-Automotive,TVL-ICT,TVL-Cookery,TVL-HomeEc,TVL-IndustrialArts,TVL-AgriFishery'],
            'transferee_grade' => ['nullable', 'in:7,8,9,10,11,12'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'has_no_middle_name' => ['nullable', 'boolean'],
            'extension_name' => ['nullable', 'string', 'max:50'],
            'sex' => ['required', 'in:Male,Female,Other'],
            'date_of_birth' => ['required', 'string'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'in:Single,Married,Divorced,Widowed'],
            'telephone_number' => ['nullable', 'string', 'max:20'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'permanent_address' => ['required', 'string', 'max:500'],
            'current_address' => ['required', 'string', 'max:500'],
            'citizenship' => ['required', 'in:Filipino,Dual,Other'],
            'citizenship_other' => ['nullable', 'string', 'max:100'],
            'elementary_graduation_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'junior_high_graduation_year' => ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'high_school_graduation_year' => ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_address' => ['required', 'string', 'max:500'],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],
            'emergency_contact_email' => ['required', 'email', 'max:255'],
            // Family members checkboxes
            'family_members_indicator' => ['nullable', 'array'],
            'family_members_indicator.*' => ['in:father,mother,spouse'],
            // Father
            'father_last_name' => ['nullable', 'string', 'max:255'],
            'father_first_name' => ['nullable', 'string', 'max:255'],
            'father_middle_name' => ['nullable', 'string', 'max:255'],
            'father_telephone' => ['nullable', 'string', 'max:20'],
            'father_mobile' => ['nullable', 'string', 'max:20'],
            'father_occupation' => ['nullable', 'string', 'max:100'],
            'father_deceased' => ['nullable', 'boolean'],
            // Mother
            'mother_last_name' => ['nullable', 'string', 'max:255'],
            'mother_first_name' => ['nullable', 'string', 'max:255'],
            'mother_middle_name' => ['nullable', 'string', 'max:255'],
            'mother_telephone' => ['nullable', 'string', 'max:20'],
            'mother_mobile' => ['nullable', 'string', 'max:20'],
            'mother_occupation' => ['nullable', 'string', 'max:100'],
            'mother_deceased' => ['nullable', 'boolean'],
            // Spouse
            'spouse_last_name' => ['nullable', 'string', 'max:255'],
            'spouse_first_name' => ['nullable', 'string', 'max:255'],
            'spouse_middle_name' => ['nullable', 'string', 'max:255'],
            'spouse_telephone' => ['nullable', 'string', 'max:20'],
            'spouse_mobile' => ['nullable', 'string', 'max:20'],
            'spouse_occupation' => ['nullable', 'string', 'max:100'],
        ], [
            'username.regex' => 'Username may only contain letters, numbers, and . _ % + - characters.',
            'username.max' => 'Username may not be greater than 64 characters.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.mixed' => 'The password must contain at least one uppercase and one lowercase letter.',
            'password.symbols' => 'The password must contain at least one special character.',
        ]);

        $username = trim($data['username']);
        $data['email'] = strtolower($username) . '@csp.edu';

        if (PreRegistration::where('email', $data['email'])->exists() || User::where('email', $data['email'])->exists()) {
            return back()->withErrors(['username' => 'That username is already taken. Please choose another one.'])->withInput();
        }

        // Additional conditional validations
        $errors = [];
        if(($data['applicant_category'] ?? '') === 'grade11') {
            if (empty($data['high_school_graduation_year'])) $errors[] = 'High School - Year Graduated is required for New Grade 11 Student.';
        }
        if(in_array($data['applicant_category'] ?? '', ['grade11','grade12'])) {
            if (empty($data['preferred_program'])) $errors[] = 'Preferred Program is required for Grade 11 and Grade 12 applicants.';
        }
        if(in_array($data['applicant_category'] ?? '', ['transferee','returnee'])) {
            if (empty($data['transferee_grade'])) $errors[] = 'Please select the grade level for transferee/returnee applicants.';
        }
        if(!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Build family information JSON
        $familyInfo = [];
        if(in_array('father', $data['family_members_indicator'] ?? [])) {
            $familyInfo['father'] = [
                'last_name' => $data['father_last_name'] ?? null,
                'first_name' => $data['father_first_name'] ?? null,
                'middle_name' => $data['father_middle_name'] ?? null,
                'telephone' => $data['father_telephone'] ?? null,
                'mobile' => $data['father_mobile'] ?? null,
                'occupation' => $data['father_occupation'] ?? null,
                'deceased' => $data['father_deceased'] ?? false,
            ];
        }
        if(in_array('mother', $data['family_members_indicator'] ?? [])) {
            $familyInfo['mother'] = [
                'last_name' => $data['mother_last_name'] ?? null,
                'first_name' => $data['mother_first_name'] ?? null,
                'middle_name' => $data['mother_middle_name'] ?? null,
                'telephone' => $data['mother_telephone'] ?? null,
                'mobile' => $data['mother_mobile'] ?? null,
                'occupation' => $data['mother_occupation'] ?? null,
                'deceased' => $data['mother_deceased'] ?? false,
            ];
        }
        if(in_array('spouse', $data['family_members_indicator'] ?? [])) {
            $familyInfo['spouse'] = [
                'last_name' => $data['spouse_last_name'] ?? null,
                'first_name' => $data['spouse_first_name'] ?? null,
                'middle_name' => $data['spouse_middle_name'] ?? null,
                'telephone' => $data['spouse_telephone'] ?? null,
                'mobile' => $data['spouse_mobile'] ?? null,
                'occupation' => $data['spouse_occupation'] ?? null,
            ];
        }

        // Determine year level based on applicant category and transferee/returnee grade.
        $yearLevel = 0;
        if ($data['applicant_category'] === 'grade7') {
            $yearLevel = 7;
        } elseif ($data['applicant_category'] === 'grade11') {
            $yearLevel = 11;
        } elseif ($data['applicant_category'] === 'grade12') {
            $yearLevel = 12;
        } elseif (in_array($data['applicant_category'], ['transferee', 'returnee'])) {
            $yearLevel = intval($data['transferee_grade'] ?? 0);
        }

        // Program display: junior high grades do not have a senior high program.
        if (in_array($yearLevel, [7, 8, 9, 10], true)) {
            $program = 'N/A';
        } elseif (in_array($yearLevel, [11, 12], true)) {
            $program = $data['preferred_program'] ?? 'N/A';
        } else {
            $program = 'N/A';
        }

        // Build full name from first/middle/last
        $fullNameParts = array_filter([trim($data['first_name'] ?? ''), trim($data['middle_name'] ?? ''), trim($data['last_name'] ?? '')]);
        $fullName = implode(' ', $fullNameParts);

        // Normalize date_of_birth to SQL YYYY-MM-DD
        $dob = null;
        if (!empty($data['date_of_birth'])) {
            $inputDob = trim($data['date_of_birth']);
            // Try ISO first (Y-m-d) then US style (m-d-Y)
            try {
                $dob = Carbon::createFromFormat('Y-m-d', $inputDob)->toDateString();
            } catch (\Exception $e) {
                try {
                    $dob = Carbon::createFromFormat('m-d-Y', $inputDob)->toDateString();
                } catch (\Exception $e2) {
                    $dob = null;
                }
            }
        }

        if (empty($dob)) {
            return back()->withErrors(['date_of_birth' => 'The date of birth field must match the format MM-DD-YYYY.'])->withInput();
        }

        // Create pre-registration record
        PreRegistration::create([
            'full_name' => $fullName,
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'program' => $program,
            'year_level' => $yearLevel,
            'status' => 'pending',
            'applicant_category' => $data['applicant_category'],
            'preferred_program' => $data['preferred_program'] ?? null,
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'has_no_middle_name' => $data['has_no_middle_name'] ?? false,
            'extension_name' => $data['extension_name'] ?? null,
            'sex' => $data['sex'],
            'date_of_birth' => $dob,
            'place_of_birth' => $data['place_of_birth'],
            'civil_status' => $data['civil_status'],
            'telephone_number' => $data['telephone_number'] ?? null,
            'mobile_number' => $data['mobile_number'],
            'permanent_address' => $data['permanent_address'],
            'current_address' => $data['current_address'],
            'citizenship' => $data['citizenship'],
            'citizenship_other' => $data['citizenship_other'] ?? null,
            'family_members_indicator' => implode(',', $data['family_members_indicator'] ?? []),
            'family_information' => json_encode($familyInfo),
            'elementary_graduation_year' => $data['elementary_graduation_year'],
            'junior_high_graduation_year' => $data['junior_high_graduation_year'] ?? null,
            'high_school_graduation_year' => $data['high_school_graduation_year'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_address' => $data['emergency_contact_address'],
            'emergency_contact_number' => $data['emergency_contact_number'],
            'emergency_contact_relationship' => $data['emergency_contact_relationship'],
            'emergency_contact_email' => $data['emergency_contact_email'],
            'transferee_grade' => $data['transferee_grade'] ?? null,
        ]);

        return view('auth.pre-registration-submitted');
    }
}
