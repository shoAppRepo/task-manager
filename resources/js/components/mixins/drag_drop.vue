<script>
export default {
  updated(){
    this.$nextTick(() => {
      // DOM更新直後に実行
      this.afterUpdatedForCategory();
      this.afterUpdatedForTask();
    });
  },
  methods:{
    afterUpdatedForCategory(){
      // dragItemをclassにもつelementを取得
      const drag_elements = document.querySelectorAll('.dragItem');

      // イベントリスナーをセット
      if(drag_elements.length > 0){
        [].forEach.call(drag_elements, (element) => {
          element.addEventListener('dragstart', this.handleDragStartForCategory);
          element.addEventListener('dragover', this.handleDragOverForCategory);
          element.addEventListener('drop', this.handleDropForCategory);
        });
      }

      // コンポーネント開放時にイベントリスナー解放
    },
    handleDragStartForCategory(e){
      e.dataTransfer.setData('dragItem', e.target.id);
    },
    handleDragOverForCategory(e){
      // デフォルトの挙動を無効化(必須)
      e.preventDefault();
      // ドラッグ可能状態に変更
      e.dataTransfer.effectAllowed = 'move';
    },
    handleDropForCategory(e){
      function regain(t) {
        return t.className.match(/dragItem/) ? t : regain(t.parentNode);
      }

      const drag_elemet_id = e.dataTransfer.getData('dragItem');
      const drop_element_id = regain(e.target).id;

      if(!drag_elemet_id.includes('task')){
        this.whenDropped(drag_elemet_id, drop_element_id);
      }
    },
    afterUpdatedForTask(){
      // dragTaskをclassにもつelementを取得
      const drag_elements = document.querySelectorAll('.dragTask');

      // イベントリスナーをセット
      if(drag_elements.length > 0){
        [].forEach.call(drag_elements, (element) => {
          element.addEventListener('dragstart', this.handleDragStartForTask);
          element.addEventListener('dragover', this.handleDragOverForTask);
          element.addEventListener('drop', this.handleDropForTask);
        });
      }
    },
    handleDragStartForTask(e){
      e.dataTransfer.setData('dragTask', e.target.id);
    },
    handleDragOverForTask(e){
      // デフォルトの挙動を無効化(必須)
      e.preventDefault();
      // ドラッグ可能状態に変更
      e.dataTransfer.effectAllowed = 'move';
    },
    handleDropForTask(e){
      function regain(t) {
        return t.className.match(/dragTask/) ? t : regain(t.parentNode);
      }

      const drag_elemet_id = e.dataTransfer.getData('dragTask');
      const drop_element_id = regain(e.target).id;

      if(drag_elemet_id.includes('task')){
        this.whenTaskDropped(drag_elemet_id, drop_element_id);
      }
    },
  },
}
</script>

<style>

</style>