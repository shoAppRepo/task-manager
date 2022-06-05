<template>
  <div>
    <vue-loading
      v-if="is_loading"
      type="spin"
      color="#333"
      :size="{ width: '50px', height: '50px' }"
    ></vue-loading>
    <div v-else class="container">
      <!-- 保存 -->
      <div class="text-center">
        <button class="bottom-icon btn mb-3" @click="save">保存</button>
      </div>
      <!-- 追加ボタン -->
      <button class="btn block" @click="addPeriod()">
        <span style="font-size: 20px" class="far fa-plus-square" />
      </button>

      <div class="card" v-for="(period, index) in periods">
        <div class="card-header d-flex justify-content-between">
          <div class="row">
            <datetime type="date" zone="Asia/Tokyo" v-model="period.start" />
            ~
            <datetime type="date" v-model="period.end" />
          </div>
          <div><input type="number" v-model="period.work_days" />日</div>
          <div><input type="number" v-model="period.goal_point" />pt</div>
          <button class="btn block p-0">
            <span class="far fa-trash-alt" @click="deletePeriod(index)" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { VueLoading } from "vue-loading-template";

export default {
  components: {
    VueLoading,
  },
  data() {
    return {
      is_loading: false,
      periods: [],
      delete_periods: [],
    };
  },
  created() {
    this.init();
  },
  methods: {
    init() {
      this.is_loading = true;

      axios.get("/api/period/index").then((response) => {
        this.is_loading = false;
        this.periods = response.data.periods;
      });
    },
    save() {
      this.is_loading = true;
      const periods = this.periods;
      const delete_periods = this.delete_periods;

      axios
        .post("/api/period/update", { periods, delete_periods })
        .then((response) => {
          this.is_loading = false;
          this.$toasted.success("更新しました");
          this.periods = response.data.periods;
        })
        .catch((error) => {
          this.is_loading = false;
          this.$toasted.error("更新出来ませんでした");
        });
    },
    deletePeriod(index) {
      const item = this.periods[index];
      if (!item.hasOwnProperty("is_new")) {
        this.delete_periods.push(item);
      }

      this.$delete(this.periods, index);
    },
    addPeriod() {
      const new_period = {
        is_new: true,
        start: null,
        end: null,
      };

      this.periods.push(new_period);
    },
  },
};
</script>

<style>
.bottom-icon {
  display: inline-block;
  padding: 0.5em 1em;
  text-decoration: none;
  background: #66ff99; /*ボタン色*/
  color: #fff;
  border-bottom: solid 4px #66cc66;
  border-radius: 3px;
}

.bottom-icon:active {
  /*ボタンを押したとき*/
  -webkit-transform: translateY(4px);
  transform: translateY(4px); /*下に動く*/
  box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2); /*影を小さく*/
  border-bottom: none;
}
</style>