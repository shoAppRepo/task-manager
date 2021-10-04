<template>
  <div class="body">
    <vue-loading v-if="is_loading" type="spin" color="#333" :size="{ width: '50px', height: '50px' }"></vue-loading>
    <div v-else>
      <div class="container">
        <div class="mb-4 justify-content-between">
          <div>
            <select v-model="selectedPeriod" @change="getItems" class="col">
              <option disabled value=null>期間を選択してください</option>
              <option v-for="period in periods" :value="period.period_id" >{{ periodName(period) }}</option>
            </select>
          </div>
          <div>合計作業時間：{{allCategoryHours}}({{allDiciminalCategoryHour}}h)</div>
          <div>合計作業ポイント：{{ allTotalPoint }}pt({{ pointDiff }})</div>
          <div>目標ポイント：{{ periodGoalPoint }}</div>
        </div>
        <div class="row">
          <div
          v-for="(item, itemIndex) in items"
          class="col-sm-4 mb-4"
        >
          <div class="card">
            <div class="card-header category-header">
              <div class="row">
                <div class="col">
                  <div>{{ item.name }}</div>
                </div>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="text-left mb-5">
                <div>合計作業ポイント：{{ totalPoint(item) }}pt</div>
                <div>合計作業時間：{{ totalCategoryHours(item)}}({{ showcategoryDeciminalNumber(item) }}h)</div>
                <div class="boreder-line" />
              </div>

              <div 
                v-for="(task, taskIndex) in item.tasks"
                class="task-content mb-3"
              >
                <div class="pl-1">
                  <div>{{task.task.name}}</div>
                  <div class="row">
                    <div class="total-task-hour col">{{ totalTaskHours(task.task) }}({{ showTaskDeciminalNumber(task.task) }})</div>
                    <div class="col">{{ task.task.point }}pt</div>
                  </div>
                </div>
                <!-- <div class="boreder-dotted-line" /> -->
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import modal_hour from '../parts/modal_hour';
import { mapState } from 'vuex';
import { VueLoading } from 'vue-loading-template'

export default {
  components:{
    modal_hour,
    VueLoading,
  },
  data(){
    return {
      is_loading: false,
      is_modal_open: false,
      selected_task: {},
      selected_index: {},
      items: {},
      periods: {},
      task: {},
      all_category_hours: null,
      all_category_hours_deciminal: null,
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
    selected(){
      return (this.items.length > 0)? true: false;
    },
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
    periodGoalPoint() {
      const period_id= this.$store.state.period.selected_period;
      const selected_period = this.periods.find((period) => period.period_id === period_id);
      return selected_period['goal_point'];
    },
    allTotalPoint() {
      let total_point = 0;

      this.items.forEach((item) => {
        total_point += this.totalPoint(item);
      });

      return total_point;
    },
    pointDiff() {
      let diff = this.allTotalPoint - this.periodGoalPoint;
      if(diff > 0){
        diff = "+" + diff;
      }else if(diff === 0){
        diff = "±" + diff;
      }

      return diff;
    },
    selectedItems(){
      return this.items;
    },
    totalTaskHours(){
      return (task) => {
        const hours = this.calcTaskHours(task);
        const hourandminute = this.getHourAndMinute(hours);

        return hourandminute['hour'] + "時間" + hourandminute['minute'] + "分";//1時間0分
      };
    },
    showcategoryDeciminalNumber(){
      return (task) => {
        const hours = this.calcCategoryHours(task);
        const hourandminute = this.getHourAndMinute(hours);
        let minute = hourandminute['minute']/60;
        //小数点移動
        minute *= 100;
        // 四捨五入
        let fix_minute = Math.round(minute);
        // 小数点を戻す
        fix_minute /= 100;
        return hourandminute['hour'] + fix_minute;
      };
    },
    showTaskDeciminalNumber(){
      return (task) => {
        const hours = this.calcTaskHours(task);
        const hourandminute = this.getHourAndMinute(hours);
        let minute = hourandminute['minute']/60;
        //小数点移動
        minute *= 100;
        // 四捨五入
        let fix_minute = Math.round(minute);
        // 小数点を戻す
        fix_minute /= 100;
        return hourandminute['hour'] + fix_minute + 'h';
      };
    },
    totalPoint(){
      return(item)=>{
        let total_point = 0;
        item['tasks'].forEach((task) => {
          const point = Number(task.task.point);
          if(point >= 0){
            total_point += point;
          }
        });

        return total_point;
      };
    },
    totalCategoryHours(){
      return (task) => {
        const hours = this.calcCategoryHours(task);
        const hourandminute = this.getHourAndMinute(hours);

        return hourandminute['hour'] + "時間" + hourandminute['minute'] + "分";//1時間0分
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
          if(start && end){
            const start_date = new Date(String(start));
            const end_date = new Date(String(end));
            const diff_time =  (end_date - start_date);
            
            total_task_hours += diff_time;
          }else {
            total_task_hours += 0;
          }
        });

        return total_task_hours;
      };
    },
    allCategoryHours(){
      let hours = 0;
      let hourandminute = 0;
      if(this.items.length){
        for(const item of this.items){
          hours += this.calcCategoryHours(item);
          hourandminute = this.getHourAndMinute(hours);
        }
        return hourandminute['hour'] + "時間" + hourandminute['minute'] + "分";//1時間0分
      }

      return hours + '時間';
    },
    allDiciminalCategoryHour(){
      let all_category_hours_deciminal = 0;
      if(this.items.length){
        for(const item of this.items){
          all_category_hours_deciminal += this.showcategoryDeciminalNumber(item);
        }
      }

      return all_category_hours_deciminal;
    },  
  },
  methods:{
    init(){
      this.is_loading = true;
      let period_id = null;
      if(this.$store.state.period.selected_period){
        period_id = this.$store.state.period.selected_period;
      }

      axios
        .get('/api/period/' + period_id + '/indexWithItems')
        .then((response) => {
          this.periods = response.data.periods;
          this.items = response.data.items;
          this.is_loading = false;
        });

    },
    save(){
      this.is_loading = true;
      const items = this.items;
      const period_id = this.$store.state.period.selected_period;
      const delete_tasks = this.delete_tasks;
      const delete_hours = this.delete_hours;
      const delete_categories = this.delete_categories;

      axios
        .post('/api/category/update', {items, period_id, delete_categories, delete_tasks, delete_hours})
        .then((response) => {
          this.is_loading = false;
          this.$toasted.success('更新しました');
          this.items = response.data.items;
          this.delete_tasks = [];
          this.delete_hours = [];
          this.delete_categories = [];
        }).catch((error) =>{
          this.is_loading = false;
          this.$toasted.error('更新出来ませんでした');
      });
    },
    getItems(){
      this.is_loading = true;
      const period_id = this.$store.state.period.selected_period;

      axios
        .get('/api/category/'+ period_id + '/index')
        .then((response) => {
          this.items = response.data.items;
          this.is_loading = false;
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
          'point': null,
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
    getHourAndMinute(hours){
      let h = Math.floor(hours / 3600000);//時間
      let m = Math.floor((hours - h * 3600000) / 60000);//分

      return{'hour' :h,'minute':m};
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
  },
}
</script>

<style>
.boreder-line{
  border: solid 1px #66CC66;
}

.boreder-dotted-line{
  border: dotted 1px #66CC66;
}

.total-task-hour{
  text-align: start;
}

.task-content{
  border-left: solid 3px #66CC66;
}
</style>