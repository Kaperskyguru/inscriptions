<template>
    <div>
        <aside id="layout-aside" class="layout-aside-admin">
             <img class="nav-logo" :src="`/img/${this.$appTheme}/logo-white.svg`" >
             <nav id="nav-dashboard-admin">
                <router-link :to="{ name: 'admin.index' }" class="callout-secondary nav-link nav-link-dashboard">{{ $t('admin.nav.index') }}</router-link>
                <ul class="nav-list nav-list-competitions" v-if="this.$appTheme === 'flofest'">
                    <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'flofest'}}" class="callout-secondary nav-link">Flofest Montréal</router-link>
                    </li>
                    <!-- <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'saintehyacinthe'}}" class="callout-secondary nav-link">Sainte Hyacinthe</router-link>
                    </li> -->
                </ul>
                <ul class="nav-list nav-list-competitions" v-else>
                    <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'gatineau'}}" class="callout-secondary nav-link">Gatineau</router-link>
                    </li>
                    <!-- <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'toronto'}}" class="callout-secondary nav-link">Toronto</router-link>
                    </li> -->
                    <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'levis'}}" class="callout-secondary nav-link">Lévis</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'saintehyacinthe'}}" class="callout-secondary nav-link">Saint-Hyacinthe</router-link>
                    </li>
                    
                    <!-- <li class="nav-item">
                        <router-link :to="{ name: 'admin.event', params: {event: 'saintehyacinthe'}}" class="callout-secondary nav-link">Sainte Hyacinthe</router-link>
                    </li> -->
                </ul>
             </nav>
             <nav id="nav-meta">
                 <ul class="nav-list nav-list-meta">
                      <li class="nav-item" v-if="loggedin">
                        <a class="nav-link text-footnote" @click="logoutUser">{{ $t('dashboard.nav.logout') }}</a>
                    </li>
                </ul>
             </nav>
        </aside>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { i18n } from '../plugins/i18n.js';

    export default {
        
        computed: {
            ...mapGetters({
                loggedin: 'auth/loggedin',
                isAdmin: 'auth/isAdmin',
                currentUser: 'auth/user',
            })
        },
        methods: {
            ...mapActions({
                logout: 'auth/logout',
                setDelayedFeedback: 'feedback/setDelayedFeedback'      
            }),
            logoutUser () {
                this.logout();
                this.setDelayedFeedback({message: i18n.t('global.alert.logout'), type: 'login'});
                this.$router.push({path: '/'});
            }
        },
        created() {
            //console.log(this.subscriptions)
        },
    }
</script>
