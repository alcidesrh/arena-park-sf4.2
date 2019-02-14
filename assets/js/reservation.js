

import Vue from 'vue';
import Vuetify from 'vuetify';
import Reservation from './reservation.vue';
Vue.use(Vuetify);

// import es from "../admin/i18n-es/es";

new Vue({
    el: '#reservation',
    render: h => h(Reservation),
});



