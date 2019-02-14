import ServiceList from '../components/service/List.vue';
import ServiceCreate from '../components/service/Create.vue';
import ServiceUpdate from '../components/service/Update.vue';
import ServiceShow from '../components/service/Show.vue';

export default [
  { name: 'ServiceList', path: '/admin/service/', component: ServiceList },
  { name: 'ServiceCreate', path: '/admin/service/create', component: ServiceCreate },
  { name: 'ServiceUpdate', path: "/admin/service/edit/:id", component: ServiceUpdate },
  { name: 'ServiceShow', path: "/admin/service/show/:id", component: ServiceShow  }
];
