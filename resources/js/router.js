import Vue from 'vue';
import VueRouter from 'vue-router';

import test_calendar_route from './routes/test_calendar';
import test_calendar from './components/test_calendar.vue';

import category_page_route from './routes/category_page';
import category_page from './components/category_page.vue';

import period_page_route from './routes/period_page';
import period_page from './components/period_page.vue';


Vue.use(VueRouter);

const router = new VueRouter({
	mode: 'history',
	routes: [
    {
      path: '/',
      component: category_page,
      children: category_page_route,
    },
    {
      path: '/calender',
      component: test_calendar,
      children: test_calendar_route,
    },
    {
      path: '/periods',
      component: period_page,
      children: period_page_route,
    },
  ],
});

export default router;