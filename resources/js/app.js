import './bootstrap';
import '../css/normalize.css';
import '../css/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import Notifications from '@kyvg/vue3-notification';
import { notify } from "@kyvg/vue3-notification";
import axios from "axios";
const preloader = document.querySelector("#preloader");

const app = createApp(App);
app.use(Notifications);
app.mount('#app');

axios.interceptors.request.use(function (config) {
    preloader.classList.add('active');
    if (!config.headers.ContentType) {
        config.headers.ContentType = `application/json; charset=UTF-8`;
    }
    config.headers.Accept = `application/json`;

    return config;
},  function (error) {
    preloader.classList.remove('active');

    notify({
        type: "error",
        title: "request",
        text: error.message,
    });
});

axios.interceptors.response.use(function (response) {
    preloader.classList.remove('active');

    return response;
}, function (error) {
    preloader.classList.remove('active');

    notify({
        type: "error",
        title: "response",
        text: error.message,
    });
});
