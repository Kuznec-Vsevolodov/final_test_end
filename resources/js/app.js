/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('general-component', require('./components/GeneralComponent.vue').default);
Vue.component('chat-component', require('./components/inside-components/main-section-components/ChatComponent.vue').default);
Vue.component('invite-component', require('./components/inside-components/main-section-components/InviteComponent.vue').default);
Vue.component('account-component', require('./components/inside-components/sidebar-components/AccountComponent.vue').default);
Vue.component('toplist-component', require('./components/inside-components/sidebar-components/TopListComponent.vue').default);
Vue.component('sidebar-component', require('./components/inside-components/SidebarComponent').default);
Vue.component('empty-sidebar-component', require('./components/inside-components/EmptySidebarComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
