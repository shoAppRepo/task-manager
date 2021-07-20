<template>
  <div>
    <div class="calendar">
      <FullCalendar 
        ref="cc"
        :options="calendarOptions"
      />

      <modal_calendar
        v-if="modal_open"
        :selected_time="selected_time"
        @save="save"
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
      selected_time: '',
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
        // eventClick: this.updateItem,
      },
    };
  },
  created(){
    this.init();
  },
  mounted(){
    this.getSelectedMonth();
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
      axios
        .post('/api/task/update', {item})
        .then((response) => {
          this.calendarOptions.events = response.data.manhours;
          this.$toasted.success('更新しました');
          this.closeModalCalendar();
        }).catch((error) => {
          this.$toasted.error('更新できませんでした');
        });
    },
    getSelectedMonth() {
      let calendarApi = this.$refs.cc.getApi();
    },
    createItem(e){
      this.selected_time = e.dateStr;
      this.openModalCalendar();
    },
    // updateItem(e){
    //   console.log(e);
    // },
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