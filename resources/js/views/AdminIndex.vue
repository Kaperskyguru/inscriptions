<template>
<div>
    <div id="layout-dashboard">
        <feedback></feedback>
        <h1 class="title-primary">{{ $t('admin.title.index') }}</h1>
         <section class="card-container" v-if="this.$appTheme === 'flofest'">
              <article class="card card-event">
                <h2 class="card-title title-secondary">Flofest</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['flofest'].length">
                        <li class="status-list-item" v-for="status in this.content['flofest']" v-bind:key="status.id">
                            <span :class="`status-count title-primary status-count-${status.type}`">{{status.subscriptions_count}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
                
            </article>
        </section>
        <section class="card-container" v-else>
            <article class="card card-event">
                <h2 class="card-title title-secondary">Gatineau</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['gatineau'].length">
                        <li class="status-list-item" v-for="status in this.content['gatineau']" v-bind:key="status.id">
                            <span :class="`status-count title-primary status-count-${status.type}`">{{status.subscriptions_count}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
                
            </article>
            <!-- <article class="card card-event">
                <h2 class="card-title title-secondary">Toronto</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['toronto'].length">
                        <li class="status-list-item" v-for="status in this.content['toronto']" v-bind:key="status.id">
                            <span :class="`status-count title-primary status-count-${status.type}`" >{{status.subscriptions_count}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
            </article> -->
            
            <article class="card card-event">
                <h2 class="card-title title-secondary">Lévis</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['levis'].length">
                        <li  class="status-list-item" v-for="status in this.content['levis']" v-bind:key="status.id">
                             <span :class="`status-count title-primary status-count-${status.type}`">{{status.subscriptions_count}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
            </article>
            <article class="card card-event">
                <h2 class="card-title title-secondary">Saint-Hyacinthe</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['saintehyacinthe'].length">
                        <li class="status-list-item" v-for="status in this.content['saintehyacinthe']" v-bind:key="status.id">
                            <span :class="`status-count title-primary status-count-${status.type}`" >{{status.subscriptions_count}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
            </article>
            <!-- <article class="card card-event">
                <h2 class="card-title title-secondary">Sainte Hyacinthe</h2>
                <div class="card-body">
                    <ul class="status-list" v-if="this.content['sainte_hyacinthe'].length">
                        <li  class="status-list-item" v-for="status in this.content['sainte_hyacinthe']" v-bind:key="status.id">
                             <span :class="`status-count title-primary status-count-${status.type}`">{{status.subscriptions.length}}</span>
                            <span class="status-label title-headline">{{status.admin_label}}</span>
                        </li>
                    </ul>
                </div>
            </article> -->
        </section>
    </div>
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
        }
    },
    beforeRouteEnter (to, from, next) {
      store.dispatch('admin/index')
        .then(next)
        .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
    },
   
    computed: {
    // mix the getters into computed with object spread operator
        ...mapGetters({
            user: 'auth/user',
            isAdmin: 'auth/isAdmin',
            content: 'admin/index',
            //subscriptions: 'subscriptions/subscriptions',
        // ...
        })
        
    },
    
    methods:{
        // ...mapActions({
        //     storeSubscription: 'subscriptions/store',
        //     setFeedback: 'feedback/setFeedback',
        //     setDelayedFeedback: 'feedback/setDelayedFeedback',
        // }),
    },
    components: {
        Icon,
        Feedback
    },
   
    created() {
        //console.log(this.content['gatineau']);
        //console.log(this.$store.getters['subscriptions/subscriptions']);
    },
}
</script>
