<template>
    <div>
        <component :is="layout">
            <router-view></router-view>
        </component>
    </div>
</template>
<script>
    const default_layout = "default";
    import Auth from '../services/Auth';
    import { store } from '../store';
    import { mapActions } from 'vuex';
    
    export default {
        name: 'app',
        components: {
            
        },
        methods: {
            ...mapActions([
            ])
        },
        computed: {
            layout() {
                return (this.$route.meta.layout || default_layout) + '-layout';
            }
        }
    }
</script>
