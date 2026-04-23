require('./bootstrap');

window.Vue = require('vue');

Vue.component('notification-componet', require('./components/OrderNotifications.vue').default);
// Vue.component('multiselect-componet', require('./components/MultiSelect.vue').default);

const app = new Vue({
    el: '#app_components',
});