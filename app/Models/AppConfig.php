<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppConfig extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get a config value with optional default
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (!Schema::hasTable('app_configs')) {
                return $default;
            }
            $record = static::where('key', $key)->first();
            return $record !== null ? $record->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Set a config value
     */
    public static function set(string $key, $value): void
    {
        try {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AppConfig set error: " . $e->getMessage());
        }
    }

    /**
     * Check if system is currently in LIVE mode
     */
    public static function isLiveMode(): bool
    {
        $mode = strtolower(static::get('api_mode', env('API_MODE', 'test')));
        return $mode === 'live';
    }

    /**
     * Increment an integer hit counter
     */
    public static function incrementHit(string $key): int
    {
        try {
            $current = (int) static::get($key, 0);
            $newVal = $current + 1;
            static::set($key, (string) $newVal);
            return $newVal;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Total Aadhaar API hits in LIVE mode
     */
    public static function getAadhaarHits(): int
    {
        return (int) static::get('aadhaar_api_hits', 0);
    }

    /**
     * Total PAN API hits in LIVE mode
     */
    public static function getPanHits(): int
    {
        return (int) static::get('pan_api_hits', 0);
    }
}
