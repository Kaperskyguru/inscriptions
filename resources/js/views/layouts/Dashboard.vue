<template>
    <div>
        <div id="dashboard">
            <nav-bar />
             <!-- <loading></loading> -->
            <slot />
        </div>

    </div>
</template>
<script>
    import NavBar from "../../components/NavBar";
    import { mapActions, mapGetters } from 'vuex';
    import { store } from '../../store';
    export default {
        components: {
            NavBar,
        },
        methods: {
            ...mapActions({
                clearFeedback: 'feedback/clearFeedback'
            })
        },
        //      console.log('beforeRoute enter');
        // // store.dispatch('events/getEvents')
        // //     .then(
        // //         store.dispatch('subscriptions/getSubscriptions')
        // //         .then(next)
        // //         .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}))
        // //     )
        // //     .catch(error => store.dispatch('feedback/setFeedback', {message: error.data, type: 'warning'}));
        // }, 
        //  ...mapGetters({
        //     user: 'auth/user',
        //     isAdmin: 'auth/isAdmin',
        //     events: 'events/events',
        //     subscriptions: 'subscriptions/subscriptions',
        // // ...
        // })
    }
</script>