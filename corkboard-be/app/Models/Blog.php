<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'status',
        'created_by',
    ];

    protected $appends = [
        'formatted_created_at',
        'formatted_status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('F j, Y g:i A');
    }

    public function getFormattedStatusAttribute(): string
    {
        return ucwords($this->status);
    }
}