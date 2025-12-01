<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGA-Formosa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">SIGA-Formosa</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Sistema Integral de Gestión Académica</p>

        <a href="{{ route('login') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
            Ingresar
        </a>
    </div>
</body>

</html>