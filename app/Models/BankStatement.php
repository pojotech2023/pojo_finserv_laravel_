<?php

namespace App\Models;
use App\Models\Investor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankStatement extends Model
{
    use HasFactory;
use SoftDeletes;
    // Table name
    protected $table = 'bankstatement';

    // Mass assignable columns
    protected $fillable = [
        'date',
        'utrno',
        'check_no',
        'remarks',
        'name',
        'bank_name',
        'amount',
        'cr_dr',
        'investment_ref',
        'loan_ref',
        'excess_ref',
        'expanses_ref',
        'conform_ref_no',
        'is_hidden',
        'deleted_at',
        'delete_status',
        'delete_requested_at',
        'delete_requested_by',

        // In $casts add:
         'delete_requested_at' => 'datetime',
        'is_manual_categorized',
        'manual_category',
        'manual_note',
        'manual_categorized_by',
        'manual_categorized_at',
        'is_openinvestment',
    ];

    // Date casting
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'deleted_at' => 'datetime',
        'is_manual_categorized' => 'boolean',
        'manual_categorized_at' => 'datetime',
    ];

   public static function generateRefNo(string $actionType): string
    {
        $prefixMap = [
            'INVESTMENT'         => 'INV',
            'LOAN'               => 'LOA',
            'LOAN RETURN'        => 'LOA',
            'EXPANSES'           => 'EXP',
            'EXCESS'             => 'UW',
            'EXCESS RETURN'      => 'UW',
            'INVESTMENT RETURN'  => 'INV',
            'OD INTEREST'        => 'OD',
            'OD'                 => 'OD',
            'SELF TRANSACTION'   => 'SELF',
        ];

        $prefix = $prefixMap[$actionType] ?? 'GEN';
        $padLength = ($prefix === 'OD') ? 4 : 3;

        // Find the maximum existing number for this prefix
        $latest = self::where(function ($q) use ($prefix) {
                    $q->where('conform_ref_no', 'like', $prefix . '%')
                      ->orWhere('ref_no', 'like', $prefix . '%');
                })
                ->orderByRaw("CAST(REPLACE(REPLACE(REPLACE(conform_ref_no, '{$prefix}', ''), '{$prefix}', ''), '/R', '') AS UNSIGNED) DESC")
                ->value('conform_ref_no');

        if (!$latest) {
            $latest = self::where('ref_no', 'like', $prefix . '%')
                        ->orderByRaw("CAST(REPLACE(REPLACE(REPLACE(ref_no, '{$prefix}', ''), '{$prefix}', ''), '/R', '') AS UNSIGNED) DESC")
                        ->value('ref_no');
        }

        if ($latest) {
            $numberStr = str_replace([$prefix, '/R'], '', $latest);
            $number = (int) $numberStr;
            $nextNumber = str_pad($number + 1, $padLength, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = str_pad(1, $padLength, '0', STR_PAD_LEFT);
        }

        if (in_array($actionType, ['LOAN RETURN', 'EXCESS RETURN', 'INVESTMENT RETURN'])) {
            return $prefix . $nextNumber . '/R';
        }

        return $prefix . $nextNumber;
    }


    public function investor()
{
    return $this->hasOne(Investor::class, 'statement_id', 'id');
}
public function scopePendingDeletion($query)
{
    return $query->where('delete_status', 'pending');
}
 
}
