@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('header')
<header class="flex items-start justify-between bg-transparent pb-2 max-w-[1400px] mx-auto w-full mt-2">
    <!-- Left side -->
    <div>
        <div class="flex items-center text-[11px] font-semibold text-gray-400 tracking-wide uppercase mb-1">
            <span class="text-[#ea580c]">YORUCAFE</span>
            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
            <span>Pengaturan Profil</span>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Pengaturan Profil
        </h2>
        <p class="text-xs font-medium text-gray-500 mt-1">Kelola informasi akun dan preferensi Anda</p>
    </div>

    <!-- Right side -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <button class="relative bg-white p-2.5 rounded-full text-gray-400 hover:text-gray-600 shadow-sm border border-gray-100 transition-all focus:outline-none">
            <span class="absolute top-2.5 right-2.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            <i data-lucide="bell" class="w-5 h-5"></i>
        </button>

        <!-- User Profile Pic -->
        <a href="{{ route('profile') }}" class="h-10 w-10 rounded-full border-2 border-white shadow-sm overflow-hidden bg-gray-200 cursor-pointer block hover:ring-2 hover:ring-[#ea580c] transition-all">
            <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-[1400px] mx-auto pb-8">
    <div class="flex flex-col lg:flex-row gap-6 mt-4">
        
        <!-- Left Column -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Profile Info Card -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-8 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-200 mb-4">
                    <img src="{{ Auth::user()->image ? Storage::url(Auth::user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ea580c&color=fff' }}" alt="User" class="w-full h-full object-cover">
                </div>
                <h3 class="text-lg font-extrabold text-gray-900">{{ Auth::user()->name }}</h3>
                <p class="text-sm font-medium text-gray-500 mb-3">{{ Auth::user()->email }}</p>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ Auth::user()->role === 'admin' ? 'text-red-600 bg-red-50 border-red-100' : 'text-blue-600 bg-blue-50 border-blue-100' }} mb-6">
                    <i data-lucide="{{ Auth::user()->role === 'admin' ? 'shield' : 'calculator' }}" class="w-3.5 h-3.5 mr-1.5"></i> {{ ucfirst(Auth::user()->role) }}
                </span>

                <button onclick="document.getElementById('profile_image_input').click()" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl border border-gray-200 transition-colors flex items-center justify-center text-sm shadow-sm mb-3">
                    <i data-lucide="camera" class="w-4 h-4 mr-2"></i> Ganti Foto Profil
                </button>
                <p class="text-[10px] font-medium text-gray-400">PNG, JPG maks. 2MB</p>
            </div>

            <!-- Account Status Card -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-extrabold text-gray-900 mb-4">Informasi Akun</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                        <span class="text-xs font-medium text-gray-500">ID Pengguna</span>
                        <span class="text-xs font-extrabold text-gray-900">#YC-00142</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                        <span class="text-xs font-medium text-gray-500">Bergabung Sejak</span>
                        <span class="text-xs font-bold text-gray-900">12 Jan 2023</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                        <span class="text-xs font-medium text-gray-500">Login Terakhir</span>
                        <span class="text-xs font-bold text-gray-900">Hari ini, 08:32</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-medium text-gray-500">Status</span>
                        <span class="text-xs font-bold text-green-600 flex items-center">
                            <div class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></div> Aktif
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="w-full lg:w-2/3">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hidden Image Input -->
                <input type="file" id="profile_image_input" name="image" class="hidden" onchange="this.form.submit()">

                <!-- Personal Information Form -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-start mb-6">
                        <div class="w-12 h-12 rounded-[14px] bg-[#ea580c] flex items-center justify-center text-white mr-4 flex-shrink-0">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Informasi Pribadi</h3>
                            <p class="text-xs font-medium text-gray-500 mt-0.5">Perbarui data diri Anda</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-bold flex items-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm font-bold">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 flex items-center">
                                <i data-lucide="user" class="w-3.5 h-3.5 mr-1.5 text-gray-400"></i> Nama Lengkap
                            </label>
                            <div class="relative">
                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="block w-full rounded-xl bg-white border border-gray-200 py-2.5 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm transition-colors shadow-sm">
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 flex items-center">
                                <i data-lucide="mail" class="w-3.5 h-3.5 mr-1.5 text-gray-400"></i> Alamat Email
                            </label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ Auth::user()->email }}" class="block w-full rounded-xl bg-white border border-gray-200 py-2.5 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm transition-colors shadow-sm">
                            </div>
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 flex items-center">
                                <i data-lucide="shield" class="w-3.5 h-3.5 mr-1.5 text-red-400"></i> Peran / Role
                            </label>
                            <div class="relative mb-1">
                                <input type="text" value="{{ ucfirst(Auth::user()->role) }}" readonly class="block w-full rounded-xl bg-gray-50 border border-gray-200 py-2.5 px-4 text-gray-500 font-bold sm:text-sm shadow-sm cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="h-4 w-4 text-gray-300"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium">Hubungi super admin untuk mengubah role</p>
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 rounded-[14px] bg-orange-50 flex items-center justify-center text-[#ea580c] mr-4 flex-shrink-0">
                                <i data-lucide="lock" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-gray-900">Keamanan Akun</h3>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">Perbarui kata sandi Anda secara berkala</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-5">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <input type="password" name="current_password" class="block w-full rounded-xl bg-white border border-gray-200 py-2.5 px-4 text-gray-900 font-medium focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm transition-colors shadow-sm tracking-widest">
                            </div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" name="new_password" placeholder="Min. 8 karakter" class="block w-full rounded-xl bg-white border border-gray-200 py-2.5 px-4 text-gray-900 font-medium focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm transition-colors shadow-sm">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input type="password" name="new_password_confirmation" placeholder="Ulangi kata sandi baru" class="block w-full rounded-xl bg-white border border-gray-200 py-2.5 px-4 text-gray-900 font-medium focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm transition-colors shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex items-center justify-between pt-4">
                    <p class="text-[11px] font-medium text-gray-400 flex items-center">
                        <i data-lucide="info" class="w-3.5 h-3.5 mr-1.5"></i> Perubahan akan disimpan dan berlaku segera
                    </p>
                    <div class="flex space-x-3">
                        <button type="reset" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-colors flex items-center">
                            <i data-lucide="x" class="w-4 h-4 mr-1.5"></i> Batal
                        </button>
                        <button type="submit" class="bg-[#ea580c] hover:bg-[#c2410c] text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-colors flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-1.5"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
