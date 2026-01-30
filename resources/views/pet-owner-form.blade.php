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
<body class="min-h-screen bg-gray-100 font-sans antialiased">
    {{-- Header --}}
    <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-docupet-blue">
                <i class="fa-solid fa-paw text-lg text-white" aria-hidden="true"></i>
            </div>
            <div>
                <div class="font-semibold text-gray-900">DocuPet</div>
                <div class="text-xs text-gray-500">A safe and happy home for every pet</div>
            </div>
        </div>
        <nav class="flex items-center gap-6">
            <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Help</a>
            <a href="#" class="font-semibold text-docupet-blue hover:underline">Save and Finish Later</a>
        </nav>
    </header>

    {{-- Progress (5 paws: clickable, highlighted up to current step from ?step=) --}}
    @php
        $currentStep = min(5, max(1, (int) request('step', 1)));
    @endphp
    <div class="flex justify-center gap-2 py-6" role="navigation" aria-label="Registration progress">
        @foreach(range(1, 5) as $stepNumber)
            <a
                href="{{ request()->fullUrlWithQuery(['step' => $stepNumber]) }}"
                class="transition-colors hover:opacity-80 {{ $stepNumber <= $currentStep ? 'text-docupet-green' : 'text-gray-400' }}"
                aria-label="Go to step {{ $stepNumber }}"
                aria-current="{{ $stepNumber === $currentStep ? 'step' : false }}"
            >
                <i class="fa-solid fa-paw text-3xl" aria-hidden="true"></i>
            </a>
        @endforeach
    </div>

    {{-- Main form card --}}
    <main class="mx-auto max-w-xl px-6 pb-12">
        <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
            <h1 class="mb-8 text-xl font-semibold text-gray-900">Tell us about your dog</h1>

            <div class="space-y-6">
                {{-- Dog's name --}}
                <div>
                    <label for="dog-name" class="mb-2 block text-sm font-medium text-gray-700">What is your dog's name?</label>
                    <input
                        id="dog-name"
                        type="text"
                        value="Monte"
                        placeholder="Dog's name"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
                    >
                </div>

                {{-- Breed --}}
                <div>
                    <label for="breed" class="mb-2 block text-sm font-medium text-gray-700">What breed are they?</label>
                    <div class="relative">
                        <input
                            id="breed"
                            type="text"
                            placeholder="Can't find it?"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-10 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
                        >
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Choose One</p>
                    <div class="mt-2 flex gap-6">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input type="radio" name="breed_clarification" value="unknown" class="border-gray-300 text-docupet-blue focus:ring-docupet-blue">
                            <span class="text-gray-700">I don't know</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input type="radio" name="breed_clarification" value="mix" class="border-gray-300 text-docupet-blue focus:ring-docupet-blue">
                            <span class="text-gray-700">It's a mix</span>
                        </label>
                    </div>
                </div>

                {{-- Gender --}}
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-700">What gender are they?</p>
                    <input type="hidden" name="gender" id="gender-value" value="female">
                    <div id="gender-toggle" class="inline-flex rounded-lg border border-gray-300 bg-gray-50 p-0.5" role="group" aria-label="Gender">
                        <button type="button" data-gender="female" class="gender-option rounded-md bg-docupet-blue px-6 py-2 text-sm font-medium text-white">Female</button>
                        <button type="button" data-gender="male" class="gender-option rounded-md px-6 py-2 text-sm font-medium text-docupet-blue hover:bg-gray-100">Male</button>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <button type="button" class="w-full cursor-not-allowed rounded-lg bg-gray-300 py-3 font-medium text-gray-500" disabled>Continue</button>
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-sm text-gray-400">Footer TBD</footer>

    <script>
        document.querySelectorAll('.gender-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var value = this.getAttribute('data-gender');
                document.getElementById('gender-value').value = value;
                document.querySelectorAll('.gender-option').forEach(function (b) {
                    if (b.getAttribute('data-gender') === value) {
                        b.classList.add('bg-docupet-blue', 'text-white');
                        b.classList.remove('text-docupet-blue', 'hover:bg-gray-100');
                    } else {
                        b.classList.remove('bg-docupet-blue', 'text-white');
                        b.classList.add('text-docupet-blue', 'hover:bg-gray-100');
                    }
                });
            });
        });
    </script>
</body>
</html>
