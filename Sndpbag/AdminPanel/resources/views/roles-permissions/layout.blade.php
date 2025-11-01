@extends('admin-panel::dashboard.layouts.app')

{{-- এই অংশটি roles/index.php ফাইল থেকে @section('title')-এর ভ্যালুটি নেবে --}}
{{-- @section('title')
    @yield('title', 'Roles & Permissions')
@endsection --}}

{{-- এই অংশটি roles/index.php ফাইল থেকে @section('header')-এর ভ্যালুটি নেবে --}}
{{-- @section('page-title')
    @yield('header', 'Roles & Permissions Management')
@endsection --}}

{{-- প্রধান কন্টেন্ট সেকশন, যেটি app.blade.php-তে আছে --}}
@section('content')
<div class="fade-in">

    {{-- সাকসেস, এরর এবং ভ্যালিডেশন মেসেজ দেখানোর জন্য --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl dark:bg-gray-800 dark:border-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-red-200 text-red-800 rounded-xl dark:bg-gray-800 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl dark:bg-gray-800 dark:border-red-700 dark:text-red-300">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- আপনার roles/index.blade.php বা permissions/index.blade.php ফাইলের কন্টেন্ট এখানে লোড হবে --}}
    @yield('roles_content')

</div>
@endsection