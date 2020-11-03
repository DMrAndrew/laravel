/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';

window.Vue.use(VueRouter);
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
import indexIngredients from './components/ingredients/indexIngredients';
import indexMedicaments from './components/medicaments/index';
import indexUser from './components/User/index';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const routes = [
    {
        path: '/indexIngredients',
        name:'indexIngredients',
        component:indexIngredients,
    },
    {
        path: '/indexMedicaments',
        name:'indexMedicaments',
        component:indexMedicaments,
    },
    {
        path: '/indexUser',
        name:'indexUser',
        component:indexUser,
    },


]

const router = new VueRouter({
    mode: 'history',
    routes
})

const app = new Vue({
    router,
    el: '#app',
});
