/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import swal from 'sweetalert'
import Vue from 'vue'
import VueSuggestion from 'vue-suggestion'

import { Form, HasError, AlertError } from 'vform'

import JsonExcel from 'vue-json-excel'

import * as firebase from 'firebase/app'
import 'firebase/firestore';

Vue.use(VueSuggestion)

const firebaseConfig = {
    apiKey: "AIzaSyDKhw55SP9oMwUm8wHuHHiYLps_buf-CHA",
    authDomain: "login1-b79c2.firebaseapp.com",
    databaseURL: "https://login1-b79c2.firebaseio.com",
    projectId: "login1-b79c2",
    storageBucket: "login1-b79c2.appspot.com",
    messagingSenderId: "346076197050",
    appId: "1:346076197050:web:d46106a801091eac7c5283",
    measurementId: "G-4KVLWGKV8N"
};

firebase.initializeApp(firebaseConfig);
export const db = firebase.firestore();
export const fb_aux = firebase;

Vue.component('downloadExcel', JsonExcel)

Vue.use(VueRouter)

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    // { path: '/contenedores', component: require('./components/Contenedores.vue').default },
 //   { path: '/especies', component: require('./components/Especies.vue').default },
  //  { path: '/alimentos', component: require('./components/Alimentos.vue').default },
    { path: '/recursos', component: require('./components/Recursos.vue').default },
    { path: '/usuarios', component: require('./components/Usuarios.vue').default },
  //  { path: '/siembras', component: require('./components/Siembras.vue').default },
    { path: '/recursos-necesarios', component: require('./components/RecursosNecesarios.vue').default },
    { path: '/informes', component: require('./components/Informes.vue').default },
  //  { path: '/alimentacion', component: require('./components/Alimentacion.vue').default },
 //   { path: '/ciclo-productivo', component: require('./components/InformeCicloProductivo.vue').default },
 //   { path: '/calidad-agua', component: require('./components/CalidadAgua.vue').default },
    { path: '/example', component: require('./components/ExampleComponent.vue').default },

  //  { path: '/sedes', component: require('./components/Sedes.vue').default },
  //  { path: '/torneos', component: require('./components/Torneos.vue').default },
  //  { path: '/jugadores', component: require('./components/Jugadores.vue').default },
    { path: '/preguntas', component: require('./components/Preguntas.vue').default },

]

const router = new VueRouter({

        routes // short for `routes: routes`
    })
    /**
     * The following block of code may be used to automatically register your
     * Vue components. It will recursively scan this directory for the Vue
     * components and automatically register them with their "basename".
     *
     * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
     */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Vue.component('dashboard-component', require('./components/Dashboard.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});