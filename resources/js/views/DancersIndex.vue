<template>
   <div id="layout-dashboard">
       <feedback></feedback>
       <div class="alert" v-if="checkSubscriptionStatus()">
           {{$t('messages.dancer.noEdit')}}
       </div>
       <a href="#" v-on:click.prevent="back" class="btn-back">
        <icon icon="back" class></icon>
        <span class="btn-back-text text-footnote">{{ $t('global.text.back') }}</span>
        </a>
       <h1 class="title-primary">
           <span>{{$t('dashboard.title.dancers')}}</span>
           <span class="text-body">{{$t('dashboard.text.totalDancers')}} {{this.dancers.length}}</span>
           </h1>

       <section class="dancer-container">
           <div class="alert-no-data" v-if="!this.dancers.length">
                <p class="alert-text text-body-display">{{ $t('dashboard.text.noDancers') }}</p>
            </div>
            <div class="table" v-else>
                <div class="table-header">
                    <ul class="table-list">
                        <li class="table-item grid-3">
                            <span class="text-subhead">
                                {{ $t('dashboard.table.title.firstName') }}
                            </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="text-subhead">
                             {{ $t('dashboard.table.title.lastName') }}
                              </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="text-subhead">
                             {{ $t('dashboard.table.title.birthday') }}
                              </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="text-subhead">
                             {{ $t('dashboard.table.title.age') }}
                              </span>
                        </li>
                    </ul>
                </div>
                <div class="table-body">
                    <ul class="table-list table-list-body" v-for=" dancer in this.dancers" v-bind:key="dancer.id">
                        <li  class="table-item grid-3">
                            <span class="table-text text-body-display">
                                {{dancer.first_name}}
                            </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="table-text text-body-display">
                                {{dancer.last_name}}
                              </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="table-text text-body-display">
                                 {{dancer.date_of_birth}}
                              </span>
                        </li>
                        <li class="table-item grid-3">
                             <span class="table-text text-body-display">
                                 {{dancer.age}}
                              </span>
                              <div class="table-menu" @click.prevent="openActions" v-if="!checkSubscriptionStatus()">
                                  <icon icon="menu" class=""></icon>
                             </div>
                             <div class="actions-container" v-if="!checkSubscriptionStatus()">
                                 <a href="#" @click.prevent="editDancer(dancer.id, $event)" class="action action-table">
                                     <icon icon="edit" class=""></icon>
                                     <span class="text-subhead">{{ $t('forms.actions.edit') }}</span>
                                </a>
                                <a href="#" @click.prevent="deleteDancer(dancer.id)" class="action action-table">
                                     <icon icon="delete" class=""></icon>
                                     <span class="text-subhead">{{ $t('forms.actions.delete') }}</span>
                                </a>
                                <div class="action-close-overlay" @click.prevent="closeActions"></div>
                             </div>
                        </li>
                    </ul>

                </div>
            </div>
        </section>
        <section class="dancer-actions">
             <button class="btn btn-primary" @click.prevent="show">{{$t('dashboard.label.add.dancer')}}</button>
        </section>
        <modal name="dancer" :classes="'modal'" height="auto" @before-open="beforeOpen" >
            <article class="modal-inner">
                <header class="modal-header">
                    <h1 class="modal-title title-primary">{{$t('forms.title.add.dancer')}}</h1>
                    <span class="modal-close" @click.prevent="hide">
                        <icon icon="close" class=""></icon>
                    </span>
                </header>
                <div class="modal-body">
                    <form autocomplete="off" class="form-dancer-add" @submit.prevent="onSubmit($event)">
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

import Icon from 'laravel-mix-vue-svgicon/IconComponent.vue';
import { mapActions, mapGetters } from 'vuex';
import { store } from '../store';
import Feedback from '../components/Feedback';


export default {

    data: function() {
        return {
            saving: false,
            dancer:{
                id:'',
                first_name: '',
                last_name: '',
                date_of_birth: '',
            }
        }
    },

    beforeRouteEnter (to, from, next) {
      store.dispatch('dancers/getDancers')
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
  },   
    methods:{
        ...mapActions({
            store: 'dancers/store',
            destroy: 'dancers/destroy',
            update: 'dancers/update',
        }),
        beforeOpen (event) {
            this.dancer.id = event.params.id;
        },
        onEnter(ev) {
            
        },
        show () {
            this.$modal.show('dancer', { id:''});
        },
        checkSubscriptionStatus() {
            let isSubscriptionSubmitted = this.subscriptions.some(function(subscription) {
                return subscription.status_id != 1;
               
            });
           
             return isSubscriptionSubmitted;
        },
        hide(ev) {
            this.$modal.hide('dancer');
            this.dancer.first_name = '';
            this.dancer.last_name = '';
            this.dancer.date_of_birth = '';
            this.$setLaravelValidationErrorsFromResponse([]);
        },
        back() {
            this.$router.go(-1);
        },
        openActions(ev) {

            ev.currentTarget.parentNode.classList.toggle('has-menu-open');
        },
        isEmpty(ev) {
            if(ev.currentTarget.value.length > 0){
                ev.currentTarget.classList.add("has-value");
            } else {
                ev.currentTarget.classList.remove("has-value");
                //document.getElementById("myDiv").classList.remove("form-group");
            }
        },
        formatDate(date) {
            let splitDate = date.split('/');

            return splitDate[2] + splitDate[1] + splitDate[0];
        },
        editDancer(id,ev) {
            let i = this.dancers.map(item => item.id).indexOf(id);
            this.dancer.first_name = this.dancers[i].first_name;
            this.dancer.last_name = this.dancers[i].last_name;
            this.dancer.date_of_birth = this.dancers[i].date_of_birth;
            this.$modal.show('dancer', { id:id});
            ev.currentTarget.parentNode.parentNode.classList.remove('has-menu-open');
        },
        closeActions(ev) {
             ev.currentTarget.parentNode.parentNode.classList.remove('has-menu-open');
        },
        deleteDancer(id) {
            this.saving = true;

            this.destroy(id)
            .then(() => {
                //this.hide();
             })
            .catch(
                error => {
                    this.saving = false;
            })
            .then(_ => (this.saving = false));
        },
        onSubmit(event) {
            this.saving = true;
            if(this.dancer.id !== '') {
                //let formatedDate = this.formatDate(this.dancer.date_of_birth);
                this.update({
                    id:this.dancer.id,
                    first_name: this.dancer.first_name,
                    last_name: this.dancer.last_name,
                    date_of_birth: this.dancer.date_of_birth
                })
                .then(() => {
                    this.hide();
                })
                .catch(
                    error => {
                        this.$setLaravelValidationErrorsFromResponse(error.data)
                        this.saving = false;
                })
                .then(_ => (this.saving = false));
            }else {
                //let formatedDate = this.formatDate(this.dancer.date_of_birth);
                this.store({
                    first_name: this.dancer.first_name,
                    last_name: this.dancer.last_name,
                    date_of_birth: this.dancer.date_of_birth
                })
                .then(() => {
                    this.hide();
                })
                .catch(
                    error => {
                        this.$setLaravelValidationErrorsFromResponse(error.data)
                        this.saving = false;
                })
                .then(_ => (this.saving = false));
            }
        }

    },
    computed: {
        ...mapGetters({
            dancers: 'dancers/dancers',
            subscriptions: 'subscriptions/subscriptions',
        })
    },
    created() {
    },
    components: {
        Icon,
        Feedback
    }
}
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
    .title-primary {
        display:flex;
        justify-content: space-between;
        align-content: center;
        align-items: center;
    }
</style>
