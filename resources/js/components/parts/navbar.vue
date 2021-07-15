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
        <li>
          <a class="nav-link white-font" href="/">集計画面</a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {

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
</style>