<template>
  <div id="layout-dashboard">
    <feedback></feedback>
    <a href="#" v-on:click.prevent="back" class="btn-back">
      <icon icon="back" class></icon>
      <span class="btn-back-text text-footnote">{{ $t('global.text.back') }}</span>
    </a>
    <h1 class="title-primary">{{this.subscription.event.city}}</h1>
    <header class="subscription-header">
      <div :class="`status status-${this.subscription.status.type}`">
        <span class="text-subhead">{{this.subscription.status.admin_label}}</span>
      </div>
      <div class="card-row">
        <div class="card-info card-col">
          <h2 class="title-headline">{{ $t('dashboard.title.totalSubscriptions') }}</h2>
          <span class="text-body-display">{{this.subscription.total_dancer}}</span>
        </div>
        <div class="card-info card-col">
            <h2 class="title-headline">{{ $t('dashboard.table.title.routine') }}</h2>
            <span class="text-body-display">{{this.subscription.routines_count}}</span>
        </div>
        <div class="card-info card-col">
          <h2 class="title-headline">{{ $t('dashboard.title.debt') }}</h2>
          <span class="text-body-display">{{this.subscription.balance}} $</span>
        </div>
      </div>
    </header>
    <section class="hero" v-if="!this.dancers">
      <h1
        class="hero-title title-large"
      >{{ $t('dashboard.title.hero', { name: `${this.subscription.organization.name}` }) }}</h1>
      <p class="hero-text text-body-display">{{$t('dashboard.text.hero')}}</p>
      <router-link
        :to="{ name: 'dashboard.dancer' }"
        class="btn btn-hero"
      >{{ $t('dashboard.label.addDancer') }}</router-link>
    </section>
    <section class="routine-container" v-if="this.dancers">
      <h1 class="title-tertiary">{{ $t('dashboard.title.routines') }}</h1>
      <div class="alert alert-no-data" v-if="!this.subscription.routines.length">
        <p class="alert-text text-body-display">{{ $t('dashboard.text.noRoutines') }}</p>
      </div>
      <div class="table" v-else>
        <div class="table-header">
          <ul class="table-list">
            <li class="table-item grid-4">
              <span class="text-subhead">{{ $t('dashboard.table.title.name') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.category') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.level') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.style') }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="text-subhead">{{ $t('dashboard.table.title.totalSubscription') }}</span>
            </li>
          </ul>
        </div>
        <div class="table-body">
          <ul
            class="table-list table-list-body"
            v-for="routine in this.subscription.routines"
            v-bind:key="routine.id"
            ref="routines"
          >
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{routine.name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.category.name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.level.name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.style.name}}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{routine.dancers.length}}</span>
              <div class="table-menu" @click.prevent="openActions" v-if="getSubscriptionStatusID() == 1">
                <icon icon="menu" class></icon>
              </div>
              <div class="actions-container" v-if="getSubscriptionStatusID() == 1">
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
              </div>
            </li>
            <li class="table-item-link">
              <router-link
                :to="{ name: 'routines.edit', params: {id: routine.subscription_id, routine_id: routine.id} }"
                class
              ></router-link>
            </li>
          </ul>
        </div>
      </div>
      <section class="routine-actions">
      
    <!--  <router-link
  :to="{ name: 'dashboard.musicShow' }"
  class="btn btn-primary btn-inverted btn-spaced"
> 
  <span class="text-subhead">{{$t('dashboard.label.add.music')}}</span>
</router-link> -->

        <div v-if="getSubscriptionStatusID() == 1">
          <button
            class="btn btn-primary btn-inverted"
            @click.prevent="openActions"
          >{{$t('dashboard.label.add.routine')}}</button>
          

          <div class="actions-container action-routine">
            <router-link
              :to="{ name: 'routines.create', params: {id: this.subscription.id} }"
              class="action table-action"
            >
              <span class="text-subhead">{{$t('dashboard.label.add.routine')}}</span>
            </router-link>
            <a href="#" class="action table-action" @click.prevent="showModalDuplicateRoutine">
              <span class="text-subhead">{{ $t('forms.actions.duplicateRoutine') }}</span>
            </a>
          </div>
         
        </div>
      </section>
    </section>

    
    <div class="alert-submission" v-if="this.subscription.status_id == 1">
      <div class="alert-inner">
        <p
          class="alert-text text-body-display"
          v-if="!this.subscription.routines.length"
        >{{ $t('dashboard.text.notSubmitted') }}</p>
        <p class="alert-text text-body-display" v-else>{{ $t('dashboard.text.ctaSubmit') }}</p>
        <button
          class="btn btn-primary"
          @click.prevent="showModalConsent"
          :disabled="!this.subscription.routines.length"
        >{{ $t('forms.actions.submit') }}</button>
        <!-- <button
          class="btn btn-primary"
          @click.prevent="changeStatus(2)"
          :disabled="!this.subscription.routines.length || this.saving"
        >{{ $t('forms.actions.submit') }}</button> -->
      </div>
    </div>
    <!-- <div class="alert-submission" v-else-if="this.subscription.status_id == 1 && this.subscription.consent_video == 1 &&  this.subscription.consent_rules == 1">
      <div class="alert-inner">
        <p
          class="alert-text text-body-display"
          v-if="!this.subscription.routines.length"
        >{{ $t('dashboard.text.notSubmitted') }}</p>
        <p class="alert-text text-body-display" v-else>{{ $t('dashboard.text.submittedEdit') }}</p>
        <button
          class="btn btn-primary"
          @click.prevent="changeStatus(2)"
          :disabled="!this.subscription.routines.length || this.saving"
        >{{ $t('forms.actions.submit') }}</button>
      </div>
    </div> -->
    <div class="alert-submission" v-else>
      <div class="alert-inner">
        <p
          class="alert-text text-body-display"
          v-if="!this.subscription.routines.length"
        >{{ $t('dashboard.text.notSubmitted') }}</p>
        <p class="alert-text text-body-display" v-else>{{ $t('dashboard.text.submitChange') }}</p>
        <button
          class="btn btn-primary btn-waiting"
          @click.prevent="changeStatus(1)"
          :disabled="this.saving"
        >{{ $t('dashboard.text.submitChangeCTA') }}</button>
      </div>
    </div>
    <modal name="duplicate-routine-modal" :classes="'modal'" height="auto">
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">{{$t('forms.title.duplicate.routine')}}</h1>
          <span class="modal-close" @click.prevent="hideModalDuplicateRoutine">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
          <p class="text-subhead">{{$t('forms.text.duplicate.routine')}}</p>
          <form autocomplete="off" class="form-routine-duplicate" @submit.prevent>
            <section class="form-step" data-step="1">
              <div class="form-group">
                <div class="floating-label-container">
                  <select
                    id="subscription"
                    class="form-select"
                    v-on:change="onSelectChange"
                    v-model="selectedSubscription"
                  >
                    <option></option>
                    <option
                      v-for="option in this.subscriptions"
                      v-bind:key="option.id"
                      v-bind:value="option.id"
                    >{{option.event.city}}</option>
                  </select>
                  <label class="floating-label" for="subscription">{{$t('forms.label.competition')}}</label>
                </div>
              </div>
              <div class="form-group">
                <div class="floating-label-container">
                  <select
                    id="subscription_routines"
                    class="form-select"
                    :disable="!this.subscriptionRoutines.length"
                    v-model="selectedRoutine"
                  >
                    <option
                      v-for="option in this.subscriptionRoutines"
                      v-bind:key="option.id"
                      v-bind:value="option.id"
                    >{{option.name}}</option>
                  </select>
                  <label
                    class="floating-label"
                    for="subscription_routines"
                  >{{$t('forms.label.routines')}}</label>
                </div>
              </div>
              <div class="form-actions">
                <div
                  class="btn btn-secondary"
                  @click.prevent="hideModalDuplicateRoutine"
                >{{ $t('forms.actions.cancel') }}</div>

                <router-link
                  :to="{ name: 'routines.duplicate', params: {id: this.subscription.id, routine_id: this.selectedRoutine} }"
                  class="btn btn-primary"
                  v-if="this.selectedRoutine"
                >
                  {{ $t('forms.actions.duplicate') }}
                </router-link>
              </div>
            </section>
          </form>
        </div>
      </article>
    </modal>
     <modal name="modal-consent" :classes="'modal'" height="auto">
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">{{$t('forms.title.submit.subscription')}}</h1>
          <span class="modal-close" @click.prevent="hideModalConsent">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
          <form autocomplete="off" class="form-submit-subscription" @submit.prevent="changeStatus(2)">
            <section class="form-step" data-step="1">
              <div class="form-group" v-bind:class="{'has-error' : errors.has('consent_video')}">
                  <div class="form-checkbox-container">
                      <input id="subscription_consent_video" class="form-checkbox-input" type="checkbox" v-model="subscriptionData.consent_video">
                      <label class="form-checkbox-label text-body-display" for="subscription_consent_video" v-if="this.$appTheme == 'flofest'">{{ $t('forms.label.flofestConsentVideo') }}</label>
                      <label class="form-checkbox-label text-body-display" for="subscription_consent_video" v-else>{{ $t('forms.label.consentVideo') }}</label>
                  </div>
                   <p v-show="errors.has('consent_video')" class="form-msg-error">{{ errors.first('consent_video') }}</p>
              </div>
              <div class="form-group" v-bind:class="{'has-error' : errors.has('consent_rules')}">
                  <div class="form-checkbox-container">
                      <input id="subscription_consent_rules" class="form-checkbox-input" type="checkbox" v-model="subscriptionData.consent_rules">
                      <label class="form-checkbox-label text-body-display" for="subscription_consent_rules" v-if="this.$appTheme == 'flofest'">{{ $t('forms.label.flofestConsentRules') }}</label>
                       <label class="form-checkbox-label text-body-display" for="subscription_consent_rules" v-else>{{ $t('forms.label.consentRules') }}</label>
                  </div>
                   <p v-show="errors.has('consent_rules')" class="form-msg-error">{{ errors.first('consent_rules') }}</p>
              </div>
              <div class="form-group" v-if="this.$appTheme == 'flofest'">
                <a class="text-link text-footnote" target="_blank" :href="`${$t('dashboard.nav.flofestRulesLink')}`">
                    <icon icon="external" class="nav-icon"></icon>
                    <span>{{ $t('dashboard.nav.rules') }}</span>
                </a>
              </div>
              <div class="form-group" v-else>
                <a class="text-link text-footnote" target="_blank" :href="`${$t('dashboard.nav.rulesLink')}`">
                    <icon icon="external" class="nav-icon"></icon>
                    <span>{{ $t('dashboard.nav.rules') }}</span>
                </a>
              </div>
              <div class="form-actions">
                <div
                  class="btn btn-secondary"
                  @click.prevent="hideModalConsent"
                >{{ $t('forms.actions.cancel') }}</div>
                <button
                  class="btn btn-primary"
                  :disabled="this.saving"
                >{{ $t('forms.actions.submit') }}</button>
              </div>
            </section>
          </form>
        </div>
      </article>
    </modal>
  </div>
</template>
<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import { i18n } from "../plugins/i18n.js";

export default {
  data: function() {
    return {
      saving: false,
      routines: false,
      submitted: false,
      selectedSubscription: "",
      selectedRoutine: "",
      subscriptionData:{
        consent_video:'',
        consent_rules:''
      }
    };
  },
  beforeRouteEnter(to, from, next) {
    store
      .dispatch("subscriptions/show", to.params.id)
      .then(_ => {
        store
          .dispatch("dancers/check")
          .then(next)
          .catch(error =>
            store.dispatch("feedback/setFeedback", {
              message: error.data,
              msg,
              type: "warning"
            })
          );
      })
      .catch(error => {
        next({ name: "dashboard.index" });
        store.dispatch("feedback/setDelayedFeedback", {
          message: error.data.msg,
          type: "warning"
        });
      });
  },

  beforeRouteUpdate(to, from, next) {
    store
      .dispatch("subscriptions/show", to.params.id)
      .then(_ => {
        store
          .dispatch("dancers/check")
          .then(next)
          .catch(error =>
            store.dispatch("feedback/setFeedback", {
              message: error.data.msg,
              type: "warning"
            })
          );
      })
      .catch(error => {
        next({ name: "dashboard.index" });
        store.dispatch("feedback/setDelayedFeedback", {
          message: error.data.msg,
          type: "warning"
        });
      });
  },
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      subscription: "subscriptions/currentSubscription",
      subscriptions: "subscriptions/subscriptions",
      dancers: "dancers/check",
      subscriptionRoutines: "routines/subscriptionRoutines"
      // ...
    })
  },

  methods: {
    ...mapActions({
      destroyRoutine: "subscriptions/destroyRoutine",
      getSubscriptionRoutines: "routines/getSubscriptionRoutines",
      resetSubscriptionRoutines: "routines/resetSubscriptionRoutines",
      update: "subscriptions/update",
      setDelayedFeedback: "feedback/setDelayedFeedback",
      setFeedback: "feedback/setFeedback"
    }),
     showModalConsent(ev) {
      this.$modal.show("modal-consent");
    },
    getSubscriptionStatusID() {
      return this.subscription.status_id
    },
    hideModalConsent(ev) {
      this.$setLaravelValidationErrorsFromResponse([]);
      this.subscription.consent_video = '';
      this.subscription.consent_rules = ''
      this.$modal.hide("modal-consent");
    },
    showModalDuplicateRoutine(ev) {
      ev.currentTarget.parentNode.parentNode.classList.toggle("has-menu-open");
      this.$modal.show("duplicate-routine-modal");
    },
    hideModalDuplicateRoutine() {
      this.selectedSubscription = "";
      this.selectedRoutine = "";
      this.$modal.hide("duplicate-routine-modal");
    },
    closeActions(ev) {
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    changeStatus(id) {
      this.saving = true;

      this.update({
        inputs: {
          status_id: id,
          consent_video: this.subscriptionData.consent_video,
          consent_rules: this.subscriptionData.consent_rules,
        },
        id: this.subscription.id
      })
        .then(() => {
                this.$setLaravelValidationErrorsFromResponse([]);
                this.subscription.consent_video = '';
                this.subscription.consent_rules = ''
                this.$modal.hide("modal-consent");

          store.dispatch("subscriptions/show", this.subscription.id).then(() => {
              if(parseInt(id) == 2) {
              this.setFeedback({
              message: i18n.t("messages.subscription.submit"),
              type: "warning"
            });
            }
          });
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.data)
          if (!error.data.errors) {
            this.setFeedback({ message: error.data.msg, type: "warning" });
          }

          this.saving = false;
        })
        .then(_ => (this.saving = false));
    },
    // onSubmitSubcription(id) {
    //   this.saving = true;
    //   this.update({
    //     inputs: {
    //       status_id: id,
    //       consent_video: this.subscriptionData.consent_video,
    //       consent_rules: this.subscriptionData.consent_rules,
    //     },
    //     id: this.subscription.id
    //   })
    //     .then(() => {
    //       //console.log(this.results)
    //       // this.setDelayedFeedback({
    //       //   message: i18n.t("messages.subscription.submit"),
    //       //   type: "warning"
    //       // });
    //       // this.$router.push({
    //       //   name: "dashboard.index"
    //       // });
    //     })
    //     .catch(error => {
    //       this.$setLaravelValidationErrorsFromResponse(error.data)
    //       if (!error.data.errors) {
    //         this.setFeedback({ message: error.data.msg, type: "warning" });
    //       }

    //       this.saving = false;
    //     })
    //     .then(_ => (this.saving = false));
    // },
    deleteRoutine(ev, id) {
      if (window.confirm(i18n.t("messages.routine.deleteRoutine"))) {
        this.saving = true;
        this.destroyRoutine(id)
          .then(() => {
            //this.hide();
          })
          .catch(error => {
            this.saving = false;
          })
          .then(_ => (this.saving = false));
      }
    },
    onSelectChange(ev) {
      // Select value
      // el.options[el.selectedIndex].text
      let el = ev.currentTarget;

      if (!el.classList.contains("has-value")) {
        el.classList.add("has-value");
      }

      if (
        el.options[el.selectedIndex].text.length == 0 &&
        el.classList.contains("has-value")
      ) {
        el.classList.remove("has-value");
        el.blur();
        this.resetSubscriptionRoutines();
        document
          .getElementById("subscription_routines")
          .classList.remove("has-value");
      } else {
        this.getSubscriptionRoutines(el.options[el.selectedIndex].value)
          .then(() => {
            //this.hide();
            this.selectedRoutine = this.subscriptionRoutines[0].id;
            document
              .getElementById("subscription_routines")
              .classList.add("has-value");
          })
          .catch(error => {
            this.saving = false;
          })
          .then(_ => (this.saving = false));
      }
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

    //this.subscriptionData.consent_video = this.subscription.consent_video;
    //this.subscriptionData.consent_rules = this.subscription.consent_rules;
  }
};
</script>
<style lang="scss" scoped>
.form-routine-duplicate {
  width: 304px;
  margin: 0 auto 0 auto;
}
.form-submit-subscription {
  padding:0 2.4rem;
  margin: 0 auto 0 auto;
}
.form-actions {
  display: flex;
  justify-content: space-between;
}

.modal .text-subhead {
  width: 304px;
  margin-left: auto;
  margin-right: auto;
}
.card-col {
  width:auto;
  margin:0 2.4rem;
}
</style>