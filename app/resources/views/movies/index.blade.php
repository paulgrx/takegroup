<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="bg-neutral-50 dark:bg-neutral-900 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-neutral-900 dark:text-white mb-2">
                    Movies Collection
                </h1>
            </div>

            <livewire:movie-list />
        </div>
    </body>
</html>
