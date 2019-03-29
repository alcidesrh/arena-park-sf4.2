

import Vue from 'vue';
import Vuetify from 'vuetify';
import Contact from './contact.vue';
Vue.use(Vuetify);

// import es from "../admin/i18n-es/es";

new Vue({
    el: '#contact',
    render: h => h(Contact),
});



