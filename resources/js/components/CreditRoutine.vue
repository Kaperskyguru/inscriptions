<template>
  <section class="invoice-container" style="margin: 4rem 0px">
    <h1 class="title-tertiary">
      {{ "Credited Routine" }}
    </h1>
    <div class="table">
      <div class="table-header">
        <ul class="table-list">
          <li class="table-item grid-4">
            <span class="text-subhead">Date</span>
          </li>
          <li class="table-item grid-2">
            <span class="text-subhead"># QBO</span>
          </li>
          <li class="table-item grid-2">
            <span class="text-subhead"></span>
          </li>
          <li class="table-item grid-2">
            <span class="text-subhead"></span>
          </li>
          <li class="table-item grid-2">
            <span class="text-subhead">Amount</span>
          </li>
        </ul>
      </div>
    </div>

    <vsa-list>
      <vsa-item v-for="credit in credits" v-bind:key="credit.id">
        <vsa-heading>
          <div class="table-list table-list-body">
            <li class="table-item grid-4">
              <span class="table-text text-body-display">{{
                new Date(credit["credit"].created_at).toLocaleDateString()
              }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display">{{
                credit["credit"].doc_number
              }}</span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display"></span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display"></span>
            </li>
            <li class="table-item grid-2">
              <span class="table-text text-body-display"
                >-{{ credit["credit"].amount }} $</span
              >
            </li>
          </div>
        </vsa-heading>

        <vsa-content>
          <div style="padding: 2rem">
            <h1 class="title-tertiary">
              {{ $t("admin.title.invoice") }}
            </h1>

            <div
              class="alert alert-no-data"
              v-if="!credit['credit']['categories'].length"
            >
              <p class="alert-text text-body-display">
                {{ $t("admin.text.noInvoice") }}
              </p>
            </div>
            <div class="table" v-else>
              <div class="table-header">
                <ul class="table-list">
                  <li class="table-item grid-4">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.category")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.routine")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.totalSubscription")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.unit_cost")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.total_cost")
                    }}</span>
                  </li>
                </ul>
              </div>
              <div class="table-body">
                <ul
                  class="table-list table-list-body"
                  v-for="category in credit['credit']['categories']"
                  v-bind:key="category.id"
                >
                  <li class="table-item grid-4">
                    <span class="table-text text-body-display">{{
                      category.name
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      category.routines_count
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      category.entries
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display"
                      >{{ category.price.formatted_rebate_price }} $</span
                    >
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display"
                      >-{{ category.total }} $</span
                    >
                  </li>
                </ul>
              </div>
            </div>

            <admin-fee
              :fees="subscription.fees"
              :event="event"
              :subscription_id="subscription_id"
              :feeTypes="feeTypes"
            />

            <ul class="invoice-list-total">
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">Sous-total</span>
                <span class="invoice-int grid-3"
                  >{{ credit["payment"].sub_total }} $</span
                >
              </li>
              <li
                class="invoice-item text-body-display"
                v-if="subscription.event.state_id == 57"
              >
                <span class="invoice-data grid-4"
                  >TPS 737664490 RT 0001 (5%)</span
                >
                <span class="invoice-int grid-3"
                  >{{ credit["payment"].tps }} $</span
                >
              </li>
              <li
                class="invoice-item text-body-display"
                v-if="subscription.event.state_id == 57"
              >
                <span class="invoice-data grid-4"
                  >TVQ 1224260896 TQ 0001 (9,975%)</span
                >
                <span class="invoice-int grid-3"
                  >{{ credit["payment"].tvq }} $</span
                >
              </li>
              <li
                class="invoice-item text-body-display"
                v-if="subscription.event.state_id == 58"
              >
                <span class="invoice-data grid-4"
                  >TVH 737664490 RT 0001 (13%)</span
                >
                <span class="invoice-int grid-3"
                  >{{ credit["payment"].tvh }} $</span
                >
              </li>
              <li class="invoice-item invoice-total text-body-display">
                <span class="invoice-data grid-4">{{
                  $t("admin.label.total_cost")
                }}</span>
                <span class="invoice-int grid-3"
                  >{{ credit["payment"].total }} $</span
                >
              </li>
            </ul>
          </div>
        </vsa-content>
      </vsa-item>
    </vsa-list>
  </section>
</template>

<script>
import AdminFee from "../components/partials/admin-fee";
export default {
  props: {
    subscription: {
      type: [Array, Object],
      default: () => {},
    },
    credits: {
      type: [Array, Object],
      default: () => [],
    },
    feeTypes: {
      type: [Array, Object],
      default: () => [],
    },
    subscription_id: {
      type: [Number, String],
    },
    event: {
      type: String,
    },
  },

  components: { AdminFee },

  mounted() {},
};
</script>

<style>
</style>