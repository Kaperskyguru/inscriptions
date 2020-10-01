<template>
<div>
    <div class="layout-wrapper">
        <public-header />
        <feedback></feedback>
        <h1 class="title-large">
            {{ $t('global.title.newPassword') }}
         </h1>
        <form autocomplete="off" novalidate class="form-login" @submit.prevent="onSubmit($event)" v-if="!success">
            <div class="form-group" v-bind:class="{'has-error' : errors.has('email')}">
                <div class="floating-label-container">
                    <input id="user_email" class="form-text-field-input" type="email" v-on:change="isEmpty"  v-model="user.email">
                    <label class="floating-label" for="user_email">{{ $t('forms.label.email') }}</label>
                </div>
                <p v-show="errors.has('email')" class="form-msg-error">{{ errors.first('email') }}</p>

            </div>
             <div class="form-group" v-bind:class="{'has-error' : errors.has('password')}">
                <div class="floating-label-container">
                    <input id="user_password" class="form-text-field-input" type="password" v-on:change="isEmpty"  v-model="user.password">
                    <label class="floating-label" for="user_password">{{ $t('forms.label.newPassword') }}</label>
                </div>
                <p v-show="errors.has('password')" class="form-msg-error">{{ errors.first('password') }}</p>

            </div>
            <div class="form-group" v-bind:class="{'has-error' : errors.has('password_confirmation')}">
                <div class="floating-label-container">
                    <input id="user_password_confirmation" class="form-text-field-input" type="password" v-on:change="isEmpty"  v-model="user.password_confirmation">
                    <label class="floating-label" for="user_password_confirmation">{{ $t('forms.label.confirmPassword') }}</label>
                </div>
                <p v-show="errors.has('password_confirmation')" class="form-msg-error">{{ errors.first('password_confirmation') }}</p>

            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" :disabled="saving">{{ $t('forms.actions.send') }}</button>
            </div>
        </form>
       
    </div>
</div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import Feedback from '../components/Feedback';
import PublicHeader from "../components/PublicHeader";
import { i18n } from '../plugins/i18n.js';
import { store } from '../store';


export default {
  data() {
    return {
    saving:false,
    success:false,
      user: {
        token: null,
        email: null,
        password: null,
        password_confirmation: null,
      }
    };
  },
  beforeRouteEnter (to, from, next) {
      store.dispatch('auth/logout')
        .then(next)
  }, 
    components: {
        Feedback,
        PublicHeader
    },
    computed: {
    // mix the getters into computed with object spread operator
        ...mapGetters({
        // ...
        })
        
    },
  methods: {
        ...mapActions({
            setFeedback: 'feedback/setFeedback',
            setDelayedFeedback: 'feedback/setDelayedFeedback',
            resetPassword: 'auth/resetPassword'
        }),
        onSubmit(event) {
            this.saving = true;
            this.resetPassword({
                token: this.$route.params.token,
                email: this.user.email,
                password: this.user.password,
                password_confirmation: this.user.password_confirmation
             })
            //.then(() => this.setDelayedFeedback({feedback: { message: 'Logged In', type: 'success'}}))
            .then(() => {
                //this.success = true;
                this.setDelayedFeedback({feedback: { message: i18n.t('messages.password.success'), type: 'login'}});
                return this.$router.push({path: '/'});
            })
            .catch(
                error => {
                    this.$setLaravelValidationErrorsFromResponse(error.data);
                    if (!error.data.errors) {
                        this.setFeedback({ message: error.data.msg, type: "login" });
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
  created() {
      this.loaded = true;
  }
};

</script>
<style lang="scss" scoped>
    .title-large {
        margin:0 0 8.8rem 0;
    }
    .form-login {
        width:304px;
        margin:0 auto 5.6rem auto;
    }
    .btn {
        margin:0 auto;
    }
    .text-link {
        text-align:center;
        margin:0 0 1.6rem 0;
    }
    .alert {
        width:304px;
    }
</style>
