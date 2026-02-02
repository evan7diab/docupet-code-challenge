import axios from 'axios';

function getApiKey() {
  const meta = document.querySelector('meta[name="api-key"]');
  return (meta && meta.getAttribute('content')) || '';
}

const apiKey = getApiKey();
const headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'X-Requested-With': 'XMLHttpRequest',
};
if (apiKey) {
  headers['X-API-Key'] = apiKey;
}

const api = axios.create({
  baseURL: '/api',
  headers,
});

/**
 * Fetch types with optional search and pagination.
 * @param {Object} params - { search: string, per_page: number }
 * @returns {Promise}
 */
export function getTypes(params = {}) {
  return api.get('/types', { params });
}

/**
 * Fetch breeds with optional search, type filter, and pagination.
 * @param {Object} params - { search: string, type_id: number, per_page: number }
 * @returns {Promise}
 */
export function getBreeds(params = {}) {
  return api.get('/breeds', { params });
}

/**
 * Save pet registration.
 * @param {Object} data - { type_id, name, gender, breed_id?, breed_clarification?, breed_text?, knows_dob, approx_age_years?, dob? }
 * @returns {Promise}
 */
export function savePet(data) {
  return api.post('/pets', data);
}

export default api;
