import './bootstrap';
import Vue from 'vue';

// Import your custom components
import ServerConsole from './components/server/Console.vue';
import ResourceGraphs from './components/server/ResourceGraphs.vue';

// Register components globally
Vue.component('server-console', ServerConsole);
Vue.component('resource-graphs', ResourceGraphs);

// Mount Vue on #app
new Vue({
    el: '#app',
});
