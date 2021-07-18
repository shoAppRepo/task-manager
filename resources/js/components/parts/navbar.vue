<template>
  <nav class="navbar navbar-expand-lg mb-2 navbar-color">
    <a class="navbar-brand white-font" href="/">工数管理</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link white-font" href="/periods">期間登録</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white-font" href="/">工数登録</a>
        </li>
        <li class="nav-item">
          <a class="nav-link white-font" href="/calendar">カレンダー</a>
        </li>
        <!-- <li>
          <a class="nav-link white-font" href="/">集計画面</a>
        </li> -->
        <li>
          <a class="nav-link white-font" href="/logout" @click.prevent="logout">ログアウト</a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  methods:{
    logout() {
      axios
        .post('/logout')
        .then((response) => {
          this.$store.commit('period/setSelectedPeriod');
          window.location.reload();
        });
    },
  }
}
</script>

<style>
.navbar-color{
  background-color: #66FF99;
  color:white;
}

.white-font{
  color:white;
}

.navbar-toggler{
  border-color: #ffffff;
}

.navbar-toggler .navbar-toggler-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
}
</style>