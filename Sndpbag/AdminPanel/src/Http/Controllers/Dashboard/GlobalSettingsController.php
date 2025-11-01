<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\GlobalSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class GlobalSettingsController extends Controller
{
    /**
     * সেটিংস পেজটি দেখানোর জন্য
     */
    public function index()
    {
        // ক্যাশ (Cache) থেকে সেটিংস লোড করুন, যদি না থাকে তবে ডাটাবেস থেকে আনুন
        $settings = Cache::rememberForever('global_settings', function () {
            return GlobalSetting::all()->pluck('value', 'key');
        });
        
        return view('admin-panel::dashboard.global-settings.index', compact('settings'));
    }

    /**
     * সেটিংস সেভ করার জন্য
     */
    public function store(Request $request)
    {
        // আপনার তালিকা অনুযায়ী ভ্যালিডেশন
        $validated = $request->validate([
            // Site Info
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_footer_text' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:png,jpg,webp,svg|max:2048', // 2MB
            'site_favicon' => 'nullable|image|mimes:png,ico,webp|max:512', // 512KB

            // Contact
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string|max:20',

            // Social
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',

            // Site Behavior
            'maintenance_message' => 'nullable|string',

            // SEO
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'google_analytics_id' => 'nullable|string',
            'google_site_verification' => 'nullable|string',
            
            // Additional
            'items_per_page' => 'required|integer|min:1',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'timezone' => 'required|string',
            'default_language' => 'required|string',
            'max_upload_size' => 'required|integer|min:1024', // Min 1MB
            'session_lifetime' => 'required|integer|min:10',
            'currency' => 'required|string|max:3',
        ]);
        
        // ১. টেক্সট (Text) ভ্যালুগুলি সেভ করুন
        $textSettings = $request->except([
            '_token', '_method', 'site_logo', 'site_favicon', 
            'maintenance_mode', 'registration_enabled', 'comments_enabled',
            'email_verification_required', 'allow_file_upload',
            'smtp_enabled', 'payment_gateway_enabled'
        ]);
        
        foreach ($textSettings as $key => $value) {
            GlobalSetting::set($key, $value);
        }

        // ২. চেক-বক্স (Checkbox) ভ্যালুগুলি সেভ করুন (আপনার তালিকা অনুযায়ী)
        GlobalSetting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');
        GlobalSetting::set('registration_enabled', $request->has('registration_enabled') ? '1' : '0');
        GlobalSetting::set('comments_enabled', $request->has('comments_enabled') ? '1' : '0');
        GlobalSetting::set('email_verification_required', $request->has('email_verification_required') ? '1' : '0');
        GlobalSetting::set('allow_file_upload', $request->has('allow_file_upload') ? '1' : '0');
        GlobalSetting::set('smtp_enabled', $request->has('smtp_enabled') ? '1' : '0');
        GlobalSetting::set('payment_gateway_enabled', $request->has('payment_gateway_enabled') ? '1' : '0');

        // ৩. লোগো (Logo) ফাইল আপলোড
        if ($request->hasFile('site_logo')) {
            $this->uploadSettingFile($request, 'site_logo', 'settings/logos');
        }
        
        // ৪. ফ্যাভিকন (Favicon) ফাইল আপলোড
        if ($request->hasFile('site_favicon')) {
            $this->uploadSettingFile($request, 'site_favicon', 'settings/logos');
        }

        // ৫. মেইনটেন্যান্স মোড (Maintenance Mode) হ্যান্ডেল করুন
        if ($request->has('maintenance_mode')) {
            // একটি সিক্রেট পাথ যোগ করা হলো যাতে আপনি সাইট দেখতে পারেন
            Artisan::call('down', ['--secret' => 'sndpbag_secret_path']); 
        } else {
            Artisan::call('up');
        }

        // ৬. ক্যাশ (Cache) ক্লিয়ার করুন
        // নোট: Mail এবং Session সেটিংস .env ফাইল পরিবর্তন না করলে কাজ করবে না।
        // কিন্তু টাইমজোন এবং কারেন্সি পরিবর্তন করার জন্য কনফিগ ক্যাশ ক্লিয়ার করা দরকার।
        Artisan::call('config:clear');

        return redirect()->back()->with('success', 'Global Settings Updated Successfully!');
    }

    /**
     * ফাইল আপলোড করার একটি প্রাইভেট হেল্পার মেথড
     */
    private function uploadSettingFile(Request $request, string $key, string $folder)
    {
        // পুরনো ফাইল ডিলিট করুন
        $oldFile = GlobalSetting::where('key', $key)->value('value');
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }
        
        // নতুন ফাইল আপলোড করুন
        $path = $request->file($key)->store($folder, 'public');
        GlobalSetting::set($key, $path);
    }
}