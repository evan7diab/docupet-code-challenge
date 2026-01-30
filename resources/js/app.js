import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Vue from 'vue';
import FirstVueComponent from './components/FirstVueComponent.vue';

Vue.component('first-vue-component', FirstVueComponent);

new Vue({
  el: '#app',
});
