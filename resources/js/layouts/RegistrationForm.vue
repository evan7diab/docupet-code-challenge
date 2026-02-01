<template>
  <div class="space-y-6">
    <h1 class="mb-8 text-xl font-semibold text-gray-900">Tell us about your pet</h1>

    <div>
      <label for="type-id" class="mb-2 block text-sm font-medium text-gray-700">What type of pet?</label>
      <select
        id="type-id"
        v-model="form.typeId"
        @change="onTypeChange"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
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
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
    </div>

    <slot name="extra-fields"></slot>

    <div class="mt-10">
      <button
        type="button"
        :disabled="!canContinue"
        @click="$emit('continue')"
        :class="[
          'w-full rounded-lg py-3 font-medium',
          canContinue ? 'bg-blue-600 text-white hover:opacity-90 cursor-pointer' : 'bg-gray-300 text-gray-500 cursor-not-allowed'
        ]"
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
      },
    };
  },
  computed: {
    canContinue() {
      return this.form.typeId && this.form.name && this.form.name.trim().length > 0;
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
      this.$emit('type-change', this.form.typeId);
    },
  },
};
</script>
