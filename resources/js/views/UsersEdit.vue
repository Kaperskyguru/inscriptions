<template>
  <div>
    <div id="layout-dashboard">
        <h1 class="title-primary">{{ $t('dashboard.title.responsable') }}</h1>
        <feedback></feedback>
        <form class="form-user-edit" @submit.prevent="onSubmit($event)">
            <section class="form-step grid-4">
                <fieldset>
                  <div class="form-group" v-bind:class="{'has-error' : errors.has('name'), 'has-value' : this.user.name}">
                    <div class="floating-label-container">
                        <input id="user_name" class="form-text-field-input" v-bind:class="{'has-value' : this.user.name}" type="text" v-on:change="isEmpty"  v-model="user.name">
                            <label class="floating-label" for="user_name">{{ $t('forms.label.name') }}</label>
                        </div>
                    <p v-show="errors.has('name')" class="form-msg-error">{{ errors.first('name') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('email')}">
                    <div class="floating-label-container">
                        <input id="user_email" class="form-text-field-input" v-bind:class="{'has-value' : this.user.email}" type="email" v-on:change="isEmpty"  v-model="user.email">
                            <label class="floating-label" for="user_email">{{ $t('forms.label.email') }}</label>
                        </div>
                    <p v-show="errors.has('email')" class="form-msg-error">{{ errors.first('email') }}</p>
                </div>
                </fieldset>
                <fieldset class="zone-password">
                  <legend class="title-tertiary">{{ $t('dashboard.title.changePassword') }}</legend>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('password')}">
                    <div class="floating-label-container">
                        <input id="responsable_password" class="form-text-field-input" type="password" v-on:change="isEmpty"  v-model="responsable.password">
                            <label class="floating-label" for="responsable_password">{{ $t('forms.label.currentPassword') }}</label>
                        </div>
                    <p v-show="errors.has('password')" class="form-msg-error">{{ errors.first('password') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('new_password')}">
                    <div class="floating-label-container">
                        <input id="responsable_new_password" class="form-text-field-input" type="password" v-on:change="isEmpty"  v-model="responsable.new_password">
                            <label class="floating-label" for="responsable_new_password">{{ $t('forms.label.newPassword') }}</label>
                        </div>
                    <p v-show="errors.has('new_password')" class="form-msg-error">{{ errors.first('new_password') }}</p>
                </div>
                <div class="form-group" v-bind:class="{'has-error' : errors.has('new_password_confirmation')}">
                    <div class="floating-label-container">
                        <input id="responsable_new_password_confirmation" class="form-text-field-input" type="password" v-on:change="isEmpty"  v-model="responsable.new_password_confirmation">
                            <label class="floating-label" for="responsable_new_password">{{ $t('forms.label.confirmPassword') }}</label>
                        </div>
                    <p v-show="errors.has('new_password_confirmation')" class="form-msg-error">{{ errors.first('new_password_confirmation') }}</p>
                </div>
                </fieldset>
            </section>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit" :disabled="saving">{{ $t('forms.actions.save') }}</button>
            </div>
            </form>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import Feedback from '../components/Feedback';
import { i18n } from '../plugins/i18n.js';



export default {
  data() {
    return {
    saving:false,
    responsable: {
      password:null,
      new_password:null,
      confirm_new_password:null,
    }
     
    };
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      isAdmin: 'auth/isAdmin',
      // ...
    })
  },
  methods: {
    ...mapActions({
            update: 'auth/update',
            setFeedback: 'feedback/setFeedback',
        }),
    onSubmit(event) {
      this.saving = true;
       this.update({
            name: this.user.name,
            email: this.user.email,
            password: this.responsable.password,
            new_password: this.responsable.new_password,
            new_password_confirmation: this.responsable.new_password_confirmation,
        })
        .then(() => {
            this.setFeedback({ message: i18n.t('messages.global.success'), type: "warning" });
             this.$setLaravelValidationErrorsFromResponse([]);
            this.saving = false;
            this.responsable.password = '';
            this.responsable.new_password = '';
            this.responsable.new_password_confirmation = '';
        })
        .catch(
            error => {
              this.$setLaravelValidationErrorsFromResponse(error.data);
                if (!error.data.errors) {
                    this.setFeedback({ message: error.data.msg, type: "warning" });
                }
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
  },
  components: {
    Feedback
  },
  created() {
    
  }
};
</script>
<style lang="scss" scoped>
    .form-actions {
        display:flex;
        justify-content: flex-end;
    }
    .zone-password {
      margin:5.6rem 0 0 0;
    }
</style>
