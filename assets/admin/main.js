import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';

import CKEditor from '@ckeditor/ckeditor5-vue';

import App from './App.vue';

import homeRoute from './routes/home';

import page from './store/modules/page/';
import pageRoutes from './routes/page';

import tarif from './store/modules/tarif/';
import tarifRoutes from './routes/tarif';

import service from './store/modules/service/';
import serviceRoutes from './routes/service';

import contact from './store/modules/contact/';
import contactRoutes from './routes/contact';

import reservation from './store/modules/reservation/';
import reservationRoutes from './routes/reservation';

import user from './store/modules/user/';
import userRoutes from './routes/user';

import airport from './store/modules/airport/';
import airportRoutes from './routes/airport';

import car from './store/modules/car/';

import email from './store/modules/email/';
import emailRoutes from './routes/email';

Vue.use(Vuex);
Vue.use(VueRouter);

import es from './i18n-es/es'

Vue.use(CKEditor);
Vue.use(Vuetify, {
    lang: {
        locales: {'Es-es': es,},
        current: 'Es-es'
    }
});

const store = new Vuex.Store({
    modules: {
        page,
        service,
        tarif,
        contact,
        reservation,
        user,
        car,
        airport,
        email
    }
});

const router = new VueRouter({
    mode: 'history',
    routes: [
        ...homeRoute,
        ...pageRoutes,
        ...serviceRoutes,
        ...tarifRoutes,
        ...contactRoutes,
        ...reservationRoutes,
        ...userRoutes,
        ...airportRoutes,
        ...emailRoutes
    ]
});

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App),
});
