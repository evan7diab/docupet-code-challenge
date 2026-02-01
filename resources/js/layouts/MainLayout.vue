<template>
  <div class="min-h-screen bg-gray-100 font-sans antialiased">
    <!-- Header: same as pet-owner-form -->
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

    <!-- Progress paws -->
    <div class="flex justify-center gap-2 py-6" role="navigation" aria-label="Registration progress">
      <slot name="progress">
        <a
          v-for="n in 5"
          :key="n"
          href="#"
          @click.prevent="$emit('step-change', n)"
          :class="['transition-colors hover:opacity-80', n <= currentStep ? 'text-docupet-green' : 'text-gray-400']"
          :aria-label="'Go to step ' + n"
          :aria-current="n === currentStep ? 'step' : false"
        >
          <i class="fa-solid fa-paw text-3xl" aria-hidden="true"></i>
        </a>
      </slot>
    </div>

    <!-- Main content -->
    <main class="mx-auto max-w-xl px-6 pb-12">
      <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
        <slot></slot>
      </div>
    </main>

    <footer class="py-8 text-center text-sm text-gray-400">Footer TBD</footer>
  </div>
</template>

<script>
export default {
  name: 'MainLayout',
  props: {
    currentStep: {
      type: Number,
      default: 1,
    },
  },
};
</script>
