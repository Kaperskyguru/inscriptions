<template>
  <div class="feedback" @click="clearFeedback">
  	<div :class="feedbackClass" v-if="feedback.message">	
  			<p class="text-body-display">{{ feedback.message }}</p>
  	</div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  name: 'feedback',
  computed: {
    ...mapGetters({
    	feedback: 'feedback/feedback',
      feedbackClass: 'feedback/feedbackClass',
    })
  },
  methods: {
  	...mapActions({
  		clearFeedback: 'feedback/clearFeedback'
  	})
  },
  watch: {
    $route (to, from) {
      this.clearFeedback();
    }
  },
}
</script>

<style scoped>
</style>