<template>
  <div>
    <div id="layout-dashboard">
      <feedback></feedback>
      <header class="dashboard-header">
        <!-- <h1 class="title-primary">{{this.content['city']}}</h1> -->
        <admin-event-nav />
        <!-- <a
                :href="`/api/v1/admin/export/schedule/organization/${this.event_name}?token=${accesstoken}`"
                target="_blank"
                class="btn btn-primary btn-inverted"
              >Exporter</a> -->

               <a
                :href="`/api/v1/subscriptions/export/${this.event_name}/dancer?token=${accesstoken}`"
                target="_blank"
                class="btn btn-primary btn-inverted"
              >Exporter les danseurs</a>
        
      </header>
      <div class="drag-loader" :class="{ 'is-loading' : isLoading, 'is-locked' : isLocked}">
        <div class="actions-header">
          <div class="action-position" v-on:click.prevent="initializePosition">
            <div class=""><icon icon="format-list-numbered"></icon></div>
          </div>
          <div class="action-lock" v-on:click.prevent="setLockState">
            <div class="" v-if="isLocked"><icon icon="lock" ></icon></div>
            <div class="" v-else><icon icon="unlock"></icon></div>
          </div>
          
        </div>
        <table class="drag-table" cellspacing="0" cellpadding="0">
        <thead class="">
          <tr>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead" width="50">Ordre</th>
            <th scope="col" class="drag-table-title text-subhead" width="150">Date</th>
            <th scope="col" class="drag-table-title text-subhead" width="50">Block</th>
            <th scope="col" class="drag-table-title text-subhead" width="100">Heure</th>
            <th scope="col" class="drag-table-title text-subhead" width="250">Nom</th>
            <th scope="col" class="drag-table-title text-subhead" width="450">Catégories</th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>

          </tr>
        </thead>
          <tbody>
            <tr v-for="(el, index) in this.elements" :key="el.name" class="drag-item drag-subitem schedule-hour" >
                    <td scope="row" class="c-align" width="30"><icon icon="drag" class></icon></td>
                    <td scope="row" width="50">
                      {{el.position}}
                    </td>
                    <td scope="row" width="150">
                      <cleave v-model="el.year" class="schedule-input" :raw="false" :options="{ date: true, delimiter: '-', datePattern: ['Y', 'm', 'd']}" v-on:blur.native="setYear($event, index)"></cleave>
                    </td>
                     <td scope="row" width="50">
                       
                      <input type="text" class="schedule-input input-xsmall" v-model="el.block" v-on:blur="setBlock($event, index)" />
                    </td>
                    <td scope="row" width="100">
                      <cleave v-model="el.hour" class="schedule-input" :raw="false" :options="{  time: true,
    timePattern: ['h', 'm']}" v-on:blur.native="setSchedulePassage($event, index)"></cleave>
                    </td>
                    <td scope="row" width="250">
                    {{el.routine.name}}
                    </td>
                    <td class="" scope="row" width="420" v-if="el.schedule_title">
                      
                    {{el.schedule_title.name}}
                    </td>
                    <td class="drag-padded">
                       <div class="" v-if="isLocked"><icon icon="small-lock" ></icon></div>
                    </td>
                   
                  </tr>
          </tbody>
        </table>
      </div>
      <div class="form-actions form-actions-fixed">
            <div
              class="btn btn-secondary"
              v-on:click.prevent="back"
            >{{ $t('forms.actions.cancel') }}</div>
            <button
              class="btn btn-primary"
              type="submit"
              :disabled="isLoading"
              v-on:click.prevent="updateScheduleItems"
            >{{ $t('forms.actions.save') }}</button>
        </div>
      
    </div>
  </div>
</template>
<script>
// import nestedDraggable from "../components/infra/nested";

import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import AdminEventNav from "../components/AdminEventNav";
import { i18n } from "../plugins/i18n.js";
import axios from "axios";

export default {
  data: function() {
    return {
      saving: false,
      isLocked:false
      // list: this.content['schedule'].map((name, index) => {
      //   return { name, order: index + 1 };
      // })
      
    };
  },
  beforeRouteEnter(to, from, next) {
    next();
    store.dispatch('schedules/getItems', {
      city: to.params.event,
    })
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
  },
  
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      isLoading: "loading/isLoading",
      accesstoken: "auth/accesstoken"
    }),
    elements: {
      get() {
        return Object.freeze(store.state.schedules.items);
      },
      set(value) {
        //console.log(value)
        //store.dispatch("schedules/updateScheduleDrag", value);
      }
    },
    event_name() {
      return this.$route.params.event;
    }
  },

  methods: {
    ...mapActions({
      setFeedback: "feedback/setFeedback",
      update: 'schedules/updateAllScheduleItem'
    }),
    initializePosition(ev) {
      var data = JSON.parse(JSON.stringify(this.elements)); ; // create TRUE copy
      //const sorted = data.sort((a, b) => b.date - a.date)
       store.dispatch("loading/setLoading", true);
      // if(!this.isLocked) {
      //     data.sort(function(a,b){
      //     // Turn your strings into dates, and then subtract them
      //     // to get a value that is either negative, positive, or zero.
      //       return new Date(a.start_date) - new Date(b.start_date);
      //   });
        for(let i = 0, len = data.length; i < len; i++) {
          data[i]['position'] = i + 1;;
        }
      // }
      
      store.dispatch("schedules/setItems", data);
    },
    setLockState(ev) {
      this.isLocked = this.isLocked === false ? true : false;
    },
    updateScheduleItems(ev) {
      store.dispatch("loading/setLoading", true);
      this.update(this.elements).then(() => {
          store.dispatch("loading/setLoading", false);
        })
        .catch(error => {
            this.setFeedback({ message: error.data.msg, type: "warning" });

          //this.$setLaravelValidationErrorsFromResponse(error.data);
          store.dispatch("loading/setLoading", false);
        })
        .then(_ => (store.dispatch("loading/setLoading", false)));
    },
    setSchedulePassage(ev, index) {
     store.dispatch("loading/setLoading", true);
      var data = JSON.parse(JSON.stringify(this.elements)); ; // create TRUE copy
       var newStartDate = new Date(data[index].year + " " + ev.target.value)
            data[index]['hour'] = ev.target.value;
            data[index]['start_date'] = this.formatDate(newStartDate);

      if(!this.isLocked) {
        for(let i = index + 1, len = data.length; i < len; i++) {
          let duration = data[i - 1].routine.category.duration
          var date = this.dateAdd(new Date(data[i - 1].start_date), 'second', duration);
          data[i]['start_date'] = this.formatDate(date);
          data[i]['year'] = this.dateToYMD(date);
          data[i]['hour'] = this.dateToHIS(date);
        }
      }

      
      store.dispatch("schedules/setItems", data);


    },
    setYear(ev, index) {
     store.dispatch("loading/setLoading", true);
      var data = JSON.parse(JSON.stringify(this.elements)); ; // create TRUE copy
      var newStartDate = new Date(ev.target.value + " " + data[index].hour)
          data[index]['year'] = ev.target.value;
          data[index]['start_date'] = this.formatDate(newStartDate);
      if(!this.isLocked) {
        for(let i = index +1, len = data.length; i < len; i++) {
            let duration = data[i - 1].routine.category.duration
            var date = this.dateAdd(new Date(data[i - 1].start_date), 'second', duration);
            data[i]['start_date'] = this.formatDate(date);
            data[i]['year'] = this.dateToYMD(date);
            data[i]['hour'] = this.dateToHIS(date);
          }
      }
      store.dispatch("schedules/setItems", data);
    },
    setBlock(ev, index) {
     store.dispatch("loading/setLoading", true);
      var data = JSON.parse(JSON.stringify(this.elements)); ; // create TRUE copy
       data[index]['block'] = ev.target.value;
      if(!this.isLocked) {
        for(let i = index + 1, len = data.length; i < len; i++) {
          data[i]['block'] = data[i - 1]['block'];
        }
      }
      
      store.dispatch("schedules/setItems", data);
    },
    formatDate(d) {
        return this.dateToYMD(d) + " " + this.dateToHIS(d);
    },
    dateToYMD(d) {
        var year, month, day;
        year = String(d.getFullYear());
        month = String(d.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(d.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
       

        return year + "-" + month + "-" + day;
    },
    dateToHIS(d){
      var h = this.addZero(d.getHours());
      var m = this.addZero(d.getMinutes());
      var s = this.addZero(d.getSeconds());

      return h + ":" + m + ":" + s;
    },
    addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    },
    dateAdd(date, interval, units) {
    if(!(date instanceof Date))
        return undefined;
      var ret = new Date(date); //don't change original date
      var checkRollover = function() { if(ret.getDate() != date.getDate()) ret.setDate(0);};
      switch(String(interval).toLowerCase()) {
        case 'year'   :  ret.setFullYear(ret.getFullYear() + units); checkRollover();  break;
        case 'quarter':  ret.setMonth(ret.getMonth() + 3*units); checkRollover();  break;
        case 'month'  :  ret.setMonth(ret.getMonth() + units); checkRollover();  break;
        case 'week'   :  ret.setDate(ret.getDate() + 7*units);  break;
        case 'day'    :  ret.setDate(ret.getDate() + units);  break;
        case 'hour'   :  ret.setTime(ret.getTime() + units*3600000);  break;
        case 'minute' :  ret.setTime(ret.getTime() + units*60000);  break;
        case 'second' :  ret.setTime(ret.getTime() + units*1000);  break;
        default       :  ret = undefined;  break;
      }
      return ret;
	  },
   
    back() {
      this.$router.go(-1);
    }
    
  },
  components: {
    Icon,
    Feedback,
    AdminEventNav,
  },

  created() {
    //console.log(this.elements);
  }
};
(function() {
    Date.prototype.toYMD = Date_toYMD;
    function Date_toYMD() {
        var year, month, day;
        year = String(this.getFullYear());
        month = String(this.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(this.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return year + "-" + month + "-" + day;
    }
})();
</script>
<style lang="scss" scoped>

</style>
