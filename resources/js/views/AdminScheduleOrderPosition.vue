<template>
  <div>
    <div id="layout-dashboard">
      <feedback></feedback>
      <header class="dashboard-header">
        <!-- <h1 class="title-primary">{{this.content['city']}}</h1> -->
        <admin-event-nav />
        <a
                :href="`/api/v1/admin/export/schedule-item/by-position/${this.event_name}?token=${accesstoken}`"
                target="_blank"
                class="btn btn-primary btn-inverted"
                
              >Exporter</a>
      </header>
      <div class="drag-loader" :class="{ 'is-loading' : isLoading}">
          <nested-schedules v-model="elements" />
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
              @click.prevent="saveSchedule"
            >{{ $t('forms.actions.save') }}</button>
        </div>
      <!-- <raw-displayer class="col-4" :title="'Vuex Store'" :value="elements" /> -->
        <!-- <nested-draggable :schedule="this.content['schedule']" class="drag-parent"  /> -->
        <!-- <rawDisplayer class="debug-sortable col-3" :value="this.content['schedule'][0]" title="Schedule" /> -->
    </div>
    <modal name="replace" :classes="'modal'" height="400" @before-open="beforeOpen">
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">{{$t('forms.title.replace')}}</h1>
          <span class="modal-close" @click.prevent="hide">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
           <form class="form-schedule-title" @submit.prevent>
            <div class="form-group grid-12">
                <icon icon="search" class="icon-autocomplete"></icon>
                <input id="autocomplete_input" class="form-text-field-autocomplete" type="text" @keyup="autoComplete" v-model="query">
            </div>
          </form>
          <div class="modal-autocomplete" v-if="this.results.length">
                <ul class="autocomplete-results">
                  <li
                  :class="`autocomplete-item autocomplete-item-${result.id}`"
                  @click.prevent="moveScheduleItem($event, result.id)"
                   v-for="result in results"
                    v-bind:key="result.id">
                    <span class="autocomplete-name text-subhead">{{ result.name }}</span>
                  </li>
                </ul>
              </div>
        </div>
      </article>
    </modal>
  </div>
</template>
<script>
// import nestedDraggable from "../components/infra/nested";
import NestedSchedules from "../components/infra/nested-schedules";

import rawDisplayer from "../components/infra/raw-displayer";
import draggable from 'vuedraggable'
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
      query:'',
      itemToMoveIndex:null,
      itemToMoveParentIndex:null,
      results:[]
      // list: this.content['schedule'].map((name, index) => {
      //   return { name, order: index + 1 };
      // })
      
    };
  },
  beforeRouteEnter(to, from, next) {
    next();
    store.dispatch('schedules/getByPosition', {
      city: to.params.event,
    })
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
  },
  
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      user: "auth/user",
      isAdmin: "auth/isAdmin",
      isLoading: "loading/isLoading",
      // content: "schedules/schedule",
      accesstoken: "auth/accesstoken"
    }),
    elements: {
      get() {
        return store.state.schedules.schedule;
      },
      set(value) {
        store.dispatch("schedules/updateScheduleDrag", value);
      }
    },
    event_name() {
      return this.$route.params.event;
    }
  },

  methods: {
    ...mapActions({
      setFeedback: "feedback/setFeedback",
      update: 'schedules/update'
    }),
    beforeOpen (ev) {
      this.itemToMoveParentIndex = ev.params.parentIndex
      this.itemToMoveIndex = ev.params.index;
    },
    moveScheduleItem(ev, destinationID) {
      this.$modal.hide('replace');
      this.query = '';
      this.results = [];
       store.dispatch("schedules/move", { 
         destinationID: destinationID, 
         index:this.itemToMoveParentIndex,
         cutIndex:this.itemToMoveIndex
      });
    },
    saveSchedule(ev) {
      store.dispatch("loading/setLoading", true);
      var data = {
        'saveOrder' : false,
        'elements' : this.elements
      };
      this.update({
        inputs: data,
        id: this.event_name,
      }).then(() => {
          store.dispatch("loading/setLoading", false);
        })
        .catch(error => {
            this.setFeedback({ message: error.data.msg, type: "warning" });

          //this.$setLaravelValidationErrorsFromResponse(error.data);
          store.dispatch("loading/setLoading", false);
        })
        .then(_ => (store.dispatch("loading/setLoading", false)));
    },
    autoComplete(ev){
        if(this.query.length > 3) {

            this.results = this.elements.filter(element => element.name.toLowerCase().indexOf(this.query.toLowerCase()) > -1);
        }else if(this.query.length == 0) {
            //this.results = [];
        }
    },
    hide() {
      this.$modal.hide('replace');
    },
    back() {
      this.$router.go(-1);
    }
    
  },
  components: {
    Icon,
    Feedback,
    AdminEventNav,
    draggable,
    NestedSchedules,
    // nestedDraggable,
    rawDisplayer
  },

  created() {
    //console.log(this.elements);
  }
};
</script>
<style lang="scss" scoped>
  .debug-sortable {
    position:fixed;
    background:#020202;
    color:#FFFFFF;
    width:300px;
    height:50vh;
    left:0;
    top:50%;
    transform:translate(0,-50%);
  }
</style>
