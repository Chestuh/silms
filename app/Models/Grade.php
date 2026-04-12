<?php

namespace App\Models;

use App\Models\Rubric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['enrollment_id', 'rubric_id', 'midterm_grade', 'final_grade', 'gwa_contribution'];

    protected $casts = [
        'midterm_grade' => 'integer',
        'final_grade' => 'integer',
        'rubric_id' => 'integer',
        'gwa_contribution' => 'decimal:4',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }
}
