<template>
  <searchable-select
    :value="value"
    :options="types"
    label="name"
    :reduce="option => option.id"
    :placeholder="placeholder"
    :disabled="disabled"
    :clearable="clearable"
    :filterable="false"
    @input="$emit('input', $event)"
    @search="onSearch"
  />
</template>

<script>
import { getTypes } from '../api/apiManager';
import SearchableSelect from './SearchableSelect.vue';

export default {
  name: 'TypesSearchable',
  components: { SearchableSelect },
  props: {
    value: {
      type: [String, Number],
      default: null,
    },
    placeholder: {
      type: String,
      default: 'Select a type',
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
      types: [],
      searchTimeout: null,
    };
  },
  mounted() {
    this.loadTypes();
  },
  methods: {
    async loadTypes(search = '') {
      try {
        const { data } = await getTypes({ search, per_page: 100 });
        this.types = Array.isArray(data) ? data : (data.data || []);
      } catch (err) {
        console.error('Failed to load types:', err);
      }
    },
    onSearch(search) {
      if (this.searchTimeout) clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(async () => {
        await this.loadTypes(search || '');
      }, 300);
    },
  },
};
</script>
