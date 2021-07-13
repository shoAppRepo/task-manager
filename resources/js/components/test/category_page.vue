<template>
  <div>
    <div class="container">
      <div class="d-flex mb-3 justify-content-between">
        <div class="d-flex">
          <div class="d-flex align-items-center">期間選択：</div>
          <select v-model="selectedPeriod" @change="getItems">
            <option value="">---</option>
            <option v-for="period in periods" :value="period.period_id" >{{ periodName(period) }}</option>
          </select>
        </div>
        <!-- 保存 -->
        <div class="text-center"> 
          <button class="bottom-icon save-icon btn" @click="save">
            保存
          </button>
        </div>
      </div>
      <div
        v-for="(item, itemIndex) in items"
        class="mb-3"
      >
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <input style="width:60%" type="text" :value="item.name" @change="changeCategoryName(itemIndex, $event.target.value)"/>
            <span>{{ countCategoryTasks(item) }}件：{{ totalCategoryHours(item)}}
              <button class="btn block p-0">
                <span class="fas fa-trash-alt ml-2" @click="deleteCategory(itemIndex)"/>
              </button>
            </span>
          </div>
          <div class="card-body">
            <div class="tasks">
              <div 
                v-for="(task, taskIndex) in item.tasks"
                class="card mb-2"
              >
                
                <div 
                  class="card-header d-flex justify-content-between"
                >
                  <input type="text" style="width:60%" :value="task.task.name" @change="changeValue('name', itemIndex, taskIndex, $event.target.value)">
                  <span class="total-task-hour" @click="openModalHour(itemIndex, taskIndex)">{{ totalTaskHours(task.task) }}
                    <button class="btn block p-0">
                      <span class="fas fa-trash-alt ml-2" @click="deleteTask(itemIndex, taskIndex)"/>
                    </button>
                  </span>
                </div>

              </div>
            </div>

            <!-- 追加ボタン -->
            <button 
              class="btn block"
              @click="addTask(item, itemIndex)"
            >
              <span style="font-size:30px;" class="fas fa-plus-circle"/>
            </button>
          </div>
        </div>
      </div>

      <!-- 追加ボタン -->
      <button 
        class="btn block"
        @click="addCategory()"
      >
        <span style="font-size:30px;" class="fas fa-plus-circle"/>
      </button>

    </div>

    <modal_hour 
      v-if="is_modal_open"
      :task="task"
      :selected_task="selected_task"
      @confirm="confirm($event)"
      @close="closeModalHour()"
    />
  </div>
</template>

<script>
import modal_hour from '../parts/modal_hour';
import { mapState } from 'vuex';

export default {
  components:{
    modal_hour,
  },
  data(){
    return {
      is_modal_open: false,
      selected_task: {},
      selected_index: {},
      items: {},
      periods: {},
      task: {},
      delete_categories: [],
      delete_tasks: [],
      delete_hours: [],
    };
  },
  created(){
    this.init();
  },
  computed:{
    ...mapState({
      selected_period: (state) => state.period.selected_period,
    }),
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
    selectedItems(){
      return this.items;
    },
    totalTaskHours(){
      return (task) => {
        const hours = this.calcTaskHours(task);

        let h = Math.floor(hours / 3600000);//時間
        let m = Math.floor((hours - h * 3600000) / 60000);//分
        let s = Math.round((hours - h * 3600000 - m * 60000) / 1000);//秒

        return h + "時間" + m + "分" + s + "秒";//1時間0分60秒
      };
    },
    totalCategoryHours(){
      return (task) => {
        const hours = this.calcCategoryHours(task);

        let h = Math.floor(hours / 3600000);//時間
        let m = Math.floor((hours - h * 3600000) / 60000);//分
        let s = Math.round((hours - h * 3600000 - m * 60000) / 1000);//秒

        return h + "時間" + m + "分" + s + "秒";//1時間0分60秒
      };
    },
    calcCategoryHours(){
      return(item) => {
        let total_category_hours = 0;
        item['tasks'].forEach((task) => {
          const hour = this.calcTaskHours(task.task);
          total_category_hours += hour;
        });

        return total_category_hours;
      };
    },
    calcTaskHours(){
      return(task)=>{
        let total_task_hours = 0;

        Object.keys(task.manhours).forEach((key) => {
          const manhour = task.manhours[key];
          const start = manhour.start;
          const end = manhour.end;
          const start_date = new Date(String(start));
          const end_date = new Date(String(end));
          const diff_time =  (end_date - start_date);
          
          total_task_hours += diff_time;
        });

        return total_task_hours;
      };
    },
  },
  methods:{
    init(){
      let period_id = null;
      if(this.$store.state.period.selected_period){
        period_id = this.$store.state.period.selected_period;
      }

      axios
        .get('/api/period/' + period_id + '/indexWithItems')
        .then((response) => {
          this.periods = response.data.periods;
          this.items = response.data.items;
        });
    },
    save(){
      const items = this.items;
      const period_id = this.$store.state.period.selected_period;
      const delete_tasks = this.delete_tasks;
      const delete_hours = this.delete_hours;
      const delete_categories = this.delete_categories;

      axios
        .post('/api/category/update', {items, period_id, delete_categories, delete_tasks, delete_hours})
        .then((response) => {
          this.$toasted.success('更新しました');
          this.items = response.data.items;
          this.delete_tasks = [];
          this.delete_hours = [];
          this.delete_categories = [];
        }).catch((error) =>{
        this.$toasted.error('更新出来ませんでした');
      });
    },
    getItems(){
      const period_id = this.$store.state.period.selected_period;

      axios
        .get('/api/category/'+ period_id + '/index')
        .then((response) => {
          this.items = response.data.items;
        });
    },
    countCategoryTasks(item){
      return item.tasks.length;
    },
    addTask(item, itemIndex){
      const category_id = item.category_id;
      const new_task = {
        task : {
          'category_id': category_id,
          'name': '',
          'manhours': [],
          'is_new': true,
          'period_id': this.$store.state.period.selected_period,
          'task_id': null,
        }
      };

      this.items[itemIndex]['tasks'].push(new_task);
    },
    addCategory(){
      const new_category = {
        'category_id': null,
        'name': '',
        'tasks': [],
        'is_new': true,
        'period_id': this.$store.state.period.selected_period,
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
      const item = this.items[itemIndex]['tasks'][taskIndex]['task'];
      if(!item.hasOwnProperty('is_new')){
        this.delete_tasks.push(item);
      }

      this.$delete(this.items[itemIndex]['tasks'], taskIndex);
    },
    changeValue(column, itemIndex, taskIndex, value){
      this.$set(this.items[itemIndex]['tasks'][taskIndex]['task'], column, value);
    },
    changeCategoryName(itemIndex, value){
      this.$set(this.items[itemIndex], 'name', value);
    },
    confirm(info){
      const new_items = info.items;
      const delete_hours = info.delete_hours;
      const itemIndex = this.selected_index['item'];
      const taskIndex = this.selected_index['task'];
      this.$set(this.items[itemIndex]['tasks'][taskIndex]['task'], 'manhours', new_items); 
      this.delete_hours = delete_hours;
    },
    openModalHour(itemIndex, taskIndex){
      this.is_modal_open = true;
      this.selected_index = {'item':itemIndex, 'task': taskIndex};
      this.task = this.items[itemIndex]['tasks'][taskIndex]['task'];
      this.selected_task = this.items[itemIndex]['tasks'][taskIndex]['task']['manhours'];
    },
    closeModalHour(){
      this.is_modal_open = false;
    },
    openAccordion(){
      // 
    },
  },
}
</script>

<style>
.save-icon {
  position: fixed;
  bottom: 16px;
  right: 16px;
}

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

.total-task-hour{
  cursor: pointer;
}
</style>