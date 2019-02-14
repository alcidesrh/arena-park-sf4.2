import UserList from '../components/user/List.vue';
import UserCreate from '../components/user/Create.vue';
import UserUpdate from '../components/user/Update.vue';
import UserShow from '../components/user/Show.vue';

export default [
  { name: 'UserList', path: '/admin/users/', component: UserList },
  { name: 'UserCreate', path: '/admin/users/create', component: UserCreate },
  { name: 'UserUpdate', path: "/admin/users/edit/:id", component: UserUpdate },
  { name: 'UserShow', path: "/admin/users/show/:id", component: UserShow  }
];
