import { createRouter, createWebHistory } from 'vue-router';
import SpreadSheet from './components/Spreadsheet.vue'
import Shuffle from './components/Shuffle.vue';
import Kentei from './components/Kentei.vue';
import ShuffleKentei from './components/ShuffleKentei.vue';
import Create from './components/Create.vue';
import Edit from './components/Edit.vue';

const routes = [
  {
    path: '/',
    redirect: '/spreadSheet'
  },
  {
    path: '/spreadSheet',
    name: 'SpreadSheet',
    component: SpreadSheet
  },
  {
    path: '/shuffle', 
    name: 'Shuffle',
    component: Shuffle
  },
  {
    path: '/kentei',
    name: 'Kentei',
    component: Kentei
  },
  {
    path: '/shuffleKentei', 
    name: 'ShuffleKentei',
    component: ShuffleKentei
  },
  {
    path: '/create',
    name: 'Create',
    component: Create
  },
  {
    path: '/edit/:id',
    name: 'Edit',
    component: Edit
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
