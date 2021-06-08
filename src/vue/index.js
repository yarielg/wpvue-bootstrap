import Vue from 'vue'
import App from './App.vue';

import vuetify from './plugin/vuetify.js'
import Badges from "./Badges.vue"; // path to vuetify export
import NewBadge from "./NewBadge.vue";

Vue.component('badges', Badges)
Vue.component('new-badge', NewBadge)

new Vue({
    el: '#vwp-plugin',
    vuetify,
    render: h => h(App)
});

