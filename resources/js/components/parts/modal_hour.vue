<template>
  <transition name="modal" appear >
    <div class="modal modal-overlay" @click.self="$emit('close')">

      <div class="modal-content">
        <div v-for="(item, index) in items" class="row mb-2 mx-auto">
          <input type="text" v-model="item.title">
          <span class="mr-2">：</span>
          <datetime type="datetime" zone="Asia/Tokyo" v-model="item.start" /> 
          ~
          <datetime type="datetime" zone="Asia/Tokyo" v-model="item.end" />
          <button class="btn block p-0">
            <span class="far fa-trash-alt ml-1" @click="deleteHour(index)"/>
          </button>
        </div>

        <!-- 追加ボタン -->
        <button 
          class="btn add-button mb-3"
          @click="addHour()"
        >
          <span style="font-size:30px;" class="far fa-plus-square"/>
        </button>

        <!-- 閉じる -->
        <button class="close-button btn" @click="$emit('close')">
          <span>×</span>
        </button>

        <!-- 決定 -->
        <div class="container">
          <div class="row justify-content-center">
            <button class="bottom-icon confirm-btn col-md-1 col-2" @click="confirm()">
              <span>決定</span>
            </button>
          </div>
        </div>
      </div>

    </div>
  </transition>
</template>

<script>
import moment from "moment";

export default {
  data(){
    return {
      items: [],
      delete_items: [],
    };
  },
  props:{
    task: Object,
    selected_task: Array,
  },
  created(){
    this.items = JSON.parse(JSON.stringify(this.selected_task));
  },
  methods:{
    now(){
      return moment().format();
    },
    addHour(){
      const task_id = (!this.task.hasOwnProperty('is_new'))? this.task.task_id: null;
      const new_hour = {
        'end' :null,
        'title': this.task.name,
        'remark': null,
        'start': this.now(),
        'task_id': task_id,
        'is_new': true,
      };

      this.items.push(new_hour);
    },
    deleteHour(index){
      const item = this.items[index];

      if(!item.hasOwnProperty('is_new')){
        this.delete_items.push(item);
      }

      this.$delete(this.items, index);
    },
    confirm(){
      this.$emit('confirm', {items:this.items, delete_hours:this.delete_items});
      this.$emit('close');
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

.confirm-btn{
  outline:none;
}

</style>