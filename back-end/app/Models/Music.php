<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $fillable = [
        "recomendation_id",
        "title",
        "thumb",
        "visualizations"
    ];

    public function recomendations () {
        return $this->belongsTo(Recomendations::class, "recomendation_id");
    }
}
