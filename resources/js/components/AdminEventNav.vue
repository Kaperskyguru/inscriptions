<template>
        <nav id="nav-event">
             <router-link :to="{ name: 'admin.event', params: {event: this.event_name}}" class="title-headline nav-link">Inscriptions</router-link>
             <router-link :to="{ name: 'admin.schedule', params: {event: this.event_name}}" class="title-headline nav-link">Ordre d'affichage</router-link>
              <router-link :to="{ name: 'admin.scheduleOrderPosition', params: {event: this.event_name}}" class="title-headline nav-link">Ordre de passage</router-link>
               <router-link :to="{ name: 'admin.scheduleHour', params: {event: this.event_name}}" class="title-headline nav-link">Heure de passage</router-link>
        </nav>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { i18n } from '../plugins/i18n.js';

    export default {
        name: 'admin-event-nav',
        computed: {
            // ...mapGetters({
            //     loggedin: 'auth/loggedin',
            //     isAdmin: 'auth/isAdmin',
            //     currentUser: 'auth/user',
            // })
            event_name() {
            return this.$route.params.event;
            }
        },
        
        methods: {
            // ...mapActions({
            //     logout: 'auth/logout',
            //     setDelayedFeedback: 'feedback/setDelayedFeedback'      
            // }),
            // logoutUser () {
            //     this.logout();
            //     this.setDelayedFeedback({message: i18n.t('global.alert.logout'), type: 'login'});
            //     this.$router.push({path: '/'});
            // }
        },
        created() {
            //console.log(this.subscriptions)
        },
    }
</script>
