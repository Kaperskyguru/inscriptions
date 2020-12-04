<template>
  <ul class="table-list table-list-body">
    <li class="table-item grid-4">
      <span class="table-text text-body-display">{{ category.name }}</span>
    </li>
    <li class="table-item grid-2">
      <span class="table-text text-body-display">{{
        category.routines_count
      }}</span>
    </li>
    <li class="table-item grid-2">
      <span class="table-text text-body-display">{{ category.entries }}</span>
    </li>

    <li class="table-item grid-2">
      <span class="table-text text-body-display">{{ category.factured }}</span>
    </li>

    <li class="table-item grid-2">
      <span class="table-text text-body-display">{{ category.credit }}</span>
    </li>
  </ul>
</template>

<script>
export default {
  props: ["category"],

  computed: {
    calculatedCredit() {
      return this.category.factured - this.category.entries;
    },
  },
  methods: {},
};
</script>

<style>
</style>