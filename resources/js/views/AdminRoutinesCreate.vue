<template>
  <div>
    <div id="layout-dashboard">
      <feedback></feedback>
      <h1 class="title-primary">{{ $t('dashboard.title.routinesAdd') }}</h1>
      <div v-if="message" class="alert">{{ message }}</div>
      <form autocomplete="off" class="form-user-add" @submit.prevent="onSubmit($event)">
        <section class="form-step" data-step="1">
          <div class="form-group grid-4" v-bind:class="{'has-error' : errors.has('name')}">
            <p class="formtitle-tertiary">{{ $t('forms.label.routineName') }}</p>
            <div class="floating-label-container">
              <input
                id="routine_name"
                class="form-text-field-input"
                type="text"
                @change="isEmpty"
                v-model="routine.name"
                autocomplete="off"
              />
              <label class="floating-label" for="routine_name">{{ $t('forms.label.routineName') }}</label>
            </div>
            <p v-show="errors.has('name')" class="form-msg-error">{{ errors.first('name') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('level_id')}">
            <p class="formtitle-tertiary">{{ $t('forms.label.level') }}</p>
            <div class="form-radio-button-container">
              <div
                class="form-radio-control tooltip-container"
                v-for="level in this.routineData.levels"
                v-bind:key="level.id"
              >
                <input
                  :id="`routine_level_${level.id}`"
                  class="form-radio-input"
                  type="radio"
                  v-model="routine.level_id"
                  :value="`${level.id}`"
                />
                <label
                  class="form-radio-button-label tooltip-trigger"
                  :for="`routine_level_${level.id}`"
                >{{level.name}}</label>
                <div class="tooltip" v-if="level.description || level.note">
                    <p class="tooltip-text text-footnote" v-if="level.description">{{level.description?level.description:''}}</p>
                    <p class="tooltip-note text-footnote" v-if="level.note">{{level.note ? level.note:''}}</p>
                </div>
              </div>
            </div>
            <p v-show="errors.has('level_id')" class="form-msg-error">{{ errors.first('level_id') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('style_id')}">
            <p class="formtitle-tertiary">{{ $t('forms.label.style') }}</p>
            <div class="form-radio-button-container">
              <div
                class="form-radio-control tooltip-container"
                v-for="style in this.routineData.styles"
                v-bind:key="style.id"
              >
                <input
                  :id="`routine_style_${style.id}`"
                  class="form-radio-input"
                  type="radio"
                  v-model="routine.style_id"
                  :value="`${style.id}`"
                />
                <label
                  class="form-radio-button-label tooltip-trigger"
                  :for="`routine_style_${style.id}`"
                >{{style.name}}</label>
                <div class="tooltip">
                  <p class="tooltip-text text-footnote ">{{style.description}}</p>
                  <p class="tooltip-note text-footnote">{{style.note}}</p>
                </div>
              </div>
            </div>
            <p v-show="errors.has('style_id')" class="form-msg-error">{{ errors.first('style_id') }}</p>
          </div>
          <div class="form-group grid-4" v-bind:class="{'has-error' : errors.has('teacher')}">
            <p class="formtitle-tertiary">{{ $t('forms.label.teacher') }}</p>
            <div class="floating-label-container">
              <input
                id="routine_teacher"
                class="form-text-field-input"
                type="text"
                @change="isEmpty"
                v-model="routine.teacher"
              />
              <label class="floating-label" for="routine_teacher">{{ $t('forms.label.teacher') }}</label>
            </div>
            <p v-show="errors.has('teacher')" class="form-msg-error">{{ errors.first('teacher') }}</p>
          </div>
          <div class="form-group grid-4" v-bind:class="{'has-error' : errors.has('dancers')}">
            <p class="formtitle-tertiary">{{ $t('forms.label.dancers') }}</p>
            <div class="floating-label-container">
              <input
                id="autocomplete_input"
                class="form-text-field-input"
                type="text"
                @keyup="autoComplete"
                @change="isEmpty"
                v-model="query"
              />
              <label class="floating-label" for="autocomplete_input">{{ $t('forms.label.dancers') }}</label>
              <div class="autocomplete" v-if="this.results.length">
                <ul class="autocomplete-results">
                  <li
                    :class="`autocomplete-item autocomplete-item-${result.id}`"
                    @click.prevent="addDancer($event, result)"
                    v-for="result in results"
                    v-bind:key="result.id"
                  >
                    <span class="autocomplete-name text-subhead">{{ result.name }}</span>
                    <span class="autocomplete-age text-subhead">{{ result.age }} {{ $t('dashboard.text.age') }}</span>
                  </li>
                  <li
                    class="autocomplete-item"
                    @click.prevent="showModalDancer"
                  >
                    <span class="autocomplete-name text-subhead">+{{  $t('dashboard.label.add.newDancer') }}</span>
                  </li>
                </ul>
              </div>
            </div>
            <p v-show="errors.has('dancers')" class="form-msg-error">{{ errors.first('dancers') }}</p>
            <ul class="routine-dancers">
              <li
                class="routine-dancer-item"
                v-for="dancer in this.routine.dancers"
                v-bind:key="dancer.id"
              >
                <div class="routine-dancer-info">
                  <span class="routine-dancer-name text-body">{{dancer.name}}</span>
                  <span
                    class="routine-dancer-age text-body"
                  >{{dancer.age}} {{ $t('dashboard.text.age') }}</span>
                </div>
                <div class="routine-dancer-delete" @click.prevent="removeDancer($event, dancer.id)">
                  <icon icon="trash" class></icon>
                </div>
                <input
                  type="hidden"
                  :id="`routine_dancer_${dancer.id}`"
                  name="routineDancers"
                  :value="`${dancer.id}`"
                />
              </li>
            </ul>
          </div>
          <div class="form-actions form-actions-fixed">
            <div
              class="btn btn-secondary"
              v-on:click.prevent="back"
            >{{ $t('forms.actions.cancel') }}</div>
            <button
              class="btn btn-primary"
              type="submit"
              :disabled="saving"
            >{{ $t('forms.actions.save') }}</button>
          </div>
        </section>
      </form>
    </div>
   <modal name="dancer" :classes="'modal'" height="auto" >
            <article class="modal-inner">
                <header class="modal-header">
                    <h1 class="modal-title title-primary">{{$t('forms.title.add.dancer')}}</h1>
                    <span class="modal-close" @click.prevent="hide">
                        <icon icon="close" class=""></icon>
                    </span>
                </header>
                <div class="modal-body">
                    <form autocomplete="off" class="form-dancer-add" @submit.prevent="onSubmitDancer($event)">
                        <section class="form-step" data-step="1">
                            <div class="form-group" v-bind:class="{'has-error' : errors.has('first_name')}">
                                <div class="floating-label-container">
                                    <input id="dancer_first_name" class="form-text-field-input" v-bind:class="{'has-value' : this.dancer.first_name}" type="text" @change="isEmpty"  v-model="dancer.first_name">
                                    <label class="floating-label" for="dancer_first_name">{{ $t('forms.label.firstname') }}</label>
                                </div>
                                <p v-show="errors.has('first_name')" class="form-msg-error">{{ errors.first('first_name') }}</p>
                            </div>
                            <div class="form-group" v-bind:class="{'has-error' : errors.has('last_name')}">
                                <div class="floating-label-container">
                                    <input id="dancer_last_name" class="form-text-field-input" v-bind:class="{'has-value' : this.dancer.last_name}" type="text" @change="isEmpty"  v-model="dancer.last_name">
                                    <label class="floating-label" for="dancer_last_name">{{ $t('forms.label.lastname') }}</label>
                                </div>
                                <p v-show="errors.has('last_name')" class="form-msg-error">{{ errors.first('last_name') }}</p>
                            </div>
                            <div class="form-group" v-bind:class="{'has-error' : errors.has('date_of_birth')}">
                                <div class="floating-label-container">
                                    <cleave v-model="dancer.date_of_birth" :raw="false" :options="{ date: true, delimiter: '-', datePattern: ['Y', 'm', 'd']}" id="dancer_date_of_birth" class="form-text-field-input" v-bind:class="{'has-value' : this.dancer.date_of_birth}" name="date_of_birth"  v-on:change.native="isEmpty"></cleave>
                                    <label class="floating-label" for="dancer_date_of_birth">{{ $t('forms.label.birthday') }}</label>
                                </div>
                                <p class="form-hint text-caption">AAAA/MM/JJ</p>
                                <p v-show="errors.has('date_of_birth')" class="form-msg-error">{{ errors.first('date_of_birth') }}</p>
                            </div>
                            <div class="form-actions">
                                <div class="btn btn-secondary" @click.prevent="hide">{{ $t('forms.actions.cancel') }}</div>
                                <button class="btn btn-primary" type="submit" :disabled="saving">{{ $t('forms.actions.done') }}</button>
                            </div>
                        </section>
                    </form>
                </div>

            </article>
        </modal>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";

export default {
  data() {
    return {
      message: null,
      loaded: false,
      saving: false,
      query: "",
      //results: [],
      routine: {
        name: "",
        teacher: "",
        level_id: "1",
        style_id: "1",
        teacher: "",
        dancers: []
      },
      dancer:{
          id:'',
          first_name: '',
          last_name: '',
          date_of_birth: '',
      }
    };
  },
  beforeRouteEnter(to, from, next) {
   
     store
        .dispatch("routines/create")
        .then(next)
        .catch(error =>
          store.dispatch("feedback/setDelayedFeedback", {
            message: error.data,msg,
            type: "warning"
          })
        );
   
  },
   beforeRouteLeave (to, from, next) {
    this.query = "";
    this.resetSearch();
    next();
  },
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      routineData: "routines/getRoutineData",
      results: "dancers/results",
      lastDancer: "dancers/lastDancer",
    }),
    subscription_id() {
      return this.$route.params.id;
    },
    organization_id() {
      return this.$route.params.organization_id;
    },
    event_name() {
      return this.$route.params.event;
    }
  },
  methods: {
    ...mapActions({
      search: "dancers/search",
      store: "routines/store",
      storeDancer: 'dancers/store',
      resetSearch: "dancers/resetSearch",
      setFeedback: "feedback/setFeedback"
      //setDelayedFeedback: 'feedback/setDelayedFeedback',
    }),
    addDancer(ev, dancer) {
      if (dancer.id !== 0) {
        this.routine.dancers.push(dancer);
        this.query = "";
        this.resetSearch();
      }
    },
    showModalDancer(ev) {
        this.query = "";
        this.resetSearch()
        this.$modal.show('dancer');
    },
    hide () {
        this.$modal.hide('dancer');
        this.dancer.first_name = '';
        this.dancer.last_name = '';
        this.dancer.date_of_birth = '';
        //this.$setLaravelValidationErrorsFromResponse([]);
    },
    removeDancer(ev, id) {
      let i = this.routine.dancers.map(item => item.id).indexOf(id);
      this.routine.dancers.splice(i, 1);
    },
    onSubmitDancer(ev) {
      this.storeDancer({
          first_name: this.dancer.first_name,
          last_name: this.dancer.last_name,
          date_of_birth: this.dancer.date_of_birth,
          organization_id: this.organization_id
      })
      .then(() => {

        this.addDancer({}, this.lastDancer)
        this.hide();
      })
      .catch(
          error => {
              this.$setLaravelValidationErrorsFromResponse(error.data)
              this.saving = false;
      })
      .then(_ => (this.saving = false));
    },
    onSubmit(ev) {
      this.saving = true;
      //this.routine.dancers
      this.store({
        name: this.routine.name,
        teacher: this.routine.teacher,
        dancers: this.routine.dancers,
        style_id: this.routine.style_id,
        level_id: this.routine.level_id,
        subscription_id: parseInt(this.subscription_id),
        dancers: this.routine.dancers,
        organization_id: parseInt(this.organization_id)
        //query: this.query,
      })
        .then(() => {
          this.$router.push({
            name: "admin.subscription",
            params: { event: this.event_name, subscription_id: parseInt(this.subscription_id) }
          });
        })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.data);
          if (!error.data.errors) {
            this.setFeedback({ message: error.data.msg, type: "warning" });
          }

          this.saving = false;
        })
        .then(_ => (this.saving = false));
    },
    autoComplete(ev) {
      if (this.query.length > 1) {
        this.search({
          query: this.query,
          dancers: this.routine.dancers,
          organization_id: this.organization_id
        })
          .then(() => {
            //console.log(this.results)
            //this.$router.push({ name: "dashboard.index" });
          })
          .catch(error => {
            //this.$setLaravelValidationErrorsFromResponse(error.data)
            //this.saving = false;
          });
        //.then(_ => (this.saving = false));
      } else {
        this.resetSearch();
      }
    },
    isEmpty(ev) {
      if (ev.currentTarget.value.length > 0) {
        ev.currentTarget.classList.add("has-value");
      } else {
        ev.currentTarget.classList.remove("has-value");
      }
    },
    back() {
      this.$router.go(-1);
    }
  },
  components: {
    Icon,
    Feedback
  },

  created() {
    
  }
};
</script>
<style lang="scss" scoped>
    .form-dancer-add {
        width:304px;
        margin:0 auto 0 auto;
    }
    .form-actions {
        display:flex;
        justify-content: space-between;
    }
</style>

