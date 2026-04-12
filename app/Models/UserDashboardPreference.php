<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboardPreference extends Model
{
    protected $fillable = ['user_id', 'layout', 'widgets', 'learning_focus', 'theme_color', 'show_quick_links', 'show_my_info'];

    protected $casts = [
        'widgets' => 'array',
        'show_quick_links' => 'boolean',
        'show_my_info' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
