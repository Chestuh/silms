<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CredentialRequest extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'credential_type', 'status', 'letter_path', 'payment_cleared_at'];

    protected function casts(): array
    {
        return [
            'payment_cleared_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
