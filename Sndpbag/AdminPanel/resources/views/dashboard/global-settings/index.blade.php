@extends('admin-panel::dashboard.layouts.app')

@section('title', 'Global Settings')
@section('page-title', 'Global Settings Management')

@push('styles')
{{-- এই স্টাইলগুলি শুধু এই পেজের জন্য --}}
<style>
    .file-input {
        flex-1; display: block; width: 100%; text-sm text-gray-500 dark:text-gray-300
        file:mr-4 file:py-2 file:px-4
        file:rounded-full file:border-0
        file:text-sm file:font-semibold
        file:bg-indigo-50 file:text-indigo-700
        dark:file:bg-indigo-900 dark:file:text-indigo-300
        hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800 cursor-pointer
    }
    .toggle-checkbox {
        appearance: none;
        width: 60px;
        height: 30px;
        background: #cbd5e1;
        border-radius: 15px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
        flex-shrink: 0;
    }
    .toggle-checkbox::before {
        content: '';
        width: 26px;
        height: 26px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: transform 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-checkbox:checked {
        background: var(--primary);
    }
    .toggle-checkbox:checked::before {
        transform: translateX(30px);
    }
    .setting-card {
        @apply bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6;
    }
    .setting-title {
        @apply text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 border-b dark:border-gray-700 pb-4;
    }
    .input-label {
        @apply block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2;
    }
    .input-field {
        @apply w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-blue-500 focus:outline-none transition;
    }
    .toggle-card {
        @apply flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700;
    }
</style>
@endpush

@section('content')

{{-- সাকসেস এবং এরর মেসেজ দেখানোর সেকশন --}}
@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl dark:bg-gray-800 dark:border-green-700 dark:text-green-300">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl dark:bg-gray-800 dark:border-red-700 dark:text-red-300">
        <p class="font-bold mb-2">অনুগ্রহ করে এই ভুলগুলি ঠিক করুন:</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form action="{{ route('global-settings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="space-y-6">
        
        <div class="setting-card">
            <h3 class="setting-title">🖼️ Site Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="site_name" class="input-label">Site Name <span class="text-red-500">*</span></label>
                    <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="input-field" required>
                </div>
                <div>
                    <label for="site_tagline" class="input-label">Site Tagline</label>
                    <input type="text" id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" class="input-field">
                </div>
                <div class="md:col-span-2">
                    <label for="site_footer_text" class="input-label">Footer Text (যেমন: © 2024 Your Company)</label>
                    <input type="text" id="site_footer_text" name="site_footer_text" value="{{ old('site_footer_text', $settings['site_footer_text'] ?? '') }}" class="input-field">
                </div>
                <div class="md:col-span-2">
                    <label class="input-label">Site Logo (Recommended: 300x80 px)</label>
                    <div class="flex items-center gap-4">
                        @if(!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                            <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo" class="h-16 rounded-lg object-contain bg-gray-100 p-1 border dark:border-gray-700">
                        @endif
                        <input type="file" name="site_logo" class="file-input">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="input-label">Site Favicon (Recommended: 32x32 px)</label>
                    <div class="flex items-center gap-4">
                        @if(!empty($settings['site_favicon']) && Storage::disk('public')->exists($settings['site_favicon']))
                            <img src="{{ Storage::url($settings['site_favicon']) }}" alt="Favicon" class="w-10 h-10 rounded-lg object-contain bg-gray-100 p-1 border dark:border-gray-700">
                        @endif
                        <input type="file" name="site_favicon" class="file-input">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="setting-card">
            <h3 class="setting-title">📧 Contact & 🌐 Social Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_email" class="input-label">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="input-field">
                </div>
                <div>
                    <label for="contact_phone" class="input-label">Contact Phone</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="contact_whatsapp" class="input-label">WhatsApp Number (e.g., +919876543210)</label>
                    <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" class="input-field">
                </div>
                <div>
                    <label for="facebook_url" class="input-label">Facebook URL</label>
                    <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="instagram_url" class="input-label">Instagram URL</label>
                    <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="twitter_url" class="input-label">Twitter URL</label>
                    <input type="url" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="youtube_url" class="input-label">YouTube URL</label>
                    <input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="linkedin_url" class="input-label">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" class="input-field">
                </div>
                <div class="md:col-span-2">
                    <label for="contact_address" class="input-label">Contact Address</label>
                    <textarea id="contact_address" name="contact_address" rows="3" class="input-field">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="setting-card">
            <h3 class="setting-title">⚙️ Site Behavior & Features</h3>
            <div class="space-y-4">
                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Maintenance Mode</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">সাইট সাময়িকভাবে বন্ধ রাখতে এটি চালু করুন। (অ্যাডমিন লগইন করতে পারবে)</p>
                    </div>
                    <input type="checkbox" name="maintenance_mode" class="toggle-checkbox" 
                           {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                </div>
                
                <div>
                    <label for="maintenance_message" class="input-label">Maintenance Message</label>
                    <textarea id="maintenance_message" name="maintenance_message" rows="2" class="input-field">{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                </div>
                
                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Enable User Registration</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">নতুন ইউজার রেজিস্ট্রেশন (`/register`) চালু বা বন্ধ করুন।</p>
                    </div>
                    <input type="checkbox" name="registration_enabled" class="toggle-checkbox" 
                           {{ old('registration_enabled', $settings['registration_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                </div>

                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Require Email Verification</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">নতুন ইউজারদের লগইনের আগে ইমেল ভেরিফাই করতে বাধ্য করুন।</p>
                    </div>
                    <input type="checkbox" name="email_verification_required" class="toggle-checkbox" 
                           {{ old('email_verification_required', $settings['email_verification_required'] ?? '1') == '1' ? 'checked' : '' }}>
                </div>
                
                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Enable Comments</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ব্লগ বা অন্যান্য পেজে কমেন্ট করা চালু বা বন্ধ করুন।</p>
                    </div>
                    <input type="checkbox" name="comments_enabled" class="toggle-checkbox" 
                           {{ old('comments_enabled', $settings['comments_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                </div>

                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Allow File Upload</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ইউজারদের ফাইল আপলোড করার অনুমতি দিন (যেমন ফোরামে)।</p>
                    </div>
                    <input type="checkbox" name="allow_file_upload" class="toggle-checkbox" 
                           {{ old('allow_file_upload', $settings['allow_file_upload'] ?? '1') == '1' ? 'checked' : '' }}>
                </div>
            </div>
        </div>

        <div class="setting-card">
            <h3 class="setting-title">🔧 Additional Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="items_per_page" class="input-label">Items Per Page (Pagination)</label>
                    <input type="number" id="items_per_page" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page'] ?? '10') }}" class="input-field">
                </div>
                <div>
                    <label for="max_upload_size" class="input-label">Max Upload Size (in KB)</label>
                    <input type="number" id="max_upload_size" name="max_upload_size" value="{{ old('max_upload_size', $settings['max_upload_size'] ?? '5120') }}" class="input-field">
                    <p class="text-xs text-gray-500 mt-1">e.g., 5120 = 5MB</p>
                </div>
                <div>
                    <label for="date_format" class="input-label">Date Format</label>
                    <input type="text" id="date_format" name="date_format" value="{{ old('date_format', $settings['date_format'] ?? 'd-m-Y') }}" class="input-field">
                    <p class="text-xs text-gray-500 mt-1">e.g., d-m-Y, Y/m/d, F j, Y</p>
                </div>
                <div>
                    <label for="time_format" class="input-label">Time Format</label>
                    <input type="text" id="time_format" name="time_format" value="{{ old('time_format', $settings['time_format'] ?? 'h:i A') }}" class="input-field">
                     <p class="text-xs text-gray-500 mt-1">e.g., h:i A, H:i</p>
                </div>
                <div>
                    <label for="timezone" class="input-label">Timezone</label>
                    <input type="text" id="timezone" name="timezone" value="{{ old('timezone', $settings['timezone'] ?? 'Asia/Kolkata') }}" class="input-field">
                </div>
                <div>
                    <label for="default_language" class="input-label">Default Language</label>
                    <input type="text" id="default_language" name="default_language" value="{{ old('default_language', $settings['default_language'] ?? 'en') }}" class="input-field">
                </div>
            </div>
        </div>

        <div class="setting-card">
            <h3 class="setting-title">🚀 SEO & Meta Settings</h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="meta_title" class="input-label">Meta Title (Default)</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $settings['meta_title'] ?? '') }}" class="input-field">
                </div>
                <div>
                    <label for="meta_description" class="input-label">Meta Description (Default)</label>
                    <textarea id="meta_description" name="meta_description" rows="3" class="input-field">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label for="meta_keywords" class="input-label">Meta Keywords (কমা দিয়ে আলাদা করুন)</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="google_analytics_id" class="input-label">Google Analytics ID (e.g., G-XXXXXXX)</label>
                    <input type="text" id="google_analytics_id" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" class="input-field">
                </div>
                 <div>
                    <label for="google_site_verification" class="input-label">Google Site Verification Key</label>
                    <input type="text" id="google_site_verification" name="google_site_verification" value="{{ old('google_site_verification', $settings['google_site_verification'] ?? '') }}" class="input-field">
                </div>
            </div>
        </div>

        <div class="setting-card">
            <h3 class="setting-title">🔐 Mail & Payment Gateway (Environment)</h3>
            <div class="p-4 bg-yellow-50 dark:bg-gray-800 border border-yellow-300 dark:border-yellow-700 rounded-lg text-yellow-800 dark:text-yellow-300">
                <strong>গুরুত্বপূর্ণ:</strong> নিরাপত্তার কারণে এই সংবেদনশীল তথ্যগুলি (যেমন পাসওয়ার্ড, এপিআই কী) ডাটাবেসে সেভ করা হয় না। এগুলি সরাসরি আপনার প্রজেক্টের `.env` ফাইলে পরিবর্তন করতে হয়। এই সেকশনটি শুধু দেখানোর জন্য যে সেগুলি চালু আছে কি না।
            </div>
            
            <div class="space-y-4 mt-6">
                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Enable SMTP Mail</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ইমেল পাঠানোর জন্য SMTP চালু করুন। (বাকি তথ্য `.env` ফাইলে দিন)</p>
                    </div>
                    <input type="checkbox" name="smtp_enabled" class="toggle-checkbox" 
                           {{ old('smtp_enabled', $settings['smtp_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                </div>
                <div class="toggle-card">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Enable Payment Gateway</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Razorpay/Stripe পেমেন্ট চালু করুন। (API Key `.env` ফাইলে দিন)</p>
                    </div>
                    <input type="checkbox" name="payment_gateway_enabled" class="toggle-checkbox" 
                           {{ old('payment_gateway_enabled', $settings['payment_gateway_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                     <div>
                        <label for="currency" class="input-label">Currency (e.g., INR, USD)</label>
                        <input type="text" id="currency" name="currency" value="{{ old('currency', $settings['currency'] ?? 'INR') }}" class="input-field">
                    </div>
                     <div>
                        <label for="session_lifetime" class="input-label">Session Lifetime (Minutes)</label>
                        <input type="number" id="session_lifetime" name="session_lifetime" value="{{ old('session_lifetime', $settings['session_lifetime'] ?? '120') }}" class="input-field">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 sticky bottom-6 z-10">
            <button type="submit" class="w-full px-8 py-4 rounded-xl text-white font-semibold hover:shadow-lg transition text-lg" style="background: var(--primary);">
                Save All Settings
            </button>
        </div>

    </div>
</form>

@endsection