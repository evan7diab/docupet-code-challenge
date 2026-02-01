<template>
  <div class="space-y-6">
    <h1 class="mb-8 text-xl font-semibold text-gray-900">Tell us about your pet</h1>

    <div>
      <label for="type-id" class="mb-2 block text-sm font-medium text-gray-700">What type of pet?</label>
      <select
        id="type-id"
        v-model="form.typeId"
        @change="onTypeChange"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
      >
        <option value="">Select a type</option>
        <option v-for="type in types" :key="type.id" :value="type.id">
          {{ type.name }}
        </option>
      </select>
    </div>

    <div>
      <label for="pet-name" class="mb-2 block text-sm font-medium text-gray-700">What is your pet's name?</label>
      <input
        id="pet-name"
        v-model="form.name"
        type="text"
        placeholder="Pet's name"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
      >
    </div>

    <!-- Breed -->
    <div>
      <label for="breed" class="mb-2 block text-sm font-medium text-gray-700">What breed are they?</label>
      <div class="relative">
        <select
          id="breed"
          v-model="form.breedId"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pr-10 text-gray-900 placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
        >
          <option value="">Can't find it?</option>
          <option v-for="breed in filteredBreeds" :key="breed.id" :value="breed.id">
            {{ breed.name }}{{ breed.is_dangerous ? ' (dangerous)' : '' }}
          </option>
        </select>
      </div>
      <div v-show="showBreedClarification" class="mt-2">
        <p class="mb-2 text-sm text-gray-500">Choose One</p>
        <div class="flex gap-6">
          <label class="flex cursor-pointer items-center gap-2">
            <input
              v-model="form.breedClarification"
              type="radio"
              value="unknown"
              class="border-gray-300 text-docupet-blue focus:ring-docupet-blue"
            >
            <span class="text-gray-700">I don't know</span>
          </label>
          <label class="flex cursor-pointer items-center gap-2">
            <input
              v-model="form.breedClarification"
              type="radio"
              value="mix"
              class="border-gray-300 text-docupet-blue focus:ring-docupet-blue"
            >
            <span class="text-gray-700">It's a mix</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Pet's Age -->
    <div>
      <p class="mb-2 text-sm font-medium text-gray-700">Pet's Age</p>
      <p class="mb-4 text-sm text-gray-500">Do you know their date of birth?</p>
      <div class="mb-4 flex gap-6">
        <label class="flex cursor-pointer items-center gap-2">
          <input
            v-model="form.knowsDob"
            type="radio"
            value="no"
            class="border-gray-300 text-docupet-blue focus:ring-docupet-blue"
          >
          <span class="text-gray-700">No</span>
        </label>
        <label class="flex cursor-pointer items-center gap-2">
          <input
            v-model="form.knowsDob"
            type="radio"
            value="yes"
            class="border-gray-300 text-docupet-blue focus:ring-docupet-blue"
          >
          <span class="text-gray-700">Yes</span>
        </label>
      </div>

      <!-- Approximate Age (when No) -->
      <div v-show="form.knowsDob === 'no'" class="mt-4">
        <label for="approx-age" class="mb-2 block text-sm font-medium text-gray-700">Approximate Age</label>
        <select
          id="approx-age"
          v-model="form.approxAgeYears"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
        >
          <option :value="null">Select age</option>
          <option v-for="age in 20" :key="age" :value="age">{{ age }} year{{ age !== 1 ? 's' : '' }}</option>
        </select>
      </div>

      <!-- Date of Birth (when Yes) -->
      <div v-show="form.knowsDob === 'yes'" class="mt-4">
        <label for="dob" class="mb-2 block text-sm font-medium text-gray-700">Date of Birth</label>
        <input
          id="dob"
          v-model="form.dob"
          type="date"
          :max="maxDobDate"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-docupet-blue"
        >
      </div>
    </div>

    <!-- Gender -->
    <div>
      <p class="mb-2 text-sm font-medium text-gray-700">What gender are they?</p>
      <div class="inline-flex rounded-lg border border-gray-300 bg-gray-50 p-0.5" role="group" aria-label="Gender">
        <button
          type="button"
          :class="form.gender === 'female' ? 'rounded-md bg-docupet-blue px-6 py-2 text-sm font-medium text-white' : 'rounded-md px-6 py-2 text-sm font-medium text-docupet-blue hover:bg-gray-100'"
          @click="form.gender = 'female'"
        >
          Female
        </button>
        <button
          type="button"
          :class="form.gender === 'male' ? 'rounded-md bg-docupet-blue px-6 py-2 text-sm font-medium text-white' : 'rounded-md px-6 py-2 text-sm font-medium text-docupet-blue hover:bg-gray-100'"
          @click="form.gender = 'male'"
        >
          Male
        </button>
      </div>
    </div>

    <div class="mt-10">
      <button
        type="button"
        :disabled="!canContinue"
        :class="[
          'w-full rounded-lg py-3 font-medium',
          canContinue ? 'cursor-pointer bg-docupet-blue text-white hover:opacity-90' : 'cursor-not-allowed bg-gray-300 text-gray-500'
        ]"
        @click="$emit('continue', form)"
      >
        Continue
      </button>
    </div>
  </div>
</template>

<script>
import { getTypes, getBreeds } from '../api/apiManager';

export default {
  name: 'RegistrationForm',
  data() {
    return {
      types: [],
      breeds: [],
      form: {
        typeId: '',
        name: '',
        breedId: '',
        breedClarification: '',
        knowsDob: '',
        approxAgeYears: null,
        dob: '',
        gender: 'female',
      },
    };
  },
  computed: {
    filteredBreeds() {
      if (!this.form.typeId) return this.breeds;
      return this.breeds.filter(
        (b) => String(b.type_id) === String(this.form.typeId)
      );
    },
    showBreedClarification() {
      return this.form.breedId === '' || this.form.breedId === null;
    },
    maxDobDate() {
      return new Date().toISOString().split('T')[0];
    },
    canContinue() {
      if (!this.form.typeId || !this.form.name || !this.form.name.trim()) return false;
      if (!this.form.breedId && !(this.showBreedClarification && this.form.breedClarification)) return false;
      if (!this.form.knowsDob) return false;
      if (this.form.knowsDob === 'no') return this.form.approxAgeYears >= 1 && this.form.approxAgeYears <= 20;
      if (this.form.knowsDob === 'yes') return this.form.dob && this.form.dob.length > 0;
      return false;
    },
  },
  mounted() {
    this.loadTypes();
    this.loadBreeds();
  },
  methods: {
    async loadTypes() {
      try {
        const { data } = await getTypes();
        this.types = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load types:', err);
      }
    },
    async loadBreeds() {
      try {
        const { data } = await getBreeds();
        this.breeds = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load breeds:', err);
      }
    },
    onTypeChange() {
      this.form.breedId = '';
      this.form.breedClarification = '';
      this.$emit('type-change', this.form.typeId);
    },
  },
};
</script>
