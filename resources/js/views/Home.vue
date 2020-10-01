<template>
  <div class="customer">
    <h1>I'm a customer</h1>
    <p>I see {{ totalTvCount }} TVs!</p>
        <p v-show="happyStaff">The staff seems happy!</p>

    <p v-show="!totalTvCount">I can't buy any..</p>
    <button 
      :disabled="!totalTvCount"
      @click="buyTv">
      Buy TV
    </button>
     <button 
      :disabled="totalTvCount < 2"
      @click="buyTwoTvs">
      Buy Two TVs
    </button>
  </div>
</template>

<script>


export default {
  name: 'Customer',
  computed: {
    // If you have only one state/getter object spread operators are not necessarily needed
    // But we'll use it for now, so you can see how they are used
    // mapState/mapGetter accepts an array of strings as parameter
    ...mapState(['totalTvCount']),
    ...mapGetter(['totalTvCount'])
  },
  methods: {
    buyTv() {
      // Dispatch the action to buy a TV
      this.$store.dispatch('removeTv', 1)
    },
    buyTwoTvs() {
      // Dispatch the action to buy a TV
      this.$store.dispatch('removeTv', 2)
    },
  }
}
</script>

<style scoped>

</style>