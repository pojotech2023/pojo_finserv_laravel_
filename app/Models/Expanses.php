<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankStatement;
class Expanses extends Model
{
    use HasFactory;
     protected $table = 'account_expanses';

    // Allow mass assignment for these columns
    protected $fillable = [
        'statement_id',
        'expanses_remark',
        'salary',
        'payout',
        'commision',
        'rent',
        'interest',
        'exrtn',
        'document',
    ];

    public function statement()
    {
        return $this->belongsTo(BankStatement::class, 'statement_id');
    }
}
