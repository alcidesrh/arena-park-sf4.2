import PageList from '../components/page/List.vue';
import PageCreate from '../components/page/Create.vue';
import PageUpdate from '../components/page/Update.vue';

export default [
  { name: 'PageList', path: '/admin/pages-list/', component: PageList },
  { name: 'PageCreate', path: '/admin/page/create', component: PageCreate },
  { name: 'PageUpdate', path: "/admin/page/edit/:id", component: PageUpdate }
];
