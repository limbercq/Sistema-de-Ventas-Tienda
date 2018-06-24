
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('categoria', require('./components/Categoria.vue'));
// declarando el componente articulo
Vue.component('articulo', require('./components/Articulo.vue'));
// declarando el componente personas
Vue.component('cliente', require('./components/Cliente.vue'));
// declarando el componente proveedores
Vue.component('proveedor', require('./components/Proveedor.vue'));
// declarando el componente Rol
Vue.component('rol', require('./components/Rol.vue'));
// declarando el componente User
Vue.component('user', require('./components/User.vue'));
// declarando el componente Ingreso
Vue.component('ingreso', require('./components/Ingreso.vue'));

const app = new Vue({
    el: '#app',
    data:{
        menu:0
    }
});
