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
        <registration-form
          v-show="!showReview"
          @continue="onContinue"
        />
        <review
          v-show="showReview"
          :form-data="formData"
          :types="types"
          :breeds="breeds"
          @back="onBack"
          @save="onSave"
        />
      </div>
    </main>

    <footer class="py-8 text-center text-sm text-gray-400">Footer TBD</footer>
  </div>
</template>

<script>
import { getTypes, getBreeds } from '../api/apiManager';
import RegistrationForm from './RegistrationForm.vue';
import Review from './Review.vue';

export default {
  name: 'MainLayout',
  components: { RegistrationForm, Review },
  data() {
    return {
      currentStep: 1,
      showReview: false,
      formData: null,
      types: [],
      breeds: [],
    };
  },
  mounted() {
    this.loadTypes();
    this.loadBreeds();
  },
  methods: {
    async loadTypes() {
      try {
        const { data } = await getTypes({ per_page: 100 });
        this.types = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load types:', err);
      }
    },
    async loadBreeds() {
      try {
        const { data } = await getBreeds({ per_page: 500 });
        this.breeds = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load breeds:', err);
      }
    },
    onContinue(form) {
      this.formData = form;
      this.showReview = true;
      this.currentStep = 2;
    },
    onBack() {
      this.showReview = false;
      this.currentStep = 1;
    },
    onSave() {
      // TODO: submit to API
      console.log('Save', this.formData);
    },
  },
};
</script>
