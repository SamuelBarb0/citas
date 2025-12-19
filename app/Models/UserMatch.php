<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'matched_at',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'match_id');
    }
}
