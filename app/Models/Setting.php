<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Setting extends Model
{
    public const KEYS = ['phone', 'email', 'address', 'instagram', 'facebook', 'whatsapp', 'telegram', 'hero_video', 'stat_events', 'stat_guests', 'stat_years'];

    public const DEFAULTS = [
        'phone' => '+374 00 000 000',
        'email' => 'info@tokhunts.events',
        'address' => 'Yerevan, Armenia',
        'instagram' => 'https://www.instagram.com/tokhunts.eventsss/',
        'facebook' => 'https://www.facebook.com/profile.php?id=61581873124627',
        'whatsapp' => '',
        'telegram' => '',
        'hero_video' => '',
        // Numbers for the counters on the home page; a counter is hidden while empty.
        'stat_events' => '',
        'stat_guests' => '',
        'stat_years' => '',
    ];

    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public static function values(): array
    {
        try {
            $stored = Cache::rememberForever('settings', fn () => static::query()->pluck('value', 'key')->all());
        } catch (Throwable) {
            $stored = [];
        }

        return array_merge(self::DEFAULTS, array_filter($stored, fn ($v) => $v !== null));
    }

    public static function get(string $key): ?string
    {
        return static::values()[$key] ?? null;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('settings');
    }
}
