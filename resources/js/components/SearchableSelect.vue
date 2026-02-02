<template>
  <v-select
    :value="value"
    :options="options"
    :label="label"
    :reduce="reduce"
    :placeholder="placeholder"
    :disabled="disabled"
    :clearable="clearable"
    :filterable="filterable"
    @input="$emit('input', $event)"
    @search="$emit('search', $event)"
  />
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
  name: 'SearchableSelect',
  components: { vSelect },
  props: {
    value: {
      type: [String, Number, Object],
      default: null,
    },
    options: {
      type: Array,
      default: () => [],
    },
    label: {
      type: String,
      default: 'name',
    },
    reduce: {
      type: Function,
      default: null,
    },
    placeholder: {
      type: String,
      default: 'Select...',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    clearable: {
      type: Boolean,
      default: true,
    },
    filterable: {
      type: Boolean,
      default: true,
    },
  },
};
</script>
