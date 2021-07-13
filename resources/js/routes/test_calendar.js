const test_calendar = () =>
  import('../components/test/test_calendar.vue');

export default [
  {
    path: '/calendar',
    name: 'test.calendar',
    component: test_calendar,
  },
];
