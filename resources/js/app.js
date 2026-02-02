import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Vue from 'vue';
import MainLayout from './layouts/MainLayout.vue';
import RegistrationForm from './layouts/RegistrationForm.vue';
import Review from './layouts/Review.vue';
import SearchableSelect from './components/SearchableSelect.vue';
import TypesSearchable from './components/TypesSearchable.vue';
import BreedsSearchable from './components/BreedsSearchable.vue';

Vue.component('main-layout', MainLayout);
Vue.component('registration-form', RegistrationForm);
Vue.component('review', Review);
Vue.component('searchable-select', SearchableSelect);
Vue.component('types-searchable', TypesSearchable);
Vue.component('breeds-searchable', BreedsSearchable);

new Vue({
  el: '#app',
  template: '<main-layout />',
});
