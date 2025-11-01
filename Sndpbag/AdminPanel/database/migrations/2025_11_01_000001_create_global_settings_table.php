<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // যেমন: 'site_name', 'site_logo'
            $table->text('value')->nullable(); // যেমন: 'আমার সাইট', 'logos/logo.png'
        });

        // ডিফল্ট সেটিংস ঢুকিয়ে দিচ্ছি
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }

    /**
     * ডিফল্ট সেটিংস ডেটাবেসে যোগ করা
     */
    private function seedDefaultSettings(): void
    {
        $settings = [
            // Site Basic Info
            'site_name' => 'SndpBag Panel',
            'site_tagline' => 'Learn & Grow with Us',
            'site_logo' => null,
            'site_favicon' => null,
            'site_footer_text' => '© 2024 SndpBag Panel. All rights reserved.',
            
            // Contact Information
            'contact_email' => 'info@example.com',
            'contact_phone' => '+91 9876543210',
            'contact_address' => 'Amtala, South 24 Parganas',
            'contact_whatsapp' => '+91 9876543210',
            
            // Social Media Links
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => null,
            'twitter_url' => null,
            'youtube_url' => 'https://youtube.com/@sndpbag4you',
            'linkedin_url' => null,
            
            // Site Features
            'maintenance_mode' => '0',
            'maintenance_message' => 'Site is under maintenance. We will be back shortly!',
            'registration_enabled' => '1',
            'comments_enabled' => '1',
            'email_verification_required' => '1',
            
            // SEO Settings
            'meta_title' => 'SndpBag Panel',
            'meta_description' => 'Learn PHP, Laravel, Tally Prime and more.',
            'meta_keywords' => 'computer, tally, php, web design',
            'google_analytics_id' => null,
            'google_site_verification' => null,
            
            // Additional Settings
            'items_per_page' => '10',
            'date_format' => 'd-m-Y',
            'time_format' => 'h:i A',
            'timezone' => 'Asia/Kolkata',
            'default_language' => 'en',
            'allow_file_upload' => '1',
            'max_upload_size' => '5120', // 5MB in KB
            
            // .env settings (শুধুমাত্র ডিফল্ট ভ্যালু, এগুলি .env ফাইল থেকেই লোড হবে)
            'smtp_enabled' => '0',
            'payment_gateway_enabled' => '0',
            'currency' => 'INR',
            'session_lifetime' => '120',
        ];

        // Eloquent মডেল ব্যবহার না করে সরাসরি DB::table() ব্যবহার করা হচ্ছে
        // কারণ মাইগ্রেশন চলার সময় মডেল লোড নাও হতে পারে
        foreach ($settings as $key => $value) {
            DB::table('global_settings')->insert([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
};