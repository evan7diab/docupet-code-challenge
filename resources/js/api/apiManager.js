import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
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

export default api;
