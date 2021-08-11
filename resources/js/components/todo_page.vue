<template>
  <div class="body">
    <navbar />
    <div>
      <h3 class="title">ToDoリスト</h3>
    </div>
    <router-view />
  </div>
</template>

<script>
import navbar from './parts/navbar.vue';

export default {
  components:{
    navbar,
  },
};
</script>

<style >

</style>
