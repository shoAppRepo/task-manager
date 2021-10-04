<template>
  <transition name="modal" appear >
    <div class="modal modal-overlay" @click.self="$emit('close')">
      <div class="modal-window">
        <div class="mx-auto mb-2">
          <div class="text-center mb-2">
            期間：
            <select v-model="selectedPeriod">
              <option disabled value=null>期間を選択してください</option>
              <option v-for="period in periods" :value="period.period_id" >{{ periodName(period) }}</option>
            </select>
          </div>
          <div class="text-center mb-2">
            タスク：
            <select v-model="item.task_id" @change="changeValue('task_id', $event.target.value)">
              <option disabled value="">タスクを選択してください</option>
              <option v-for="task in periods_tasks" :value="task.task_id">{{ task.name }}</option>
            </select>
          </div>

          <div class="row">
            <input class="col" type="text" v-model="item.title">
            <span class="mr-2">：</span>
            <datetime class="col" type="datetime" zone="Asia/Tokyo" v-model="item.start" /> 
            ~
            <datetime class="col" type="datetime" zone="Asia/Tokyo" v-model="item.end" />
          </div>
        </div>

        <!-- ボタン -->
        <div class="container">
          <div class="row justify-content-center">
            <button class="bottom-icon save-btn col-md-1 col-2" @click="$emit('save', item)">
              <span>保存</span>
            </button>

            <button
              v-if="method === 'update'"
              class="bottom-icon delete-btn col-md-1 col-2 ml-3" 
              @click="$emit('deleteItem', item)"
            >
              <span>削除</span>
            </button>
          </div>
        </div>

        

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data(){ 
    return {
      item: {},
      tasks: [],
      periods: {},
    };
  },
  props:{
    method: String,
    selected_time: Object,
    selected_item: Object,
  },
  created(){
    this.init();

    if(this.method === 'insert'){
      this.makeItem();
    }else{
      this.item = { ...this.selected_item};
    }
  },
  computed: {
    selectedPeriod: {
      get () { return this.$store.state.period.selected_period },
      set (val) { this.$store.commit('period/setSelectedPeriod', val) },
    },
    periodName(){
      return(period) => {
        const start = new Date(period.start).toLocaleDateString();
        const end = new Date(period.end).toLocaleDateString();
        const date = start + "~" + end;
        return date;
      };
    },
    periods_tasks() {
      return this.tasks.filter(task => task.period_id === this.selectedPeriod);
    },
  },
  methods:{
    init(){
      axios
        .get('/api/task/index')
        .then((response) => {
          this.tasks = response.data.tasks;
          this.periods = response.data.periods.periods;
      });
    },
    makeItem(){
      this.item = {
        'end' :this.selected_time.end,
        'title': null,
        'remark': null,
        'start': this.selected_time.start,
        'task_id': null,
      };
    },
    changeValue(column, value){
      if(column === 'task_id'){
        this.$set(this.item, column, Number(value));
        const task = this.tasks.find((task) => task.task_id === Number(value));
        this.$set(this.item, 'title', task.name);
      }
    },
  },
}
</script>

<style>
.modal-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  z-index: 5000;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.45);
}

.modal-window {
  z-index: 10000;
  margin: 1.5em auto 0;
  padding: 10px 20px;
  border: 2px solid #aaa;
  background: #fff;
  max-height: 90%;
  position: relative;
  overflow-y: auto;
}

.modal-content {
  padding: 10px 20px;
}

/* オーバーレイのトランジション */
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.4s;
}

/* ディレイを付けるとモーダルウィンドウが消えた後にオーバーレイが消える */
.modal-leave-active {
  transition: opacity 0.6s ease 0.4s;
}

.modal-enter, .modal-leave-to {
  opacity: 0;
}

.bottom-icon {
  outline:none;
  display: inline-block;
  padding: 0px;
  text-decoration: none;
  color: #FFF;
  border-radius: 3px;
}

.bottom-icon:active{
  /*ボタンを押したとき*/
  -webkit-transform: translateY(4px);
  transform: translateY(4px);/*下に動く*/
  border-bottom: none;
}

.save-btn {
  background: #66FF99;/*ボタン色*/
  border-bottom: solid 4px #66CC66;
}

.save-btn:active {
  box-shadow: 0px 0px 1px rgba(0, 0, 0, 0.2);/*影を小さく*/
}

.delete-btn{
  color: black;
  background: white;/*ボタン色*/
  border-bottom: solid 4px gray;
}

.delete-btn:active{
  box-shadow: 0px 0px 1px gray;/*影を小さく*/
}

</style>