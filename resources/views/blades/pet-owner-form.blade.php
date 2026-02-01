<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Owner Registration – DocuPet</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css'])
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

    {{-- Main content (Vue app: progress + form) --}}
    <div id="pet-owner-form-app">
        {{-- Progress (5 paws: clickable, highlighted up to current step) --}}
        <div class="flex justify-center gap-2 py-6" role="navigation" aria-label="Registration progress">
            <a
                v-for="n in 5"
                :key="n"
                href="#"
                @click.prevent="goToStep(n)"
                :class="['transition-colors hover:opacity-80', n <= currentStep ? 'text-docupet-green' : 'text-gray-400']"
                :aria-label="'Go to step ' + n"
                :aria-current="n === currentStep ? 'step' : false"
            >
                <i class="fa-solid fa-paw text-3xl" aria-hidden="true"></i>
            </a>
        </div>

        <main class="mx-auto max-w-xl px-6 pb-12">
            <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
                @if (session('success'))
                    <div class="mb-6 rounded-lg bg-docupet-green/10 p-4 text-sm text-docupet-green">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('pet-owner.register.store') }}" id="pet-registration-form">
                    @csrf
                {{-- Form block (step 1) --}}
                <div v-show="!showReview">
                <h1 class="mb-8 text-xl font-semibold text-gray-900">Tell us about your pet</h1>
                <div class="space-y-6">
                    {{-- Pet type --}}
                    <div>
                        <label for="type-id" class="mb-2 block text-sm font-medium text-gray-700">What type of pet?</label>
                        <select
                            id="type-id"
                            name="type_id"
                            v-model="typeId"
                            @change="onTypeChange"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
                            required
                        >
                            <option value="">Select a type</option>
                            <option v-for="type in types" :key="type.id" :value="type.id">@{{ type.name }}</option>
                        </select>
                    </div>

                    {{-- Pet's name --}}
                    <div>
                        <label for="dog-name" class="mb-2 block text-sm font-medium text-gray-700">What is your pet's name?</label>
                        <input
                            id="dog-name"
                            name="name"
                            type="text"
                            v-model="name"
                            placeholder="Pet's name"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
                        >
                    </div>

                    {{-- Breed --}}
                    <div>
                        <label for="breed" class="mb-2 block text-sm font-medium text-gray-700">What breed are they?</label>
                        <div class="relative">
                            <select
                                id="breed"
                                name="breed_id"
                                v-model="breedId"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-10 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
                            >
                                <option value="">Can't find it?</option>
                                <option v-for="breed in filteredBreeds" :key="breed.id" :value="breed.id">
                                    @{{ breed.name + (breed.is_dangerous ? ' (dangerous)' : '') }}
                                </option>
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fa-solid fa-chevron-down h-5 w-5" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div v-show="showBreedClarification" class="mt-2">
                            <p class="mb-2 text-sm text-gray-500">Choose One</p>
                            <div class="flex gap-6">
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input type="radio" name="breed_clarification" value="unknown" v-model="breedClarification" class="border-gray-300 text-docupet-blue focus:ring-docupet-blue">
                                    <span class="text-gray-700">I don't know</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input type="radio" name="breed_clarification" value="mix" v-model="breedClarification" class="border-gray-300 text-docupet-blue focus:ring-docupet-blue">
                                    <span class="text-gray-700">It's a mix</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-700">What gender are they?</p>
                        <input type="hidden" name="gender" :value="gender">
                        <div class="inline-flex rounded-lg border border-gray-300 bg-gray-50 p-0.5" role="group" aria-label="Gender">
                            <button
                                type="button"
                                @click="gender = 'female'"
                                :class="gender === 'female' ? 'rounded-md bg-docupet-blue px-6 py-2 text-sm font-medium text-white' : 'rounded-md px-6 py-2 text-sm font-medium text-docupet-blue hover:bg-gray-100'"
                            >
                                Female
                            </button>
                            <button
                                type="button"
                                @click="gender = 'male'"
                                :class="gender === 'male' ? 'rounded-md bg-docupet-blue px-6 py-2 text-sm font-medium text-white' : 'rounded-md px-6 py-2 text-sm font-medium text-docupet-blue hover:bg-gray-100'"
                            >
                                Male
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button
                        type="button"
                        @click="onContinue"
                        :disabled="!canContinue"
                        :class="['w-full rounded-lg py-3 font-medium', canContinue ? 'cursor-pointer bg-docupet-blue text-white hover:opacity-90' : 'cursor-not-allowed bg-gray-300 text-gray-500']"
                    >
                        Continue
                    </button>
                </div>
                </div>

                {{-- Review block (step 2, hidden until Continue clicked) --}}
                <div v-show="showReview" class="space-y-6">
                    <h1 class="mb-8 text-xl font-semibold text-gray-900">Review your pet's information</h1>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-gray-900">@{{ selectedTypeName }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-gray-900">@{{ name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-gray-900">@{{ breedDisplay }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-gray-900 capitalize">@{{ gender }}</dd>
                        </div>
                    </dl>
                    <div class="mt-10 flex gap-4">
                        <button
                            type="button"
                            @click="goToStep(1)"
                            class="flex-1 rounded-lg border border-gray-300 py-3 font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Back
                        </button>
                        <button
                            type="submit"
                            class="flex-1 rounded-lg bg-docupet-blue py-3 font-medium text-white hover:opacity-90"
                        >
                            Save
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </main>
    </div>

    <footer class="py-8 text-center text-sm text-gray-400">Footer TBD</footer>

    <script>
        new Vue({
            el: '#pet-owner-form-app',
            data: {
                types: @json($types),
                breeds: @json($breeds),
                typeId: '',
                breedId: '',
                breedClarification: '',
                gender: 'female',
                name: 'Monte',
                showReview: false,
                currentStep: 1,
                basePath: @json(url()->current()),
            },
            computed: {
                filteredBreeds: function () {
                    if (!this.typeId) return this.breeds;
                    return this.breeds.filter(function (b) {
                        return String(b.type_id) === String(this.typeId);
                    }.bind(this));
                },
                showBreedClarification: function () {
                    return this.breedId === '' || this.breedId === null;
                },
                canContinue: function () {
                    if (!this.typeId || !this.name || !this.name.trim()) return false;
                    if (this.breedId) return true;
                    if (this.showBreedClarification && this.breedClarification) return true;
                    return false;
                },
                selectedTypeName: function () {
                    var type = this.types.find(function (t) { return String(t.id) === String(this.typeId); }.bind(this));
                    return type ? type.name : '';
                },
                breedDisplay: function () {
                    if (this.breedId) {
                        var breed = this.breeds.find(function (b) { return String(b.id) === String(this.breedId); }.bind(this));
                        return breed ? breed.name + (breed.is_dangerous ? ' (dangerous)' : '') : '';
                    }
                    if (this.breedClarification === 'unknown') return 'Unknown';
                    if (this.breedClarification === 'mix') return 'Mixed';
                    return '';
                },
            },
            methods: {
                onTypeChange: function () {
                    this.breedId = '';
                },
                onContinue: function () {
                    this.showReview = true;
                    this.currentStep = 2;
                },
                goToStep: function (step) {
                    this.currentStep = step;
                    this.showReview = step >= 2;
                },
                stepUrl: function (step) {
                    var base = this.basePath.split('?')[0];
                    return base + '?step=' + step;
                },
            },
        });
    </script>
</body>
</html>
