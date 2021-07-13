<template>
  <div class="card">
    <div class="card-header">{{header_name}}</div>
    <div class="card-body">
      <div v-for="main_item in main_items">
        <div class="card">
          <div class="card-header">{{main_item.name}}</div>
          <div class="card-body">
            <div v-for="(sub_item) in sub_items">
              <div class="card">
                <div class="card-header">{{sub_item.name}}</div>
                <div class="card-body">
                  <div>
                    {{  }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){

  },
  props:{
    header_name: String,
    main_items: [Object, Array],
    sub_items: [Object, Array],
  },
}
</script>

<style>

</style>