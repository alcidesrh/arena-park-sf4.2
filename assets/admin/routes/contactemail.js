import ContactEmailList from '../components/contactemail/List.vue';
import ContactEmailCreate from '../components/contactemail/Create.vue';
import ContactEmailUpdate from '../components/contactemail/Update.vue';
import ContactEmailShow from '../components/contactemail/Show.vue';

export default [
  { name: 'ContactEmailList', path: '/admin/contact_emails/', component: ContactEmailList },
  { name: 'ContactEmailCreate', path: '/admin/contact_emails/create', component: ContactEmailCreate },
  { name: 'ContactEmailUpdate', path: "/admin/contact_emails/edit/:id", component: ContactEmailUpdate },
  { name: 'ContactEmailShow', path: "/admin/contact_emails/show/:id", component: ContactEmailShow  }
];
