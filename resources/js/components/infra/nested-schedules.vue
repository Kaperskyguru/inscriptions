<template>
  <draggable
    v-bind="dragOptions"
    tag="ul"
    class="drag-area item-container"
    id="drag-parent"
    :list="list"
    :value="value"
    :group="{ name: 'category' }"
    @input="emitter"
  >
    <li v-for="(el, index) in realValue" :key="el.id" class="drag-item">
      <div class="schedule-title-item">
        <form class="form-schedule-title" @submit.prevent="" v-if=el.id>
            <p class="schedule-category schedule-title ">
             
            <input class="title-headline schedule-title-input" type="text" name="scheduleTitle" v-model="el.name">
            </p>
        </form>
        <div class="" v-else>
          <p class="title-headline schedule-title-uncategorized">{{el.name}}</p>
        </div>
      <div class="table-menu schedule-title-menu" @click.prevent="openActions" v-if=el.id>
          <icon icon="menu" class></icon>
      </div>
      <div class="actions-container">
          <a
            href="#"
            class="action action-table"
            @click.prevent="onClickCut($event, index)"
          >
            <icon icon="scissors" class></icon>
            <span class="text-subhead">{{ $t('forms.actions.cut') }}</span>
          </a> 
          <a
            href="#"
            class="action action-table"
            @click.prevent="deleteScheduleTitle($event, {index:index, id:el.id})"
          >
            <icon icon="delete" class></icon>
            <span class="text-subhead">{{ $t('forms.actions.delete') }}</span>
          </a> 
          <div class="action-close-overlay" @click.prevent="closeActions"></div>
        </div>
      </div>
      <nested-routines class="item-sub" :list="el.schedule_items" :parentIndex="index" />
      <button class="schedule-add text-caption" v-on:click="addScheduleCategory($event, index)" v-if=el.id>{{ $t('admin.actions.newCategory') }}</button>
    </li>
  </draggable>
</template>

<script>
import draggable from 'vuedraggable';
import NestedRoutines from "../../components/infra/nested-routines";
import { mapActions, mapGetters } from "vuex";
import { store } from "../../store";
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";



export default {
  name: "nested-schedule",
  methods: {
    emitter(value) {
      this.$emit("input", value);
    },
    // updateItemOrder:function() {
    //   // var items = this.realValue.map(function(item, index) {
    //   //       return { item: item, order: index }
    //   //   })

    //   //   console.log(items);
    // },
    addScheduleCategory:function(ev, index) {

      store.dispatch("schedules/addCategory", { index:index, uuid:this.generateUUID()});
    },
    deleteScheduleTitle:function(ev, data) {
      let actionsParent = ev.currentTarget.parentNode.parentNode;
      let cutParent = actionsParent.nextElementSibling;
      actionsParent.classList.remove('has-menu-open');
      store.dispatch('loading/setLoading', true);
      store.dispatch("schedules/deleteCategory", data);
    },
    generateUUID: function() { // Public Domain/MIT
    var d = new Date().getTime();//Timestamp
    var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16;//random number between 0 and 16
        if(d > 0){//Use timestamp until depleted
            r = (d + r)%16 | 0;
            d = Math.floor(d/16);
        } else {//Use microseconds since page-load if supported
            r = (d2 + r)%16 | 0;
            d2 = Math.floor(d2/16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    
},
    onClickCut:function(ev, index) {
      let actionsParent = ev.currentTarget.parentNode.parentNode;
      let cutParent = actionsParent.nextElementSibling;
      actionsParent.classList.remove('has-menu-open');
      cutParent.classList.add('has-cut-tool');
      //store.dispatch("schedules/cut", { index:index});
    },
    openActions(ev) {
      ev.currentTarget.parentNode.classList.toggle("has-menu-open");
    },
    closeActions(ev) {
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
  },
  components: {
    draggable,
    NestedRoutines,
    Icon
  },
  computed: {
     ...mapGetters({
      isLoading: "loading/isLoading",
    }),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    },
    
    // this.value when input = v-model
    // this.list  when input != v-model
    realValue() {
      return this.value ? this.value : this.list;
    }
  },
  props: {
    value: {
      required: false,
      type: Array,
      default: null
    },
    list: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>