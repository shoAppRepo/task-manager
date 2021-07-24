<script>
export default {
  updated(){
    this.$nextTick(() => {
      // DOM更新直後に実行
      this.afterUpdated();
    });
  },
  methods:{
    afterUpdated(){
      // dragItemをclassにもつelementを取得
      const drag_elements = document.querySelectorAll('.dragItem');

      // イベントリスナーをセット
      if(drag_elements.length > 0){
        [].forEach.call(drag_elements, (element) => {
          element.addEventListener('dragstart', this.handleDragStart);
          element.addEventListener('dragover', this.handleDragOver);
          element.addEventListener('drop', this.handleDrop);
        });
      }

      // コンポーネント開放時にイベントリスナー解放
    },
    handleDragStart(e){
      e.dataTransfer.setData('dragitem', e.target.id);
    },
    handleDragOver(e){
      // デフォルトの挙動を無効化(必須)
      e.preventDefault();
      // ドラッグ可能状態に変更
      e.dataTransfer.effectAllowed = 'move';
    },
    handleDrop(e){
      function regain(t) {
        return t.className.match(/dragItem/) ? t : regain(t.parentNode);
      }

      const drag_elemet_id = e.dataTransfer.getData('dragItem');
      const drop_element_id = regain(e.target).id;

      const drag_id = drag_elemet_id.split('-')[1];
      const drop_id = drop_element_id.split('-')[1];

      this.whenDropped(Number(drag_id), Number(drop_id));
    },
  },
}
</script>

<style>

</style>