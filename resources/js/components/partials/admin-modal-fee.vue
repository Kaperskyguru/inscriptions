<template>
<modal name="fee" :classes="'modal'" height="auto" @before-open="beforeOpenFee">
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">{{$t('forms.title.add.fee')}}</h1>
          <span class="modal-close" @click.prevent="hideFeeModal">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
          <form
            autocomplete="off"
            class="form-fee-add"
            @submit.prevent="onSubmitFee($event)"
          >
          <div class="form-group" v-bind:class="{'has-error' : errors.has('entries')}">
            <div class="form-group" v-bind:class="{'has-error' : errors.has('fee_type_id')}">
                <div class="floating-label-container">
                  <select
                    id="payment_type"
                    class="form-select has-value"
                    v-model="fee.fee_type_id"
                  >
                    <option
                      v-for="option in feeTypes"
                      v-bind:key="option.id"
                      v-bind:value="option.id"
                    >{{option.name}}</option>
                  </select>
                  <label class="floating-label" for="fee_type">{{ $t('forms.label.type') }}</label>
                </div>
              </div>
                <div class="floating-label-container">
                  <input
                    id="fee_entries"
                    class="form-text-field-input"
                    v-bind:class="{'has-value' : this.fee.entries}"
                    type="text"
                    v-on:change="isEmpty"
                    v-model="fee.entries"
                  />
                  <label class="floating-label" for="fee_entries">{{ $t('forms.label.entries') }}</label>
                </div>
                <p v-show="errors.has('entries')" class="form-msg-error">{{ errors.first('entries') }}</p>
              </div>
              <div class="form-actions">
                <div
                  class="btn btn-secondary"
                  @click.prevent="hidePaymentModal"
                >{{ $t('forms.actions.cancel') }}</div>
                <button
                  class="btn btn-primary"
                  type="submit"
                  :disabled="saving"
                >{{ $t('forms.actions.save') }}</button>
              </div>
           
          </form>
        </div>
      </article>
    </modal>

</template>

<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { store } from "../../store";
import { mapActions, mapGetters } from "vuex";


export default {
  name: "admin-modal-fee",
  data: function() {
    return {
      saving: false,
      fee: {
        id: "",
        fee_type_id: 1,
        subscription_id: "",
        entries: "",
        note: ""
      }
    };
  },
  methods: {
    ...mapActions({
      update: 'fees/update',
      store: 'fees/store',
      // resetSearch: 'admin/resetSearch',
    }),
    beforeOpenFee(event) {
      this.fee.id = event.params.id;
      if (this.fee.id !== "") {
          this.fee.fee_type_id = event.params.fee.fee_type_id;
          this.fee.entries = event.params.fee.entries;
      }
    
    },
    
    hideFeeModal(ev) {
      this.$modal.hide("fee");
      // this.dancer.first_name = '';
      // this.dancer.last_name = '';
      // this.dancer.date_of_birth = '';
      // this.$setLaravelValidationErrorsFromResponse([]);
    },
    isEmpty(ev) {
      if (ev.currentTarget.value.length > 0) {
        ev.currentTarget.classList.add("has-value");
      } else {
        ev.currentTarget.classList.remove("has-value");
        //document.getElementById("myDiv").classList.remove("form-group");
      }
    },
    onSubmitFee(ev) {
      this.saving = true;
      if (this.fee.id !== "") {
        this.update({
          id: this.fee.id,
          fee_type_id: this.fee.fee_type_id,
          entries: this.fee.entries,
        })
          .then(() => {
            this.$modal.hide("fee");
            this.fee.id = '';
            this.fee.fee_type_id = 1;
            this.fee.entries = '';
            store.dispatch("admin/subscription", {
              event: this.event,
              subscription_id: this.subscription_id
            });
          })
          .catch(error => {
            // this.$setLaravelValidationErrorsFromResponse(error.data);
            // if (!error.data.errors) {
            // }

            this.saving = false;
          })
          .then(_ => (this.saving = false));
      } else {
        this.store({
          fee_type_id: this.fee.fee_type_id,
          subscription_id: this.subscription_id,
          entries: this.fee.entries,
          //query: this.query,
        })
          .then(() => {
            this.$modal.hide("fee");
            this.fee.fee_type_id = 1;
            this.fee.entries = '';
            store.dispatch("admin/subscription", {
              event: this.event_name,
              subscription_id: this.subscription_id
            });
          })
          .catch(error => {
            // this.$setLaravelValidationErrorsFromResponse(error.data);
            // if (!error.data.errors) {
            // }

            this.saving = false;
          })
          .then(_ => (this.saving = false));
      }
    }
  },
  components: {
    Icon
  },
  computed: {
    
  },
  props: {
     
    // value: {
    //   required: false,
    //   // type: Array,
    //   default: null
    // },
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

  }
};
</script>
<style lang="scss" scoped>

.form-fee-add {
  width: 304px;
  margin: 0 auto 0 auto;
}
.form-actions {
  display: flex;
  justify-content: space-between;
}
.modal-header {
  background-color: #212529;
}
</style>