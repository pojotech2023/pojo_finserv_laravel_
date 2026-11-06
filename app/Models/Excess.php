<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excess extends Model
{
    use HasFactory;
     protected $fillable = [
        'statement_id',
        'user_id',
        'letter_path',
        'ref_no'
    ];

    public function files()
    {
        return $this->hasMany(ExcessFile::class);
    }
}
