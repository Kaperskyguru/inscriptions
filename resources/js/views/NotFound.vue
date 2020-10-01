<template>
  <div>
     <div class="layout-wrapper">
        <public-header />
        <h1 class="title-large">
            {{ $t('global.title.notFound') }}
         </h1>
          <p class="text-center c-align grid-8">{{ $t('global.text.notFound') }}</p>
          <router-link :to="{ path: '/' }" class="text-link">{{ $t('global.text.backHome') }}</router-link>

      </div>
  </div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import PublicHeader from "../components/PublicHeader";

export default {
  data() {
    return {
    };
  },
    components: {
        PublicHeader
    },
    computed: {
    // mix the getters into computed with object spread operator
        ...mapGetters({
        // ...
        })
        
    },
  methods: {
        ...mapActions({
        }),
  },
  created() {
      //this.loaded = true;
  }
};

</script>
<style lang="scss" scoped>
    .title-large {
        margin:0 0 8.8rem 0;
    }
    .text-link {
        text-align:center;
        margin:0 0 1.6rem 0;
        justify-content: center;
    }
</style>
