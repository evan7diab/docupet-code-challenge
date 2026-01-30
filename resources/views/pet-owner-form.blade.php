<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Owner Registration – DocuPet</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 antialiased">
    {{-- Header --}}
    <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 docupet-bg-blue rounded flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2C9.24 2 7 4.24 7 7c0 1.57.69 2.97 1.78 3.93C6.32 11.6 5 13.97 5 16.5V20h14v-3.5c0-2.53-1.32-4.9-3.22-5.57C16.31 9.97 17 8.57 17 7c0-2.76-2.24-5-5-5zm0 2c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm-2 8.08V18h4v-5.92c1.16-.41 2-1.53 2-2.83 0-.55-.45-1-1-1H9c-.55 0-1 .45-1 1 0 1.3.84 2.42 2 2.83z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-900">DocuPet</div>
                <div class="text-xs text-gray-500">A safe and happy home for every pet</div>
            </div>
        </div>
        <nav class="flex items-center gap-6">
            <a href="#" class="text-gray-600 hover:text-gray-900">Help</a>
            <a href="#" class="font-semibold docupet-blue hover:underline">Save and Finish Later</a>
        </nav>
    </header>

    {{-- Progress (5 paws: 2 active green, 3 gray) --}}
    <div class="flex justify-center gap-2 py-6">
        @foreach(range(1, 5) as $step)
            <span class="{{ $step <= 2 ? 'paw-active' : 'paw-inactive' }}">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C9.24 2 7 4.24 7 7c0 1.57.69 2.97 1.78 3.93C6.32 11.6 5 13.97 5 16.5V20h14v-3.5c0-2.53-1.32-4.9-3.22-5.57C16.31 9.97 17 8.57 17 7c0-2.76-2.24-5-5-5z"/>
                </svg>
            </span>
        @endforeach
    </div>

    {{-- Main form card --}}
    <main class="max-w-xl mx-auto px-6 pb-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-xl font-semibold text-gray-900 mb-8">Tell us about your dog</h1>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">What is your dog's name?</label>
                    <input type="text" value="Monte" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dog's name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">What breed are they?</label>
                    <div class="relative">
                        <input type="text" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-10 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Can't find it?">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Choose One</p>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="breed_clarification" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700">I don't know</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="breed_clarification" class="text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700">It's a mix</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">What gender are they?</label>
                    <div class="inline-flex rounded-lg border border-gray-300 p-0.5 bg-gray-50">
                        <button type="button" class="px-6 py-2 rounded-md docupet-bg-blue text-white text-sm font-medium">Female</button>
                        <button type="button" class="px-6 py-2 rounded-md text-blue-600 text-sm font-medium hover:bg-gray-100">Male</button>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <button type="button" class="w-full py-3 rounded-lg bg-gray-300 text-gray-500 font-medium cursor-not-allowed" disabled>Continue</button>
            </div>
        </div>
    </main>

    <footer class="text-center py-8 text-gray-400 text-sm">Footer TBD</footer>
</body>
</html>
