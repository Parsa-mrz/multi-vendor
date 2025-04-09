<!DOCTYPE html>
<html lang="{{ str_replace( '_', '-', app()->getLocale() ) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>{{ $title ?? 'Page Title' }}</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite( 'resources/css/app.css' )
    @livewireStyles
</head>

<body class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <livewire:header />

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-gray-600">
            <p>&copy; {{ date( 'Y' ) }} My Application. All rights reserved.</p>
        </div>
    </footer>

    @livewireScripts
    @vite( 'resources/js/app.js' )
    <script>
        Livewire.on('swal', (params) => {
            const alertData = Array.isArray(params) ? params[0] : params;
            Swal.fire({
                toast: true,
                title: alertData.title || '',
                text: alertData.text || '',
                icon: alertData.type,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                didClose: () => {
                    if (alertData.redirect) {
                        window.location.href = alertData.redirect;
                    }
                }
            });
        });
    </script>
</body>

</html>
