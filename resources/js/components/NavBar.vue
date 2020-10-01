<template>
    <div>
        <aside id="layout-aside">
             <img class="nav-logo" :src="`/img/${this.$appTheme}/logo-white.svg`" >
             <nav id="nav-dashboard">
                <router-link :to="{ name: 'dashboard.index' }" class="callout-secondary nav-link">{{ $t('dashboard.nav.index') }}</router-link>
                <ul class="nav-list nav-list-competitions"  v-if="this.subscriptions.length">
                    <li class="nav-item"  v-for="subscription in this.subscriptions" v-bind:key="subscription.id">
                        <router-link :to="{ name: 'dashboard.subscriptionsShow', params: {id: subscription.id}}" class="callout-secondary nav-link">{{subscription.event.city}}</router-link>
                    </li>
                </ul>
                <!-- <router-link :to="{ name: 'dashboard.subscription' }" class="callout-secondary">{{ $t('dashboard.nav.subscription') }}</router-link> -->
             </nav>
             <nav id="nav-meta">
                 <ul class="nav-list nav-list-meta">
                    <li class="nav-item">
                         <router-link :to="{ name: 'organization.edit' }" class="nav-link text-subhead">{{ $t('dashboard.nav.organization') }}</router-link>
                    </li>
                    <li class="nav-item">
                         <router-link :to="{ name: 'dashboard.userEdit' }" class="nav-link text-subhead">{{ $t('dashboard.nav.personInCharge') }}</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'dashboard.dancer' }" class="nav-link text-subhead">{{ $t('dashboard.nav.dancer') }}</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'dashboard.musicShow' }" class="nav-link text-subhead">{{ $t('dashboard.nav.music') }}</router-link>
                    </li>
                     <li class="nav-item nav-item-spacer" v-if="this.$appTheme == 'flofest'">
                        <a class="nav-link text-footnote" target="_blank" :href="`${$t('dashboard.nav.flofestRulesLink')}`">
                           <icon icon="external" class="nav-icon"></icon>
                            <span>{{ $t('dashboard.nav.rules') }}</span>
                        </a>
                    </li>
                    <li class="nav-item nav-item-spacer" v-else>
                        <a class="nav-link text-footnote" target="_blank" :href="`${$t('dashboard.nav.rulesLink')}`">
                           <icon icon="external" class="nav-icon"></icon>
                            <span>{{ $t('dashboard.nav.rules') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-footnote" :href="`/${$t('global.text.mirrorLocale')}`">{{ $t('global.text.mirror') }}</a>
                    </li>
                    <li class="nav-item" v-if="loggedin">
                        <a class="nav-link text-footnote" @click="logoutUser">{{ $t('dashboard.nav.logout') }}</a>
                    </li>
                </ul>
             </nav>
        </aside>
    </div>
</template>

<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapGetters, mapActions } from 'vuex';
import { i18n } from '../plugins/i18n.js';

    export default {
        
        computed: {
            ...mapGetters({
                 user: 'auth/user',
                loggedin: 'auth/loggedin',
                isAdmin: 'auth/isAdmin',
                subscriptions: 'subscriptions/subscriptions',
            })
        },
        methods: {
            ...mapActions({
                logout: 'auth/logout',
                setDelayedFeedback: 'feedback/setDelayedFeedback'      
            }),
            changeLang() {
                //window.locale = i18n.t('global.text.mirrorLocale');
                // const route = Object.assign({}, this.$route);
                // route.params.lang = lang;
                //this.$router.push({path: '/'});
            },
            logoutUser () {
                this.logout();
                this.setDelayedFeedback({message: i18n.t('global.alert.logout'), type: 'login'});
                this.$router.push({path: '/'});

            }
        },
        components: {
            Icon,
        },
        created() {
            //console.log(this.subscriptions)
        },
    }
</script>
