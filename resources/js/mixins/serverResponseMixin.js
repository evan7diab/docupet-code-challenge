/**
 * Mixin for handling API / server responses (success and error parsing).
 */
export default {
  methods: {
    /**
     * Format an axios error into a user-facing message string.
     * Handles Laravel validation errors (422), server errors (5xx), and network errors.
     * @param {Error} err - Axios error (err.response may be set)
     * @returns {string}
     */
    formatApiError(err) {
      const res = err.response;
      if (!res) return err.message || 'Network or unknown error.';
      const data = res.data;
      if (data?.errors && typeof data.errors === 'object') {
        const parts = [];
        Object.keys(data.errors).forEach((field) => {
          const messages = data.errors[field];
          const list = Array.isArray(messages) ? messages.join(' ') : String(messages);
          parts.push(`${field}: ${list}`);
        });
        return parts.join('. ');
      }
      if (res.status === 503) {
        return data?.message === 'API key not configured.'
          ? 'Service is not configured. Please contact the administrator.'
          : (data?.message || 'Service temporarily unavailable. Please try again later.');
      }
      if (data?.message) return data.message;
      if (res.status === 422) return 'Please check your input and try again.';
      if (res.status >= 500) return 'Server error. Please try again later.';
      return err.message || 'Something went wrong.';
    },

    /**
     * Extract success message from API response data.
     * @param {Object} data - response.data from axios
     * @param {string} [defaultMessage] - fallback if data.message is missing
     * @returns {string}
     */
    getSuccessMessage(data, defaultMessage = 'Request completed successfully.') {
      return (data && data.message) ? data.message : defaultMessage;
    },
  },
};
