<template>
  <div>
    <div class="calendar">
      <FullCalendar 
        ref="cc"
        :options="calendarOptions"
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
import { VueLoading } from 'vue-loading-template'

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
    VueLoading,
  },
  data() {
    return {
      is_loading: false,
      calendarOptions: {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
        initialView: 'timeGridDay',
        locale: jaLocale,
        editable: true,
        selectable: true,
        events:[],
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
    getSelectedMonth() {
      let calendarApi = this.$refs.cc.getApi();
    },
  }
}
</script>

<style>
.top-page {
  display: flex;
  min-height: 100%;
}

.sidebar {
  width:20%;
  background-color: blue;
}

.demo-app-main {
  flex-grow: 1;
  padding: 3em;
}

.fc { /* the calendar root */
  max-width: 1100px;
  margin: 0 auto;
}
</style>