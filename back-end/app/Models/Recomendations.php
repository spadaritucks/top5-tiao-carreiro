<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendations extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        "youtube_url",
        "status"
    ];

    public function user(){
        return $this->belongsTo(Users::class, "user_id");
    }

    public function approvals () {
        return $this->hasOne(Approvals::class, "recomendation_id");
    }
}
