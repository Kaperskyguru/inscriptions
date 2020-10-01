<template>
  <div>
    <div class="layout-wrapper">
      <public-header />
      <h1 class="title-large" v-if="step === 1">{{ $t('global.title.create') }}</h1>
      <feedback></feedback>
      <form autocomplete="off" class="form-user-add" @submit.prevent="onSubmit($event)">
        <section class="form-step" data-step="1" v-bind:class="{'visuallyhidden' : step != 1}">
          <div class="form-group" v-bind:class="{'has-error' : errors.has('firstname')}">
            <div class="floating-label-container">
              <input
                id="user_firstname"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.firstname"
              />
              <label class="floating-label" for="user_firstname">{{ $t('forms.label.firstnameResponsable') }}</label>
            </div>
            <p
              v-show="errors.has('firstname')"
              class="form-msg-error"
            >{{ errors.first('firstname') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('lastname')}">
            <div class="floating-label-container">
              <input
                id="user_lastname"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.lastname"
              />
              <label class="floating-label" for="user_firstname">{{ $t('forms.label.lastnameResponsable') }}</label>
            </div>
            <p v-show="errors.has('lastname')" class="form-msg-error">{{ errors.first('lastname') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('email')}">
            <div class="floating-label-container">
              <input
                id="user_email"
                class="form-text-field-input"
                type="email"
                v-on:change="isEmpty"
                v-model="user.email"
              />
              <label class="floating-label" for="user_email">{{ $t('forms.label.email') }}</label>
            </div>
            <p v-show="errors.has('email')" class="form-msg-error">{{ errors.first('email') }}</p>
          </div>

          <div class="form-group" v-bind:class="{'has-error' : errors.has('password')}">
            <div class="floating-label-container">
              <input
                id="user_password"
                class="form-text-field-input"
                type="password"
                v-on:change="isEmpty"
                v-model="user.password"
              />
              <label class="floating-label" for="user_password">{{ $t('forms.label.password') }}</label>
            </div>
            <p v-show="errors.has('password')" class="form-msg-error">{{ errors.first('password') }}</p>
          </div>
        </section>
        <section class="form-step" data-step="2" v-bind:class="{'visuallyhidden' : step != 2}">
          <div class="form-group">
            <h3 class="title-tertiary">{{ $t('forms.title.organization') }}</h3>
          </div>
          <div class="form-group">
            <div class="form-radio-container">
              <input
                id="organization_type_school"
                class="form-radio-input"
                type="radio"
                v-model="user.organizationTypeId"
                value="1"
              />
              <label
                class="form-radio-label text-body-display"
                for="organization_type_school"
              >{{ $t('forms.label.school') }}</label>
            </div>
            <div class="form-radio-container">
              <input
                id="organization_type_group"
                class="form-radio-input"
                type="radio"
                v-model="user.organizationTypeId"
                value="2"
              />
              <label
                class="form-radio-label text-body-display"
                for="organization_type_group"
              >{{ $t('forms.label.group') }}</label>
            </div>
            <div class="form-radio-container">
              <input
                id="organization_type_dancer"
                class="form-radio-input"
                type="radio"
                v-model="user.organizationTypeId"
                value="3"
              />
              <label
                class="form-radio-label text-body-display"
                for="organization_type_dancer"
              >{{ $t('forms.label.dancer') }}</label>
            </div>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationName')}">
            <div class="floating-label-container">
              <input
                id="organization_name"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.organizationName"
              />
              <label
                class="floating-label"
                for="organization_name"
              >{{ $t('forms.label.organizationName') }}</label>
            </div>
            <p
              v-show="errors.has('organizationName')"
              class="form-msg-error"
            >{{ errors.first('organizationName') }}</p>
          </div>
        </section>
        <section class="form-step" data-step="3" v-bind:class="{'visuallyhidden' : step != 3}">
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationAddress')}">
            <div class="floating-label-container">
              <input
                id="organization_address"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.organizationAddress"
              />
              <label
                class="floating-label"
                for="organization_address"
              >{{ $t('forms.label.organizationAddress') }}</label>
            </div>
            <p
              v-show="errors.has('organizationAddress')"
              class="form-msg-error"
            >{{ errors.first('organizationAddress') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationCity')}">
            <div class="floating-label-container">
              <input
                id="organization_city"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.organizationCity"
              />
              <label
                class="floating-label"
                for="organization_city"
              >{{ $t('forms.label.organizationCity') }}</label>
            </div>
            <p
              v-show="errors.has('organizationCity')"
              class="form-msg-error"
            >{{ errors.first('organizationCity') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationCountry')}">
            <div class="floating-label-container">
              <select id="organization_country" v-on:change="onSelectChange" class="form-select has-value" v-model="user.organizationCountry">
                <option value="2">Canada</option>
              </select>
             <label
                class="floating-label"
                for="organization_country"
              >{{ $t('forms.label.country') }}</label>
            </div>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationState')}">
            <div class="floating-label-container">
              <select id="organization_state" v-on:change="onSelectChange" class="form-select has-value" v-model="user.organizationState">
                <option v-for="option in this.states" v-bind:key="option.id"  v-bind:value="option.id">
                  {{option.name}}
                </option>
              </select>
             <label
                class="floating-label"
                for="organization_state"
              >{{ $t('forms.label.state') }}</label>
            </div>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationZipcode')}">
            <div class="floating-label-container">
              <input
                id="organization_zipcode"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.organizationZipcode"
              />
              <label
                class="floating-label"
                for="organization_zipcode"
              >{{ $t('forms.label.organizationZipcode') }}</label>
            </div>
            <p
              v-show="errors.has('organizationZipcode')"
              class="form-msg-error"
            >{{ errors.first('organizationZipcode') }}</p>
          </div>
          <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationPhone')}">
            <div class="floating-label-container">
              <input
                id="organization_phone"
                class="form-text-field-input"
                type="text"
                v-on:change="isEmpty"
                v-model="user.organizationPhone"
              />
              <label
                class="floating-label"
                for="organization_phone"
              >{{ $t('forms.label.organizationPhone') }}</label>
            </div>
            <p
              v-show="errors.has('organizationPhone')"
              class="form-msg-error"
            >{{ errors.first('organizationPhone') }}</p>
          </div>
          <div class="form-group">
              <div class="form-radio-container">
                  <input id="organization_locale_fr" class="form-radio-input" type="radio" v-model="user.organizationLocale" value="fr">
                  <label class="form-radio-label text-body-display" for="organization_locale_fr">{{ $t('global.text.localeFr') }}</label>
              </div>
              <div class="form-radio-container">
                  <input id="organization_locale_en" class="form-radio-input" type="radio" v-model="user.organizationLocale" value="en">
                  <label class="form-radio-label text-body-display" for="organization_locale_en">{{ $t('global.text.localeEn') }}</label>
              </div>
          </div>
        </section>
        <div class="form-actions">
          <div
            class="btn btn-secondary"
            v-on:click.prevent="back"
            v-if="step == 1"
          >{{ $t('forms.actions.back') }}</div>
          <div
            class="btn btn-secondary"
            v-on:click.prevent="onClickPrevStep"
            v-else
          >{{ $t('forms.actions.back') }}</div>
          <button
            class="btn btn-primary"
            v-on:click.prevent="onClickNextStep"
            v-if="step <= 2"
          >{{ $t('forms.actions.next') }}</button>

          <button
            class="btn btn-primary"
            type="submit"
            :disabled="saving"
            v-if="step === 3"
          >{{ $t('forms.actions.done') }}</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
//import api from "../api/users";

import { mapActions, mapGetters } from "vuex";
import PublicHeader from "../components/PublicHeader";
import { store } from '../store';
import Feedback from '../components/Feedback';



export default {
  name: "register",
  data() {
    return {
      saving: false,
      user: {
        id: null,
        firstname: "",
        lastname: "",
        email: "",
        organizationName: "",
        organizationAddress: "",
        organizationCity: "",
        organizationCountry: "2",
        organizationState : "57",
        organizationZipcode: "",
        organizationPhone: "",
        organizationLocale: "",
        organizationTypeId: "1"
      }
    };
  },
  beforeRouteEnter (to, from, next) {
       
      store.dispatch('states/getByCountryID', 2)
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
    },
  components: {
    PublicHeader,
    Feedback
  },
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      step: "validate/getStep",
      states: "states/getStates"

      // ...
    })
  },
  methods: {
    ...mapActions({
      setFeedback: "feedback/setFeedback",
      setDelayedFeedback: "feedback/setDelayedFeedback",
      register: "auth/register",
      nextStep: "validate/nextStep",
      prevStep: "validate/prevStep",
      resetStep: "validate/prevStep",
    }),
    onClickPrevStep(event) {
      this.prevStep();
    },
    onClickNextStep(event) {
      this.nextStep({
        firstname: this.user.firstname,
        lastname: this.user.lastname,
        email: this.user.email,
        password: this.user.password,
        organizationName: this.user.organizationName,
        organizationAddress: this.user.organizationAddress,
        organizationCity: this.user.organizationCity,
        organizationCountry: this.user.organizationCountry,
        organizationState: this.user.organizationState,
        organizationZipcode: this.user.organizationZipcode,
        organizationPhone: this.user.organizationPhone,
        organizationLocale: this.user.organizationLocale,
        organizationTypeId: this.user.organizationTypeId
      })
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.data);
          this.saving = false;
        })
        .then(_ => (this.saving = false));
    },
    onSubmit(event) {
      this.saving = true;
      this.register({
        firstname: this.user.firstname,
        lastname: this.user.lastname,
        email: this.user.email,
        password: this.user.password,
        organizationName: this.user.organizationName,
        organizationAddress: this.user.organizationAddress,
        organizationCity: this.user.organizationCity,
        organizationCountry: this.user.organizationCountry,
        organizationState: this.user.organizationState,
        organizationZipcode: this.user.organizationZipcode,
        organizationPhone: this.user.organizationPhone,
        organizationLocale: this.user.organizationLocale,
        organizationTypeId: this.user.organizationTypeId
      })
        //.then(() => this.setDelayedFeedback({feedback: { message: 'Logged In', type: 'success'}}))
        //.then(() => this.$router.push({ path: "dashboard.index" }))
        .then(() => window.location.href = "/" + this.user.organizationLocale)
        .catch(error => {
          this.$setLaravelValidationErrorsFromResponse(error.data);
          this.saving = false;
        })
        .then(_ => (this.saving = false));
    },
    onSelectChange(ev) {
      // Select value
      let el = ev.currentTarget

      if(!el.classList.contains('has-value')) {
        el.classList.add("has-value");
      }
     

    },
    isEmpty(ev) {
        if (ev.currentTarget.value.length > 0) {
        ev.currentTarget.classList.add("has-value");
      } else {
        ev.currentTarget.classList.remove("has-value");
        //document.getElementById("myDiv").classList.remove("form-group");
      }
    },
    back() {
      this.$router.go(-1);
    }
  },
  created() {

    this.resetStep();
    this.user.organizationLocale = window.locale;

  }
};
</script>
<style lang="scss" scoped>
.form-user-add {
  width: 304px;
  margin: 0 auto 5.6rem auto;
}
.form-actions {
  display: flex;
  justify-content: space-between;
}
</style>
