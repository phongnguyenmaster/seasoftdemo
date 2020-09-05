/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import 'jquery-ui/ui/widgets/resizable.js';

window.Vue = require('vue');
Vue.use(require('vue-moment'));

window.io = window.io = require('socket.io-client');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('chat-layout', require('./components/ChatLayoutComponent.vue').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.config.productionTip = false

const app = new Vue({
    el: '#app',
    data: {
        currentUserLogin: {}
    },
    methods: {
        getCurrentUserLogin() {
            axios.get('/getUserLogin')
                .then(response => {
                    this.currentUserLogin = response.data
                })
        },
        getUserInfo(id, callback) {
            axios.post('/getUserInfo', { 'id': id })
                .then(response => {
                    callback(response.data);
                })
        }
    }
});
