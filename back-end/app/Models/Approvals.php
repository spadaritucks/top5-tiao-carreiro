<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvals extends Model
{
    use HasFactory;

    protected $fillable = [
        'recomendation_id',
        'approval'
    ];

    public function recomendations () {
        return $this->belongsTo(Recomendations::class, "recomendation_id");
    }
}
