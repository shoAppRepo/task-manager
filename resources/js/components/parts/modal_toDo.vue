<template>
  <transition name="modal" appear >
    <div class="modal modal-overlay" @click.self="$emit('close')">
      <div class="modal-content">
        
        <textarea v-model="item.remark"></textarea>

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

export default {
  data(){
    return {
      item: {},
    };
  },
  props:{
    selected_task: Object,
  },
  created(){
    this.item = JSON.parse(JSON.stringify(this.selected_task));
  },
  methods:{
    confirm(){
      this.$emit('confirm', this.item);
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
.close-button{
  position: absolute;
  top: 0px;
  right: 0px;
  border-radius: 3px;
  background-color: red;
  color: white;
  padding: 2px 10px;
  font-size: 20px;
}

.close-button:hover{
  color: white;
}

.confirm-btn{
  outline:none;
}

</style>