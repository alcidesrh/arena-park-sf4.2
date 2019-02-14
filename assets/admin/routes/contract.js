import ContractList from '../components/contract/List.vue';
import ContractCreate from '../components/contract/Create.vue';
import ContractUpdate from '../components/contract/Update.vue';
import ContractShow from '../components/contract/Show.vue';

export default [
  { name: 'ContractList', path: '/contracts/', component: ContractList },
  { name: 'ContractCreate', path: '/contracts/create', component: ContractCreate },
  { name: 'ContractUpdate', path: "/contracts/edit/:id", component: ContractUpdate },
  { name: 'ContractShow', path: "/contracts/show/:id", component: ContractShow  }
];
