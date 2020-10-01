<template>
   <div id="layout-dashboard">
        <feedback></feedback>
        <h1 class="title-primary">{{ $t('dashboard.title.organization') }}</h1>
        <form autocomplete="off" class="form-create" @submit.prevent="onSubmit($event)">
             <section class="form-step grid-4" data-step="1">
                <div class="form-group" v-bind:class="{'has-error' : errors.has('name')}">
                    <div class="floating-label-container">
                        <input id="organization_name" class="form-text-field-input" v-bind:class="{'has-value' : this.organization.name}" type="text" v-on:change="isEmpty"  v-model="organization.name">
                            <label class="floating-label" for="organization_name">{{ $t('forms.label.organizationName') }}</label>
                        </div>
                    <p v-show="errors.has('name')" class="form-msg-error">{{ errors.first('name') }}</p>
                </div>
                 <div class="form-group" v-bind:class="{'has-error' : errors.has('address')}">
                    <div class="floating-label-container">
                        <input id="organization_address" class="form-text-field-input" v-bind:class="{'has-value' : this.organization.address}" type="text" v-on:change="isEmpty"  v-model="organization.address">
                            <label class="floating-label" for="organization_address">{{ $t('forms.label.organizationAddress') }}</label>
                        </div>
                    <p v-show="errors.has('address')" class="form-msg-error">{{ errors.first('address') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('city')}">
                    <div class="floating-label-container">
                        <input id="organization_city" class="form-text-field-input" v-bind:class="{'has-value' : this.organization.city}" type="text" v-on:change="isEmpty"  v-model="organization.city">
                            <label class="floating-label" for="organization_city">{{ $t('forms.label.organizationCity') }}</label>
                        </div>
                    <p v-show="errors.has('city')" class="form-msg-error">{{ errors.first('city') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('organizationCountry')}">
                    <div class="floating-label-container">
                    <select id="organization_country" v-on:change="onSelectChange" class="form-select has-value" v-model="organization.country_id">
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
                    <select id="organization_state" v-on:change="onSelectChange" class="form-select has-value" v-model="organization.state_id">
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
                <div class="form-group" v-bind:class="{'has-error' : errors.has('zipcode')}">
                    <div class="floating-label-container">
                        <input id="organization_zipcode" class="form-text-field-input" v-bind:class="{'has-value' : this.organization.zipcode}" type="text" v-on:change="isEmpty"  v-model="organization.zipcode">
                            <label class="floating-label" for="organization_zipcode">{{ $t('forms.label.organizationZipcode') }}</label>
                        </div>
                    <p v-show="errors.has('zipcode')" class="form-msg-error">{{ errors.first('zipcode') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('phone')}">
                    <div class="floating-label-container">
                        <input id="organization_phone" class="form-text-field-input" v-bind:class="{'has-value' : this.organization.phone}" type="text" v-on:change="isEmpty"  v-model="organization.phone">
                            <label class="floating-label" for="organization_phone">{{ $t('forms.label.organizationPhone') }}</label>
                        </div>
                    <p v-show="errors.has('phone')" class="form-msg-error">{{ errors.first('phone') }}</p>
                </div>
                <div class="form-group">
                    <div class="form-radio-container">
                        <input id="organization_locale_fr" class="form-radio-input" type="radio" v-model="organization.locale" value="fr">
                        <label class="form-radio-label text-body-display" for="organization_locale_fr">{{ $t('global.text.localeFr') }}</label>
                    </div>
                    <div class="form-radio-container">
                        <input id="organization_locale_en" class="form-radio-input" type="radio" v-model="organization.locale" value="en">
                        <label class="form-radio-label text-body-display" for="organization_locale_en">{{ $t('global.text.localeEn') }}</label>
                    </div>
                </div>
            </section>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" :disabled="saving">{{ $t('forms.actions.save') }}</button>
            </div>
        </form>
    </div>
</template>
<script>
//import api from "../api/users";
import { mapActions, mapGetters } from 'vuex';
import Feedback from '../components/Feedback';
import { i18n } from '../plugins/i18n.js';
import { store } from '../store';



export default {
  data() {
    return {
      saving: false,
      organization: {
         name: null,
         address: null,
         city: null,
         zipcode: null,
         phone: null,
         locale: null,
         country_id: null,
         state_id: null,
      }
      
    };
  },
  beforeRouteEnter (to, from, next) {
       
      store.dispatch('states/getByCountryID', 2)
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
    },
  components: {
      Feedback
  },
  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      user: 'auth/user',
      isAdmin: 'auth/isAdmin',
      states: "states/getStates"
      // ...
    })
    
  },
  methods: {
      ...mapActions({
            update: 'organizations/update',
            authenticate: 'auth/authenticate',
            setFeedback: 'feedback/setFeedback',
        }),
      onSubmit(event) {
      this.saving = true;

       this.update({
                name: this.organization.name,
                address: this.organization.address,
                city: this.organization.city,
                zipcode: this.organization.zipcode,
                phone: this.organization.phone,
                website: this.organization.website,
                locale: this.organization.locale,
                country_id: this.organization.country_id,
                state_id: this.organization.state_id
             })
             .then(() => {
                 this.authenticate();
                 this.setFeedback({message: i18n.t('messages.global.success'), type: 'success'});
                 //this.$router.push({ name: "dashboard.index" });
             })
            .catch(
                error => {
                    this.$setLaravelValidationErrorsFromResponse(error.data)
                    this.saving = false;
            })
            .then(_ => (this.saving = false));
    },
    isEmpty(ev) {
        if(ev.currentTarget.value.length > 0){
            ev.currentTarget.classList.add("has-value");
        } else {
            ev.currentTarget.classList.remove("has-value");
            //document.getElementById("myDiv").classList.remove("form-group");
        }
    },
    onSelectChange(ev) {
      // Select value
      // el.options[el.selectedIndex].text
      let el = ev.currentTarget

      if(!el.classList.contains('has-value')) {
        el.currentTarget.classList.add("has-value");
      }
      

    },
  },
  created() {
      this.organization.name = this.user.organization.name;
      this.organization.address = this.user.organization.address;
      this.organization.city = this.user.organization.city;
      this.organization.phone = this.user.organization.phone;
      this.organization.zipcode = this.user.organization.zipcode;
      this.organization.locale = this.user.organization.locale;
      this.organization.country_id = this.user.organization.country_id;
      this.organization.state_id = this.user.organization.state_id;
  }
};
</script>
<style lang="scss" scoped>
    .form-actions {
        display:flex;
        justify-content: flex-end;
    }
</style>
