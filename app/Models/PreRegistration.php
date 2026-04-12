<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password_hash',
        'full_name',
        'program',
        'year_level',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        // New fields
        'applicant_category',
        'preferred_program',
        'last_name',
        'first_name',
        'middle_name',
        'has_no_middle_name',
        'extension_name',
        'sex',
        'date_of_birth',
        'transferee_grade',
        'place_of_birth',
        'civil_status',
        'telephone_number',
        'mobile_number',
        'permanent_address',
        'current_address',
        'citizenship',
        'citizenship_other',
        'family_members_indicator',
        'family_information',
        'elementary_graduation_year',
        'junior_high_graduation_year',
        'high_school_graduation_year',
        'emergency_contact_name',
        'emergency_contact_address',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'emergency_contact_email',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    protected function resolveYearLevel(): int
    {
        if ($this->year_level >= 7 && $this->year_level <= 12) {
            return $this->year_level;
        }

        if ($this->applicant_category === 'grade7') {
            return 7;
        }

        if ($this->applicant_category === 'grade11') {
            return 11;
        }

        if ($this->applicant_category === 'grade12') {
            return 12;
        }

        if (in_array($this->applicant_category, ['transferee', 'returnee'], true)) {
            return intval($this->transferee_grade ?? 0);
        }

        return 0;
    }

    public function getYearLevelLabelAttribute(): string
    {
        $resolved = $this->resolveYearLevel();
        return $resolved ? 'Grade ' . $resolved : 'N/A';
    }

    public function getDisplayProgramAttribute(): string
    {
        $resolved = $this->resolveYearLevel();
        if (in_array($resolved, [7, 8, 9, 10], true)) {
            return 'N/A';
        }

        return $this->preferred_program ?: ($this->program ?: 'N/A');
    }
}
