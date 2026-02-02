<template>
  <div class="space-y-6">
    <h1 class="mb-8 text-xl font-semibold text-gray-900">Review your pet's information</h1>

    <dl class="space-y-4" v-if="formData">
      <div>
        <dt class="text-sm font-medium text-gray-500">Type</dt>
        <dd class="mt-1 text-gray-900">{{ typeName }}</dd>
      </div>
      <div>
        <dt class="text-sm font-medium text-gray-500">Name</dt>
        <dd class="mt-1 text-gray-900">{{ formData.name }}</dd>
      </div>
      <div>
        <dt class="text-sm font-medium text-gray-500">Breed</dt>
        <dd class="mt-1 text-gray-900">{{ breedDisplay }}</dd>
      </div>
      <div>
        <dt class="text-sm font-medium text-gray-500">Gender</dt>
        <dd class="mt-1 text-gray-900 capitalize">{{ formData.gender }}</dd>
      </div>
      <div>
        <dt class="text-sm font-medium text-gray-500">Age</dt>
        <dd class="mt-1 text-gray-900">{{ ageDisplay }}</dd>
      </div>
    </dl>

    <div class="mt-10 flex gap-4">
      <button
        type="button"
        :disabled="saving"
        @click="$emit('back')"
        :class="[
          'flex-1 rounded-lg border border-gray-300 py-3 font-medium',
          saving ? 'cursor-not-allowed bg-gray-50 text-gray-400' : 'text-gray-700 hover:bg-gray-50'
        ]"
      >
        Back
      </button>
      <button
        type="button"
        :disabled="saving"
        @click="$emit('save')"
        :class="[
          'flex-1 rounded-lg py-3 font-medium',
          saving ? 'cursor-not-allowed bg-docupet-blue/70 text-white' : 'bg-docupet-blue text-white hover:opacity-90'
        ]"
      >
        {{ saving ? 'Saving...' : 'Save' }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Review',
  props: {
    formData: {
      type: Object,
      default: null,
    },
    types: {
      type: Array,
      default: () => [],
    },
    breeds: {
      type: Array,
      default: () => [],
    },
    saving: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    typeName() {
      if (!this.formData || !this.formData.typeId) return '';
      const type = this.types.find((t) => String(t.id) === String(this.formData.typeId));
      return type ? type.name : '';
    },
    breedDisplay() {
      if (!this.formData) return '';
      if (this.formData.breedId) {
        const breed = this.breeds.find((b) => String(b.id) === String(this.formData.breedId));
        return breed ? breed.name + (breed.is_dangerous ? ' (dangerous)' : '') : '';
      }
      if (this.formData.breedClarification === 'unknown') return 'Unknown';
      if (this.formData.breedClarification === 'mix') {
        return this.formData.breedText ? 'Mixed: ' + this.formData.breedText : 'Mixed';
      }
      return '';
    },
    ageDisplay() {
      if (!this.formData) return '';
      if (this.formData.knowsDob === 'no' && this.formData.approxAgeYears) {
        const y = this.formData.approxAgeYears;
        return y + ' year' + (y !== 1 ? 's' : '') + ' (approximate)';
      }
      if (this.formData.knowsDob === 'yes' && this.formData.dob) {
        const parts = this.ageFromDob(this.formData.dob);
        return parts.join(', ');
      }
      return '';
    },
  },
  methods: {
    ageFromDob(dobStr) {
      const birth = new Date(dobStr);
      const today = new Date();
      let years = today.getFullYear() - birth.getFullYear();
      let months = today.getMonth() - birth.getMonth();
      let days = today.getDate() - birth.getDate();
      if (days < 0) {
        months -= 1;
        days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
      }
      if (months < 0) {
        years -= 1;
        months += 12;
      }
      const parts = [];
      if (years) parts.push(years + ' year' + (years !== 1 ? 's' : ''));
      if (months) parts.push(months + ' month' + (months !== 1 ? 's' : ''));
      if (days || parts.length === 0) parts.push(days + ' day' + (days !== 1 ? 's' : ''));
      return parts;
    },
  },
};
</script>
