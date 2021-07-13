<template>
  <div class="container">
    <!-- 保存 -->
    <div class="text-center"> 
      <button class="bottom-icon btn mb-3" @click="save">
        保存
      </button>
    </div>

    <div class="card" v-for="(period, index) in periods">
      <div class="card-header d-flex justify-content-between">
        <div class="row">
          <datetime type="date" zone="Asia/Tokyo" v-model="period.start" /> 
          ~
          <datetime type="date" v-model="period.end" />
        </div>
        <button class="btn block p-0">
          <span class="fas fa-trash-alt" @click="deletePeriod(index)"/>
        </button>
      </div>
    </div>

    <!-- 追加ボタン -->
    <button 
      class="btn block"
      @click="addPeriod()"
    >
      <span style="font-size:30px;" class="fas fa-plus-circle"/>
    </button>
  </div>
</template>

<script>
export default {
  data(){
    return{
      periods: [],
      delete_periods: [],
    };
  },
  created(){
    this.init();
  },
  methods:{
    init(){
      axios
        .get('/api/period/index')
        .then((response) => {
          this.periods = response.data.periods;
      });
    },
    save(){
      const periods = this.periods;
      const delete_periods = this.delete_periods;

      axios
        .post('/api/period/update', {periods, delete_periods})
        .then((response) => {
          this.$toasted.success('更新しました');
          this.periods = response.data.periods;
      }).catch((error) =>{
        this.$toasted.error('更新出来ませんでした');
      });
    },  
    deletePeriod(index){
      const item = this.periods[index];
      if(!item.hasOwnProperty('is_new')){
        this.delete_periods.push(item);
      }

      this.$delete(this.periods, index);
    },
    addPeriod(){
      const new_period = {
        'is_new': true,
        'start': null,
        'end': null,
      };

      this.periods.push(new_period);
    },
  },
}
</script>

<style>
.bottom-icon {
  display: inline-block;
  padding: 0.5em 1em;
  text-decoration: none;
  background: #668ad8;/*ボタン色*/
  color: #FFF;
  border-bottom: solid 4px #627295;
  border-radius: 3px;
}

.bottom-icon:active{
  /*ボタンを押したとき*/
  -webkit-transform: translateY(4px);
  transform: translateY(4px);/*下に動く*/
  box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
  border-bottom: none;
}
</style>