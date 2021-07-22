const aggregation_page = () =>
  import('../components/test/aggregation_page.vue');

export default [
  {
    path: '/aggregation',
    name: 'aggregation.page',
    component: aggregation_page,
  },
];
