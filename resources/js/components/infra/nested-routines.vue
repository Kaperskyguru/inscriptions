<template>
<table class="drag-table" cellspacing="0" cellpadding="0">
        <thead class="">
          <tr>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>
            <th scope="col" class="drag-table-title text-subhead"></th>

          </tr>
        </thead>
          <draggable
            v-bind="dragOptions"
            tag="tbody"
            class="drag-area item-container"
            :list="list"
            :value="value"
            :group="{ name: 'routines' }"
            @input="emitter"
          >
            <tr v-for="(el, index) in realValue" :key="el.name" class="drag-item drag-subitem" @click.prevent="onClickScheduleItem">
                    <td scope="row" class="c-align" width="30"><icon icon="drag" class></icon></td>
                    <td scope="row" width="50">
                      <input class="drag-order-position text-body" type="text" tabindex="1" v-model="el.position" />
                      </td>
                    <td scope="row" width="50">{{ el.organization.accronyme}}</td>
                    <td scope="row" width="250">{{ el.routine.name }}</td>
                    <td scope="row" width="200">{{el.routine.average}}</td>
                    <td scope="row"  width="200">{{ el.routine.category.translations[0].name }}</td>
                    <td scope="row"  width="200">{{el.routine.level.name}}</td>
                    <td scope="row"  width="200">{{ el.routine.style.name }}</td>
                    <td scope="row"  width="50">{{ el.routine.dancers.length }}</td>
                    <td scope="row"  width="50">
                      <button @click.prevent="onClickReplace($event, index)">
                         <icon icon="replace" class></icon>
                      </button>
                     
                    </td>
                  </tr>
          </draggable>
      </table>

</template>

<script>
import draggable from 'vuedraggable'
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { store } from "../../store";


export default {
  name: "nested-routines",
  methods: {
    emitter(value) {
      this.$emit("input", value);
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
onClickReplace:function(ev, index) {
    this.$modal.show("replace", { index: index, parentIndex:this.parentIndex });
},
    onClickScheduleItem:function(ev) {

      let $this = ev.currentTarget;
      let parent = $this.closest('.drag-table');

      if(parent.classList.contains('has-cut-tool')) {
        parent.classList.remove('has-cut-tool')
        let nodes = Array.prototype.slice.call( document.getElementById('drag-parent').children );

        let index = nodes.indexOf( parent.parentNode );

        let cutIndex = [...$this.parentElement.children].indexOf($this);
        store.dispatch("schedules/cut", { index:index, cutIndex:cutIndex, uuid:this.generateUUID()});
       
      }
    },

  },
  components: {
    draggable,
    Icon
  },
  computed: {
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
      // type: Array,
      default: null
    },
    list: {
      required: false,
      // type: Array,
      default: null
    },
     parentIndex: {
      required: false,
      // type: Array,
      default: null
    }
  }
};
</script>