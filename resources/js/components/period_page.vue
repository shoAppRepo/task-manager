<template>
  <div>
    <navbar />
    <div>
      <h3>期間登録</h3>
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
