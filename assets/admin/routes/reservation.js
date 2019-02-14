import ReservationList from '../components/reservation/List.vue';
import ReservationCreate from '../components/reservation/Create.vue';
import ReservationUpdate from '../components/reservation/Update.vue';
import ReservationShow from '../components/reservation/Show.vue';

export default [
  { name: 'ReservationList', path: '/admin/reservations/', component: ReservationList },
  { name: 'ReservationCreate', path: '/admin/reservations/create', component: ReservationCreate },
  { name: 'ReservationUpdate', path: "/admin/reservations/edit/:id", component: ReservationUpdate },
  { name: 'ReservationShow', path: "/admin/reservations/show/:id", component: ReservationShow  }
];
