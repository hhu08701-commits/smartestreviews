<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'path',
        'post_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'device_type',
        'browser',
        'os',
        'session_id',
    ];

    /**
     * Get the post that this page view belongs to.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
