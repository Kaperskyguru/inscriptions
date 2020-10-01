<template>
<div>
    <div id="layout-dashboard">
        <feedback></feedback>
        <h1 class="title-primary">{{this.user.organization.name}} - {{ $t('dashboard.title.index') }}</h1>
        <section class="card-container" v-if="this.subscriptions.length">
            <article class="card card-subscription" v-for="subscription in this.subscriptions" v-bind:key="subscription.id">
                <header class="card-header">
                        <h1 class="card-title title-large">{{subscription.event.city}}</h1>
                        <icon icon="arrow" class=""></icon>
                </header>
                <div class="card-body">
                    <div class="card-status">
                        <div :class="`status status-${subscription.status.type}`">
                            <span class="text-subhead">{{subscription.status.admin_label}}</span>
                        </div>
                    </div>
                     <div class="card-row">
                        <div class="card-info card-col">
                            <h2 class="title-headline">{{ $t('dashboard.title.totalSubscriptions') }}</h2>
                            <span class="text-body-display">{{subscription.total_dancer}}</span>
                        </div>
                         <div class="card-info card-col">
                            <h2 class="title-headline">{{ $t('dashboard.table.title.routine') }}</h2>
                            <span class="text-body-display">{{subscription.routines_count}}</span>
                        </div>
                        <div class="card-info card-col">
                            <h2 class="title-headline">{{ $t('dashboard.title.debt') }}</h2>
                            <span class="text-body-display">{{subscription.balance}} $</span>
                        </div>
                    </div>

                </div>
                 <router-link :to="{ name: 'dashboard.subscriptionsShow', params: {id: subscription.id}}" class="card-link">

                    </router-link>
            </article>
        </section>
        <section class="event-container" v-if="this.events.length">
            <h3 class="title-tertiary">{{ $t('dashboard.title.noSubscription') }}</h3>
            <ul class="event-list">
                <li class="event-item" v-for="event in this.events" v-bind:key="event.id">
                    <img class="responsive-img" :src="`/img/${event.slug}.jpg`" >
                    <div :class="`event-info event-${event.slug}`">
                        <h4 class="event-title title-large"><span>{{event.city}}</span></h4>
                        <button class="btn btn-primary" :disabled="saving" v-on:click.prevent="subscription($event, event.id)">{{ $t('forms.actions.subscribe') }}</button>
                    </div>
                </li>
            </ul>
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
   
    computed: {
    // mix the getters into computed with object spread operator
        ...mapGetters({
            user: 'auth/user',
            isAdmin: 'auth/isAdmin',
            events: 'events/events',
            subscriptions: 'subscriptions/subscriptions',
        // ...
        })
        
    },
    
    methods:{
        ...mapActions({
            storeSubscription: 'subscriptions/store',
            setFeedback: 'feedback/setFeedback',
            setDelayedFeedback: 'feedback/setDelayedFeedback',
        }),
        subscription: function (ev, id) {
            // use event here as well as id
            var $this = event.target;
            $this.disabled = true;
            this.saving = true;
            this.storeSubscription({
                    event_id: id,
                })
                .then(() => {
                    let lastInsertID = this.subscriptions[this.subscriptions.length - 1].id;
                    store.dispatch('events/removeEvent', id);
                    this.$router.push({ name: "dashboard.subscriptionsShow", params: {id: lastInsertID} })

                })
                .catch(
                    error => {
                        $this.disabled = true;
                        this.setFeedback({message: error.data.msg, type: 'warning'});
                        this.saving = false;
                })
                .then(_ => (this.saving = false));
        }
    },
    components: {
        Icon,
        Feedback
    },
   
    created() {
        //console.log(this.$store.getters['subscriptions/subscriptions']);
    },
}
</script>
