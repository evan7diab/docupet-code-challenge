/**
 * Mixin for building pet registration payload (form data → API snake_case).
 */
export default {
  methods: {
    /**
     * Map registration form (camelCase) to API payload (snake_case).
     * @param {Object} form - Form object with typeId, name, breedId, etc.
     * @returns {Object} Payload for POST /api/pets
     */
    petPayload(form) {
      if (!form) return {};
      return {
        type_id: form.typeId,
        name: form.name,
        gender: form.gender,
        breed_id: form.breedId || null,
        breed_clarification: form.breedClarification || null,
        breed_text: form.breedText || null,
        knows_dob: form.knowsDob,
        approx_age_years: form.approxAgeYears ?? null,
        dob: form.dob || null,
      };
    },
  },
};
