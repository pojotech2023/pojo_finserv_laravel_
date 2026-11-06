<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcessFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'excess_id',
        'filename',
        'filepath',
    ];

    public function excess()
    {
        return $this->belongsTo(Excess::class);
    }
}
