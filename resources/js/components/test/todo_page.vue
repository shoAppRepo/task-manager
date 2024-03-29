<template>
  <div class="body">
    <vue-loading v-if="is_loading" type="spin" color="#333" :size="{ width: '50px', height: '50px' }"></vue-loading>
    <div v-else>
      <div class="container">
        <div class="row mb-4 justify-content-between">
          <!-- 保存 -->
          <button class="bottom-icon btn col-md-1 col-2" @click="save">
            保存
          </button>
        </div>
        <div class="row">
          <div
          v-for="(item, itemIndex) in items"
          class="col-sm-4 mb-4"
        >
          <div
            :id="'drag-'+item.category_id" 
            class="card dragItem"
            draggable="true"
          >
            <div class="card-header category-header">
              <div class="row">
                <div class="col">
                  <textarea style="width:100%" type="text" :value="item.name" @change="changeCategoryName(itemIndex, $event.target.value)"></textarea>
                </div>
                <button class="btn block p-0 col-1">
                  <span class="far fa-trash-alt ml-2" @click="deleteCategory(itemIndex)"/>
                </button>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="text-left mb-3">
                <div>{{ countCategoryTasks(item) }}件</div>
              </div>

              <div 
                v-for="(task, taskIndex) in item.tasks"
                class="task-content mb-3"
              >
                <div class="container dragTask" draggable="true" :id="'task-' + item.category_id + '-' + task.task_id">
                  <div class="row">
                    <textarea type="text" class="title-textarea col p-0" :value="task.name" @change="changeValue('name', itemIndex, taskIndex, $event.target.value)"></textarea>
                    <div>
                      <div>
                        <button class="btn block">
                          <span class="far fa-trash-alt ml-2" @click="deleteTask(itemIndex, taskIndex)"/>
                        </button>
                      </div>
                      <div>
                        <button class="btn block">
                          <span class="fas fa-pencil-alt ml-2" @click="openModalToDo(itemIndex, taskIndex)"/>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 追加ボタン -->
              <button 
                class="btn block"
                @click="addTask(item, itemIndex)"
              >
                <span style="font-size:20px;" class="far fa-plus-square"/>
              </button>
            </div>
          </div>
        </div>
        </div>
        <!-- 追加ボタン -->
        <button 
          class="btn block"
          @click="addCategory()"
        >
          <span style="font-size:20px;" class="far fa-plus-square"/>
        </button>

      </div>
    </div>

    <modal_toDo 
      v-if="is_modal_open"
      :selected_task="selected_task"
      @confirm="confirm($event)"
      @close="closeModalToDo()"
    />

  </div>
</template>

<script>
import modal_toDo from '../parts/modal_toDo';
import drag_drop from '../mixins/drag_drop';
import { mapState } from 'vuex';
import { VueLoading } from 'vue-loading-template'

export default {
  mixins:[drag_drop],
  components:{
    modal_toDo,
    VueLoading,
  },
  data(){
    return {
      is_loading: false,
      is_modal_open: false,
      selected_index: {},
      selected_task: {},
      items: {},
      task: {},
      delete_categories: [],
      delete_tasks: [],
    };
  },
  created(){
    this.init();
  },
  computed:{
    nextSort(){
      const sort_length = this.items.length;
      return sort_length + 1;
    },
  },
  methods:{
    init(){
      this.is_loading = true;

      axios
        .get('/api/todo/index')
        .then((response) => {
          this.items = response.data.items;
          this.is_loading = false;
        });

    },
    save(){
      this.is_loading = true;
      const items = this.items;
      const delete_tasks = this.delete_tasks;
      const delete_categories = this.delete_categories;

      axios
        .post('/api/todo/update', {items, delete_categories, delete_tasks})
        .then((response) => {
          this.is_loading = false;
          this.$toasted.success('更新しました');
          this.items = response.data.items;
          this.delete_tasks = [];
          this.delete_categories = [];
        }).catch((error) =>{
          this.is_loading = false;
          this.$toasted.error('更新出来ませんでした');
      });
    },
    countCategoryTasks(item){
      return item.tasks.length;
    },
    addTask(item, itemIndex){
      const category_id = item.category_id;
      const new_task = {
        'category_id': category_id,
        'name': '',
        'is_new': true,
        'task_id': null,
      };

      this.items[itemIndex]['tasks'].push(new_task);
    },
    addCategory(){
      const new_category = {
        'category_id': null,
        'name': '',
        'sort': this.nextSort,
        'tasks': [],
        'is_new': true,
      };

      this.items.push(new_category);
    },
    deleteCategory(itemIndex){
      const item = this.items[itemIndex];
      if(!item.hasOwnProperty('is_new')){
        this.delete_categories.push(item);
      }

      this.$delete(this.items, itemIndex);
    },
    deleteTask(itemIndex, taskIndex){
      const item = this.items[itemIndex]['tasks'][taskIndex];
      if(!item.hasOwnProperty('is_new')){
        this.delete_tasks.push(item);
      }

      this.$delete(this.items[itemIndex]['tasks'], taskIndex);
    },
    changeValue(column, itemIndex, taskIndex, value){
      this.$set(this.items[itemIndex]['tasks'][taskIndex], column, value);
    },
    changeCategoryName(itemIndex, value){
      this.$set(this.items[itemIndex], 'name', value);
    },
    confirm(item){
      const itemIndex = this.selected_index['item'];
      const taskIndex = this.selected_index['task'];
      this.$set(this.items[itemIndex]['tasks'], taskIndex, item); 
    },
    openModalToDo(itemIndex, taskIndex){
      this.is_modal_open = true;
      this.selected_index = {'item':itemIndex, 'task': taskIndex};
      this.selected_task = this.items[itemIndex]['tasks'][taskIndex];
    },
    closeModalToDo(){
      this.is_modal_open = false;
    },
    findCategryIndex(id){
      return this.items.findIndex((item) => item.category_id === id);
    },
    whenDropped(drag_elemet_id, drop_element_id){
      const drag_element = document.querySelector('#' + drag_elemet_id);
      const drop_element = document.querySelector('#' + drop_element_id);

      if(drag_element.className === drop_element.className){
        const drag_id = Number(drag_elemet_id.split('-')[1]);
        const drop_id = Number(drop_element_id.split('-')[1]);
        const drag_item_index = this.findCategryIndex(drag_id);
        const drop_item_index = this.findCategryIndex(drop_id);
        let sort_number = 0;
        // ソート番号変更
        if(this.items[drag_item_index]['sort'] > this.items[drop_item_index]['sort']){
          sort_number = this.items[drop_item_index]['sort'] - 0.5;
        }else if(this.items[drop_item_index]['sort'] > this.items[drag_item_index]['sort']){
          sort_number = this.items[drop_item_index]['sort'] + 0.5
        }
        this.$set(this.items[drag_item_index], 'sort' , sort_number)
  
        // sort順に並び替え
        this.sortItems();
        // 全体のsort番号振り直し
        this.sortNumbering();
      }
      
    },
    sortItems(){
      this.items.sort((a, b) => a.sort - b.sort);
    },
    sortNumbering(){
      for(let i = 0; i < this.items.length; i++){
        this.items[i]['sort'] = i + 1;
      }
    },
    findTask(category_index, task_id){
      return this.items[category_index]['tasks'].findIndex((task) => task.task_id === task_id);
    },
    sortTask(category_index){
      this.items[category_index]['tasks'].sort((a, b) => a.sort - b.sort);
    },
    taskSortNumbering(category_index){
      for(let i = 0; i < this.items[category_index]['tasks'].length; i++){
        this.items[category_index]['tasks'][i]['sort'] = i + 1;
      }
    },
    whenTaskDropped(drag_elemet_id, drop_element_id){
      const drag_element = document.querySelector('#' + drag_elemet_id);
      const drop_element = document.querySelector('#' + drop_element_id);

      if(drag_element.className === drop_element.className){
        const drag_category_id = Number(drag_elemet_id.split('-')[1]);
        const drop_category_id = Number(drop_element_id.split('-')[1]);
        const drag_category_item = this.findCategryIndex(drag_category_id);
        const drop_category_item = this.findCategryIndex(drop_category_id);
        const drag_id = Number(drag_elemet_id.split('-')[2]);
        const drop_id = Number(drop_element_id.split('-')[2]);
        let drag_task = this.findTask(drag_category_item, drag_id);
        const drop_task = this.findTask(drop_category_item, drop_id);

        // 異なるカテゴリーにドロップされた場合、移動元から削除し移動先に追加
        if(drag_category_id !== drop_category_id){
          this.items[drag_category_item]['tasks'][drag_task]['category_id'] = drop_category_id;
          this.items[drop_category_item]['tasks'].push(this.items[drag_category_item]['tasks'][drag_task]);
          this.$delete(this.items[drag_category_item]['tasks'], drag_task);
          drag_task = this.items[drop_category_item]['tasks'].length - 1;
        }

        // sort番号セット
        if(this.items[drop_category_item]['tasks'][drag_task]['sort'] > this.items[drop_category_item]['tasks'][drop_task]['sort']){
          this.items[drop_category_item]['tasks'][drag_task]['sort'] = this.items[drop_category_item]['tasks'][drop_task]['sort'] - 0.5;
        }else if(this.items[drop_category_item]['tasks'][drag_task]['sort'] < this.items[drop_category_item]['tasks'][drop_task]['sort']){
          this.items[drop_category_item]['tasks'][drag_task]['sort'] = this.items[drop_category_item]['tasks'][drop_task]['sort'] + 0.5;
        }

        // タスクのcategory_idからカテゴリーのitemを取得し、その中のtasksの順番を変更
        this.sortTask(drop_category_item);
        // sort番号振り直し　
        this.taskSortNumbering(drop_category_item);
      }
    },
  },
}
</script>

<style>

.bottom-icon {
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

.total-task-hour{
  text-align: start;
  cursor: pointer;
}

.title-textarea {
  outline:none;
}

.task-content{
  border-radius: 5%;
}
</style>