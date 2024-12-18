<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'receiver_id',
        'type',
        'content',
        'link',
        'created_at',
        'hidden',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}