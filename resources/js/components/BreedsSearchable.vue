<template>
  <searchable-select
    :value="value"
    :options="breedOptions"
    label="label"
    :reduce="option => option.id"
    :placeholder="isDisabled && !typeId ? 'Select a type first' : placeholder"
    :disabled="isDisabled"
    :clearable="clearable"
    :filterable="false"
    @input="$emit('input', $event)"
    @search="onSearch"
  />
</template>

<script>
import { getBreeds } from '../api/apiManager';
import SearchableSelect from './SearchableSelect.vue';

export default {
  name: 'BreedsSearchable',
  components: { SearchableSelect },
  props: {
    value: {
      type: [String, Number],
      default: null,
    },
    typeId: {
      type: [String, Number],
      default: null,
    },
    placeholder: {
      type: String,
      default: 'Select a breed',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    clearable: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      breeds: [],
      searchTimeout: null,
    };
  },
  computed: {
    breedOptions() {
      const breedList = this.breeds.map((b) => ({
        ...b,
        label: b.name + (b.is_dangerous ? ' (dangerous)' : ''),
      }));
      return [{ id: null, label: "Can't find it?" }, ...breedList];
    },
    isDisabled() {
      return !this.typeId || this.disabled;
    },
  },
  watch: {
    typeId: {
      immediate: true,
      handler(newVal) {
        this.$emit('input', null);
        if (newVal) {
          this.loadBreeds(newVal);
        } else {
          this.breeds = [];
        }
      },
    },
  },
  methods: {
    async loadBreeds(typeId, search = '') {
      if (!typeId) return;
      try {
        const { data } = await getBreeds({ type_id: typeId, search, per_page: 100 });
        this.breeds = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load breeds:', err);
        this.breeds = [];
      }
    },
    onSearch(search) {
      if (!this.typeId) return;
      if (this.searchTimeout) clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(async () => {
        await this.loadBreeds(this.typeId, search || '');
      }, 300);
    },
  },
};
</script>
