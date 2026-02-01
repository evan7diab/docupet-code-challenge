<template>
  <div class="space-y-6">
    <h1 class="mb-8 text-xl font-semibold text-gray-900">Review your information</h1>

    <dl class="space-y-4">
      <slot></slot>
    </dl>

    <div class="mt-10 flex gap-4">
      <button
        type="button"
        @click="$emit('back')"
        class="flex-1 rounded-lg border border-gray-300 py-3 font-medium text-gray-700 hover:bg-gray-50"
      >
        Back
      </button>
      <button
        type="button"
        @click="$emit('save')"
        class="flex-1 rounded-lg bg-blue-600 py-3 font-medium text-white hover:opacity-90"
      >
        Save
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Review',
};
</script>
