<?php

namespace Sndpbag\AdminPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GlobalSetting extends Model
{
    protected $table = 'global_settings';
    
    // key এবং value কলাম দুটিকে mass assignable করছি
    protected $fillable = ['key', 'value'];

    // এই টেবিলে created_at এবং updated_at কলামের দরকার নেই
    public $timestamps = false;

    /**
     * সেটিং সেভ করার জন্য একটি হেল্পার মেথড
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        // updateOrCreate ব্যবহার করা হচ্ছে যাতে 'key' ডুপ্লিকেট না হয়
        self::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
    }

    /**
     * সেটিং সেভ হওয়ার পর ক্যাশ (Cache) ক্লিয়ার করুন
     */
    protected static function booted()
    {
        parent::boot();
        
        // যখনই কোনো সেটিং তৈরি বা আপডেট হবে, এই ক্যাশটি ক্লিয়ার হবে
        static::saved(function () {
            Cache::forget('global_settings');
        });
        
        // ডিলিট হলেও ক্যাশ ক্লিয়ার হবে
        static::deleted(function () {
            Cache::forget('global_settings');
        });
    }
}