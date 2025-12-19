<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reason',
        'description',
        'status',
    ];

    // Relación con el usuario que reporta
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relación con el usuario reportado
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
