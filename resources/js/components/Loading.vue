<template>
  <div class="loading" v-if="isLoading">
	  <div class="loader"></div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
  name: 'loading',
  computed: {
    ...mapGetters({
    	isLoading: 'loading/isLoading'
    })
  }
}
</script>

<style lang="scss" scoped>
	div.loading
	{
		background-color:white;
		position : fixed;
		display:flex;
		justify-content: center;
		align-items: center;
		align-content: center;
		left : 0;
		right : 0;
		bottom:0;
		top:0;
		width:100%;
		height:100%;
		z-index:9999;

	}
	
	
</style>
