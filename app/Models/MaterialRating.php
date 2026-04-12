<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRating extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'material_id', 'rating', 'comment'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }
}
