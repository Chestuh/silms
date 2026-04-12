<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionRecord extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'record_type', 'date_processed', 'notes'];

    protected function casts(): array
    {
        return ['date_processed' => 'date'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
