import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Vue from 'vue';
import MainLayout from './layouts/MainLayout.vue';
import RegistrationForm from './layouts/RegistrationForm.vue';
import Review from './layouts/Review.vue';

Vue.component('main-layout', MainLayout);
Vue.component('registration-form', RegistrationForm);
Vue.component('review', Review);

new Vue({
  el: '#app',
  template: `
    <main-layout :current-step="currentStep">
      <registration-form />
    </main-layout>
  `,
  data: {
    currentStep: 1,
  },
});
