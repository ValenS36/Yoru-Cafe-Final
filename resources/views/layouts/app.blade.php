<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - YoruCafe</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 bg-[#f8f9fa] antialiased flex h-screen overflow-hidden p-4 gap-4">

    <!-- Sidebar Component -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#f8f9fa] overflow-hidden">
        
        <!-- Header Section -->
        <div class="px-6 pt-6">
            @yield('header')
        </div>
        
        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto mt-4 px-6">
            @yield('content')
        </main>
        
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
