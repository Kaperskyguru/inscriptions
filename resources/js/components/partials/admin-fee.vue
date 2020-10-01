<template>
<div>
  <h2 class="title-tertiary title-fee">{{ $t('admin.title.fees') }}</h2>
  <div class="alert alert-no-data" v-if="!fees.length">
    <p class="alert-text text-body-display">{{ $t('admin.text.noFees') }}</p>
  </div>
  <div class="table" v-else>
    <div class="table-header">
      <ul class="table-list">
        <li class="table-item grid-6">
          <span class="text-subhead">{{ $t('dashboard.table.title.type') }}</span>
        </li>
        <li class="table-item grid-2">
          <span class="text-subhead">{{ $t('dashboard.table.title.totalSubscription') }}</span>
        </li>
         <li class="table-item grid-2">
            <span class="text-subhead">{{ $t('dashboard.table.title.unit_cost') }}</span>
          </li>
        <li class="table-item grid-2">
          <span class="text-subhead">{{ $t('dashboard.table.title.total_cost') }}</span>
        </li>
      </ul>
    </div>
    <div class="table-body">
      <ul
        class="table-list table-list-body"
        v-for="fee in fees"
        v-bind:key="fee.id"
      >
        
        <li class="table-item grid-6">
          <span class="table-text text-body-display">{{fee.fee_type.name}}</span>
        </li>
        
        <li class="table-item grid-2">
          <span class="table-text text-body-display">{{fee.entries}}</span>
        </li>
         <li class="table-item grid-2">
          <span class="table-text text-body-display">{{fee.fee_type.formatted_price}} $</span>
        </li>
        <li class="table-item grid-2">
          <span class="table-text text-body-display">{{fee.total_amount}} $</span>
          <div class="table-menu" @click.prevent="openActions">
            <icon icon="menu" class></icon>
          </div>
          <div class="actions-container">
            <a
              href="#"
              @click.prevent="editFee(fee.id,$event)"
              class="action action-table"
            >
              <icon icon="edit" class></icon>
              <span class="text-subhead">{{ $t('forms.actions.edit') }}</span>
            </a>
            <a
              href="#"
              @click.prevent="onDeleteFee(fee.id)"
              class="action action-table"
            >
              <icon icon="delete" class></icon>
              <span class="text-subhead">{{ $t('forms.actions.delete') }}</span>
            </a>
            <div class="action-close-overlay" @click.prevent="closeActions"></div>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <admin-modal-fee :feeTypes="feeTypes" :event="this.event" :subscription_id="this.subscription_id"/>
</div>
 
</template>

<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../../store";

import AdminModalFee from "../../components/partials/admin-modal-fee";



export default {
  name: "admin-fee",
  methods: {
    ...mapActions({
      delete: 'fees/delete',
      // resetSearch: 'admin/resetSearch',
    }),
   openActions(ev) {
      ev.currentTarget.parentNode.classList.toggle("has-menu-open");
    },
    closeActions(ev) {
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    editFee(id, ev) {
      let fees = this.fees;
      let i = fees.map(item => item.id).indexOf(id);
      // let updatedFee = {
      //   entries:fees[i].entries,
      //   total_amount:fees[i].total_amount,
      //   note:fees[i].note,
      // }

      this.$modal.show("fee", { id: id, fee:fees[i] });
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },

    onDeleteFee(id) {
      this.saving = true;

      this.delete(id)
        .then(() => {
          store.dispatch("admin/subscription", {
            event: this.event_name,
            subscription_id: this.subscription_id
          });
        })
        .catch(error => {
          this.saving = false;
        })
        .then(_ => (this.saving = false));
    },
  },
  components: {
    Icon,
    AdminModalFee
  },
  computed: {
    ...mapGetters({
      //subscriptions: 'subscriptions/subscriptions',
      // ...
    }),
    
  },
  props: {
    // value: {
    //   required: false,
    //   // type: Array,
    //   default: null
    // },
    fees: {
      required: false,
      // type: Array,
      default: null
    },
    feeTypes: {
      required: false,
      // type: Array,
      default: null
    },
    subscription_id: {
      required: false,
      // type: Array,
      default: null
    },
    event: {
      required: false,
      // type: Array,
      default: null
    },
    //  parentIndex: {
    //   required: false,
    //   // type: Array,
    //   default: null
    // }
  }
};
</script>