<template>
  <transition name="modal" appear >
    <div class="modal modal-overlay" @click.self="$emit('close')">
      <div class="modal-content">
        <div class="mx-auto mb-2">
          <div class="text-center mb-2">
            タスク：
            <select :value="item.task_id" @change="changeValue('task_id', $event.target.value)">
              <option value="">---</option>
              <option v-for="task in tasks" :value="task.task_id">{{ task.name }}</option>
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

        <!-- 保存 -->
        <div class="container">
          <div class="row justify-content-center">
            <button class="bottom-icon confirm-btn col-md-1 col-2" @click="$emit('save', item)">
              <span>保存</span>
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
    };
  },
  props:{
    selected_time: String,
  },
  created(){
    this.init();
    this.makeItem();
  },
  methods:{
    init(){
      axios
        .get('/api/task/index')
        .then((response) => {
          this.tasks = response.data.tasks;
      });
    },
    makeItem(){
      this.item = {
        'end' :null,
        'title': null,
        'remark': null,
        'start': this.selected_time,
        'task_id': null,
        'is_new': true,
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
  z-index: 1;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}

.modal-content {
  width:85%;
  z-index: 3;
  padding: 40px 50px;
  background:#fff;
  overflow: hidden;
  border-radius: 4px;
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
  background: #66FF99;/*ボタン色*/
  color: #FFF;
  border-bottom: solid 4px #66CC66;
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