<template>
  <div>
    <vue-loading v-if="is_loading" type="spin" color="#333" :size="{ width: '50px', height: '50px' }"></vue-loading>
    <div v-else class="calendar">
      <FullCalendar 
        ref="cc"
        :options="calendarOptions"
      />

      <modal_calendar
        v-if="modal_open"
        :method="method"
        :selected_item="selected_item"
        :selected_time="selected_time"
        @save="save"
        @deleteItem="deleteItem"
        @close="closeModalCalendar"
      />
    </div>

  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import jaLocale from "@fullcalendar/core/locales/ja"; // 日本語化用
import { VueLoading } from 'vue-loading-template';
import modal_calendar from '../parts/modal_calendar';

export default {
  components: {
    modal_calendar,
    FullCalendar, 
    VueLoading,
  },
  data() {
    return {
      modal_open: false,
      is_loading: false,
      selected_time: {},
      selected_item: {},
      calendarOptions: {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
        initialView: 'timeGridWeek',
        locale: jaLocale,
        editable: true,
        selectable: true,
        events:[],
        dateClick: this.createItem,
        eventClick: this.updateByModal,
        select: this.createItem, 
        eventResize: this.update,
        eventDrop: this.update, 
      },
    };
  },
  created(){
    this.init();
  },
  methods: {
    init(){
      this.is_loading = true;
      axios
        .get('/api/calendar/index')
        .then((response) => {
          this.calendarOptions.events = response.data.manhours;
          this.is_loading = false;
      });
    },
    save(item){
      this.is_loading = true;
      let save_url = '';
      if(this.method === 'insert'){
        save_url = '/api/task/insert';
      }else{
        save_url = '/api/task/update';
      }
      axios
        .post(save_url, {item})
        .then((response) => {
          this.calendarOptions.events = response.data.manhours;
          this.$toasted.success('更新しました');
          this.is_loading = false;
          this.closeModalCalendar();
        }).catch((error) => {
          this.$toasted.error('更新できませんでした');
        });
    },
    deleteItem(item){
      axios
        .post('/api/task/delete', {item})
        .then((response) => {
          this.calendarOptions.events = response.data.manhours;
          this.$toasted.success('更新しました');
          this.closeModalCalendar();
        }).catch((error) => {
          this.$toasted.error('更新できませんでした');
        });
    },
    createItem(e){
      this.selected_time = {
        'start':e.startStr,
        'end':e.endStr,
      };
      this.method = 'insert';
      this.openModalCalendar();
    },
    updateByModal(e){
      this.method = 'update';
      this.selected_item = {
        'end' :e.event.endStr,
        'title': e.event.title,
        'remark': e.event.extendedProps.remark,
        'start': e.event.startStr,
        'task_id': e.event.extendedProps.task_id,
        'man_hour_id': e.event.extendedProps.man_hour_id,
      };

      this.openModalCalendar();
    },
    update(e){
      this.method = 'update';
      const item = {
        'end' :e.event.endStr,
        'title': e.event.title,
        'remark': e.event.extendedProps.remark,
        'start': e.event.startStr,
        'task_id': e.event.extendedProps.task_id,
        'man_hour_id': e.event.extendedProps.man_hour_id,
      };

      this.save(item);
    },
    openModalCalendar(){
      this.modal_open = true;
    },
    closeModalCalendar(){
      this.modal_open = false;
    },
  }
}
</script>

<style>
</style>