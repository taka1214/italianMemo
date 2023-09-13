import './bootstrap';
import { createApp } from 'vue';
import '../sass/app.scss';
import router from "./index";



const app = createApp({});

// import ExampleComponent from './components/ExampleComponent.vue';
import App from './components/App.vue';
app.component('app', App);

app.use(router);
app.mount('#vue');
