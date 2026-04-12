<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyReminder extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'material_id', 'title', 'remind_at', 'sent'];

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'sent' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }
}
