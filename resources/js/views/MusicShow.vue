<template>
  <div id="layout-dashboard">
    <feedback></feedback>
    <a href="#" v-on:click.prevent="back" class="btn-back">
      <icon icon="back" class></icon>
      <span class="btn-back-text text-footnote">{{ $t('global.text.back') }}</span>
    </a>
    <h1 class="title-primary">{{ $t('dashboard.title.music') }}</h1>
    <section  :class="{ 'is-loading' : isLoading}" v-for="subscription in this.subscriptions" v-bind:key="subscription.id">
      <h2 class="title-secondary">{{subscription.event.city}}</h2>
      <div class="table">
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.name') }}</span>
            </li>
            <li class="table-item grid-1">
              <span class="text-subhead">{{ $t('dashboard.table.title.totalSubscription') }}</span>
            </li>
            <li class="table-item grid-1">
              <span class="text-subhead">{{ $t('dashboard.table.title.category') }}</span>
            </li>
            <li class="table-item grid-1">
              <span class="text-subhead">{{ $t('dashboard.table.title.level') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.style') }}</span>
            </li>
            
            <li class="table-item grid-4">
              <span class="text-subhead">{{ $t('dashboard.table.title.music') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
          <ul
            class="table-list table-list-body"
            v-for="(routine, index) in subscription.routines"
            v-bind:key="routine.id"
            ref="routines"
          >
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.name}}</span>
            </li>
            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{routine.dancers.length}}</span>
            </li>
            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{routine.category.name}}</span>
            </li>
            <li class="table-item grid-1">
              <span class="table-text text-body-display">{{routine.level.name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.style.name}}</span>
            </li>
            
             <li class="table-item grid-4">
                  <form class="form-upload" method="POST " enctype="multipart/form-data" v-on:submit.prevent="onUploadSubmit">
                    <div class="group-upload">
                        <input class="input-file" type="file" name="file" :id="'file-' + routine.id" v-on:change="handleFiles" />
                        <label :for="'file-' + routine.id" class="btn btn-secondary" v-if="routine.music != 'default.mp3'">{{ routine.music }}</label>
                        <label :for="'file-' + routine.id" class="btn btn-secondary" v-else>{{ $t('forms.actions.upload') }}</label>
                    </div>
                    <div class="form-actions">
                         <input type="hidden" name="routine_id" :value="routine.id" />
                      <button class="btn-upload btn btn-primary" type="submit" >
                          {{ $t('forms.actions.submit') }}
                      </button>
                    </div>
                     <div class="music-play-container" v-if="routine.music != 'default.mp3'">
                      <a :href="'/storage/' + subscription.event.slug + '/' + routine.music" target="_blank">
                        <icon icon="play"></icon>
                      </a>
                    </div>
                     
                  </form>
            </li>
           
           
              <!-- <div class="table-menu" @click.prevent="openActions" v-if="getSubscriptionStatusID() == 1">
                <icon icon="menu" class></icon>
              </div> -->
              <!-- <div class="actions-container" v-if="getSubscriptionStatusID() == 1">
                <router-link
                  :to="{ name: 'routines.edit', params: {id: routine.subscription_id, routine_id: routine.id} }"
                  class="action table-action"
                >
                  <icon icon="edit" class></icon>
                  <span class="text-subhead">{{ $t('forms.actions.edit') }}</span>
                </router-link>
                <router-link
                  :to="{ name: 'routines.duplicate', params: {id: routine.subscription_id, routine_id: routine.id} }"
                  class="action table-action"
                >
                  <icon icon="duplicate" class></icon>
                  <span class="text-subhead">{{ $t('forms.actions.duplicate') }}</span>
                </router-link>
                <a
                  href="#"
                  class="action action-table"
                  @click.prevent="deleteRoutine($event, routine.id)"
                  :disabled="saving"
                >
                  <icon icon="delete" class></icon>
                  <span class="text-subhead">{{ $t('forms.actions.delete') }}</span>
                </a>
                <div class="action-close-overlay" @click.prevent="closeActions"></div>
              </div> -->
            <!-- </li> -->
            <!-- <li class="table-item-link">
              <router-link
                :to="{ name: 'routines.edit', params: {id: routine.subscription_id, routine_id: routine.id} }"
                class
              ></router-link>
            </li> -->
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import { i18n } from "../plugins/i18n.js";
import axios from 'axios';


export default {
  data: function() {
    return {
      saving: false,
      file: ''
    };
  },
  beforeRouteEnter(to, from, next) {
    next()
  },
 
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
       isLoading: "loading/isLoading",
        subscriptions: "subscriptions/subscriptions",
      // ...
    })
  },

  methods: {
    ...mapActions({
      upload:"routines/upload",
      setFeedback:"feedback/setFeedback"
    }),
    handleFiles(ev) {
      this.file = ev.target.files[0];
      var label = ev.target.nextElementSibling;
      var fileName = ev.target.value.split( '\\' ).pop();
      label.innerHTML = fileName;

    },
    onUploadSubmit(ev) {

let formData = new FormData();
      formData.append('song', this.file)
      formData.append('routine_id', ev.target.routine_id.value)

      store.dispatch("loading/setLoading", true);

      this.saving = true;
      this.upload(formData)
        .then(() => {
          this.setFeedback({
            message: i18n.t("messages.global.success"),
            type: "success"
          });
          store.dispatch("loading/setLoading", false);
                					store.dispatch('subscriptions/getSubscriptions', false);


          //this.$router.push({ name: "dashboard.index" });
        })
        .catch(error => {
          store.dispatch("loading/setLoading", false);
          this.setFeedback({ message: error.data.msg, type: "warning" });
          //this.$setLaravelValidationErrorsFromResponse(error.data);
          this.saving = false;
        })
        .then(_ => (this.saving = false));



    },
    removeFile( key ){
        // this.files.splice( key, 1 );
        //this.getImagePreviews();
    },
    back() {
      this.$router.go(-1);
    },
    openActions(ev) {
      ev.currentTarget.parentNode.classList.toggle("has-menu-open");
    }
  },
  components: {
    Icon,
    Feedback
  },
  created() {
    //console.log(this.subscription.consent_video);
    console.log();
    //this.subscriptionData.consent_video = this.subscription.consent_video;
    //this.subscriptionData.consent_rules = this.subscription.consent_rules;
  }
};
</script>
<style lang="scss" scoped>
section {
  margin:0 0 32px 0;
}
</style>