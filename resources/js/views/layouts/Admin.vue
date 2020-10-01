<template>
    <div>
        <div id="dashboard">
            <admin-nav-bar />
             <!-- <loading></loading> -->
            <slot />
        </div>

    </div>
</template>
<script>
    import AdminNavBar from "../../components/AdminNavBar";
    //import { store } from '../../store';
    export default {
        components: {
            AdminNavBar,
        },
    }
</script>