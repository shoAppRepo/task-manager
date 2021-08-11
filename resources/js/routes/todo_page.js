const todo_page = () =>
  import('../components/test/todo_page.vue');

export default [
  {
    path: '/',
    name: 'todo.page',
    component: todo_page,
  },
];
