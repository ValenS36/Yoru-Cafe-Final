@extends('layouts.auth')

@section('content')
<div class="flex min-h-screen bg-[#e8ebeb]">
    
    <!-- Left Side - Image/Branding (Hidden on Mobile) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-end p-12">
        <!-- Background Image -->
        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070&auto=format&fit=crop" 
             alt="Cafe Background" 
             class="absolute inset-0 w-full h-full object-cover z-0 brightness-50">
             
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent z-10"></div>
             
        <div class="relative z-20 text-white max-w-lg">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-6 h-6 text-orange-500">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11 2v20c-5.07-.5-9-4.79-9-10s3.93-9.5 9-10zm2 0v20c5.07-.5 9-4.79 9-10s-3.93-9.5-9-10zm-1 2.06c-3.95.49-7 3.85-7 8.94s3.05 8.45 7 8.94V4.06zm2 0v17.88c3.95-.49 7-3.85 7-8.94s-3.05-8.45-7-8.94z"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight">YourCafe POS</span>
            </div>
            <h1 class="text-5xl font-extrabold mb-4 leading-tight tracking-tight">Join the<br>Flavor Revolution</h1>
            <p class="text-base text-gray-300 font-normal">Empower your cafe with the tools for success. Create your staff account and start managing your workspace efficiently.</p>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative bg-[#e5e5e5]">
        
        <div class="w-full max-w-[480px] bg-white rounded-xl shadow-xl p-8 sm:p-10">
            
            <div class="mb-8">
                <h2 class="text-[28px] font-bold text-gray-900 mb-1 tracking-tight">Staff Registration</h2>
                <p class="text-sm text-gray-500">Create a new account to join the cafe management system.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="John Doe"
                               class="block w-full rounded-lg bg-[#eef0ff] border-0 py-3 pl-10 pr-4 text-gray-900 focus:ring-2 focus:ring-primary sm:text-sm transition-colors placeholder:text-gray-400 font-medium">
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="name@yourcafe.com"
                               class="block w-full rounded-lg bg-[#eef0ff] border-0 py-3 pl-10 pr-4 text-gray-900 focus:ring-2 focus:ring-primary sm:text-sm transition-colors placeholder:text-gray-400 font-medium">
                    </div>
                </div>

                <!-- Password Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="h-4 w-4 text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required placeholder="••••••••"
                                   class="block w-full rounded-lg bg-[#eef0ff] border-0 py-3 pl-10 pr-4 text-gray-900 focus:ring-2 focus:ring-primary sm:text-sm transition-colors placeholder:text-gray-400 font-medium tracking-widest">
                        </div>
                    </div>
                    <!-- Confirm Password Input -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Confirm</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i data-lucide="check-circle" class="h-4 w-4 text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••"
                                   class="block w-full rounded-lg bg-[#eef0ff] border-0 py-3 pl-10 pr-4 text-gray-900 focus:ring-2 focus:ring-primary sm:text-sm transition-colors placeholder:text-gray-400 font-medium tracking-widest">
                        </div>
                    </div>
                </div>
                
                <!-- Role Selection -->
                <div class="pt-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-2.5 uppercase tracking-wide">Select Your Role</label>
                    <div class="grid grid-cols-2 gap-3" x-data="{ selectedRole: 'cashier' }">
                        <!-- Admin Role -->
                        <button type="button" @click="selectedRole = 'admin'" 
                                :class="selectedRole === 'admin' ? 'bg-[#f4f2ff] border-transparent text-gray-900 ring-2 ring-[#ea580c]' : 'bg-white border-gray-200 text-gray-600 border-2 hover:bg-gray-50'"
                                class="flex flex-col items-center justify-center p-3 rounded-lg transition-all relative">
                            <i data-lucide="shield" class="h-5 w-5 mb-1.5" :class="selectedRole === 'admin' ? 'text-[#ea580c]' : 'text-gray-500'"></i>
                            <span class="text-xs font-semibold">Manager</span>
                        </button>
                        
                        <!-- Cashier Role -->
                        <button type="button" @click="selectedRole = 'cashier'" 
                                :class="selectedRole === 'cashier' ? 'bg-[#f4f2ff] border-transparent text-gray-900 ring-2 ring-[#ea580c]' : 'bg-white border-[#d1d5db] border-2 text-gray-800 hover:bg-gray-50'"
                                class="flex flex-col items-center justify-center p-3 rounded-lg transition-all relative">
                            <i data-lucide="calculator" class="h-5 w-5 mb-1.5" :class="selectedRole === 'cashier' ? 'text-[#ea580c]' : 'text-gray-500'"></i>
                            <span class="text-xs font-semibold">Cashier</span>
                        </button>
                        
                        <!-- Hidden input to pass role -->
                        <input type="hidden" name="role" x-model="selectedRole">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="flex w-full justify-center items-center rounded-lg bg-[#ea580c] px-3 py-3.5 text-sm font-bold text-white shadow-md hover:bg-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600 transition-all duration-200">
                        Create Account <i data-lucide="user-plus" class="w-4 h-4 ml-1.5"></i>
                    </button>
                </div>
                
                <div class="text-center mt-6 pt-2">
                    <p class="text-xs text-gray-500 font-medium">
                        Already have an account? <a href="{{ route('login') }}" class="text-[#ea580c] font-bold hover:underline">Log in</a>
                    </p>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection
