<template>
<div>
    <div class="layout-wrapper">
        <public-header />
        <h1 class="title-large">
            {{ $t('global.title.login') }}
         </h1>
         <feedback></feedback>
        <!-- <div v-if="message" class="alert">{{ message }}</div>
        <div v-if="! loaded">Loading...</div> -->
        <form class="form-login" @submit.prevent="onSubmit($event)">
            <div class="form-group" v-bind:class="{'has-error' : errors.has('email')}">
                <div class="floating-label-container">
                    <input id="user_email" class="form-text-field-input" type="email" v-on:change="isEmpty"  v-model="user.email">
                    <label class="floating-label" for="user_email">{{ $t('forms.label.email') }}</label>
                </div>
                <p v-show="errors.has('email')" class="form-msg-error">{{ errors.first('email') }}</p>

            </div>

            <div class="form-group" v-bind:class="{'has-error' : errors.has('password')}">
                <div class="floating-label-container">
                    <input id="user_password" class="form-text-field-input" type="password" v-on:change="isEmpty" v-model="user.password">
                    <label class="floating-label" for="user_password">{{ $t('forms.label.password') }}</label>
                </div>
                <p v-show="errors.has('password')" class="form-msg-error">{{ errors.first('password') }}</p>

            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" :disabled="saving">{{ $t('forms.actions.login') }}</button>
            </div>
        </form>
        <router-link :to="{ name: 'users.create' }" class="text-link">{{ $t('global.text.signup') }}</router-link>
        <!-- <a href="#" class="text-link"></a> -->
        <router-link :to="{ name: 'users.forgot' }" class="text-link">{{ $t('global.text.forgetPassword') }}</router-link>
        <a class="text-link" :href="`/${$t('global.text.mirrorLocale')}`">{{ $t('global.text.mirror') }}</a>
    </div>
</div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import Auth from '../services/Auth';
import Feedback from '../components/Feedback';
import PublicHeader from "../components/PublicHeader";

export default {
  name: 'login',
  data() {
    return {
    saving:false,
      user: {
        name: "",
        email: ""
      }
    };
  },
    components: {
        Feedback,
        PublicHeader
    },
    computed: {
    // mix the getters into computed with object spread operator
        ...mapGetters({
            isAdmin: 'auth/isAdmin',
        // ...
        })
        
    },
  methods: {
        ...mapActions({
            setFeedback: 'feedback/setFeedback',
            setDelayedFeedback: 'feedback/setDelayedFeedback',
            login: 'auth/login'
        }),
        onSubmit(event) {
            this.saving = true;
            this.login({
                 email: this.user.email,
                 password: this.user.password,
             })
            //.then(() => this.setDelayedFeedback({feedback: { message: 'Logged In', type: 'success'}}))
            .then(() => {
                if(this.isAdmin) {
                    this.$router.push({ name: "admin.index" });
                }else {
                    this.$router.push({ name: "dashboard.index" });
                }

            })
            .catch(
                error => {
                    this.$setLaravelValidationErrorsFromResponse(error.data);
                    if (!error.data.errors) {
                        this.setFeedback({ message: error.data.msg, type: "login" });
                    }

                    this.saving = false;
            });
			
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
        justify-content: center;
        margin:0 0 1.6rem 0;
    }
    .alert {
        width:304px;
    }
</style>
