<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicHonor extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'honor_type', 'semester', 'school_year'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
