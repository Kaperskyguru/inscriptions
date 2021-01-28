<template>
  <div>
    <div id="layout-dashboard">
      <feedback></feedback>
      <a href="#" v-on:click.prevent="back" class="btn-back">
        <icon icon="back" class></icon>
        <span class="btn-back-text text-footnote">{{
          $t("global.text.back")
        }}</span>
      </a>
      <h1 class="title-primary">
        {{ this.content["organizations"].name }}
      </h1>
      <header class="subscription-header">
        <div class="status-container">
          <div
            :class="`status status-${this.content['organizations'].subscriptions[0].status.type}`"
            @click.prevent="openActions"
          >
            <span class="text-subhead">{{
              this.content["organizations"].subscriptions[0].status.admin_label
            }}</span>
          </div>
          <div class="actions-container action-status">
            <form class="form-update-status" @submit.prevent="changeStatus(1)">
              <button
                class="status status-default"
                type="submit"
                :disabled="saving"
              >
                <span class="text-subhead">
                  {{ $t("admin.label.not_submitted") }}
                </span>
              </button>
            </form>
            <form class="form-update-status" @submit.prevent="changeStatus(2)">
              <button
                class="status status-danger"
                type="submit"
                :disabled="saving"
              >
                <span class="text-subhead">
                  {{ $t("admin.label.to_be_reviewed") }}
                </span>
              </button>
            </form>
            <form class="form-update-status" @submit.prevent="changeStatus(3)">
              <button
                class="status status-warning"
                type="submit"
                :disabled="saving"
              >
                <span class="text-subhead">
                  {{ $t("admin.label.waiting_payment") }}
                </span>
              </button>
            </form>
            <form class="form-update-status" @submit.prevent="changeStatus(4)">
              <button
                class="status status-success"
                type="submit"
                :disabled="saving"
              >
                <span class="text-subhead">
                  {{ $t("admin.label.paid") }}
                </span>
              </button>
            </form>
            <form class="form-update-status" @submit.prevent="changeStatus(5)">
              <button
                class="status status-waiting"
                type="submit"
                :disabled="saving"
              >
                <span class="text-subhead">
                  {{ $t("admin.label.waiting") }}
                </span>
              </button>
            </form>
            <div
              class="action-close-overlay"
              @click.prevent="closeActions"
            ></div>
          </div>
        </div>

        <div class="card-row">
          <div class="card-info card-col">
            <h2 class="title-headline">
              {{ $t("dashboard.title.totalSubscriptions") }}
            </h2>
            <span class="text-body-display">{{
              this.content["organizations"].subscriptions[0].total_dancer
            }}</span>
          </div>
          <div class="card-info card-col">
            <h2 class="title-headline">
              {{ $t("dashboard.table.title.routine") }}
            </h2>
            <span class="text-body-display">{{
              this.content["organizations"].subscriptions[0].routines_count
            }}</span>
          </div>
          <div class="card-info card-col">
            <h2 class="title-headline">
              {{ $t("dashboard.title.debt") }}
            </h2>
            <span class="text-body-display"
              >{{
                this.content["organizations"].subscriptions[0].balance
              }}
              $</span
            >
          </div>
        </div>
      </header>

      <nav class="nav-tabs">
        <button
          class="nav-tabs-link text-body active"
          data-tab="inscriptions"
          @click.prevent="onClickTab($event)"
        >
          Inscriptions
        </button>
        <button
          class="nav-tabs-link text-body"
          data-tab="informations"
          @click.prevent="onClickTab($event)"
        >
          Informations
        </button>
        <button
          class="nav-tabs-link text-body"
          data-tab="dancers"
          @click.prevent="onClickTab($event)"
        >
          Danseurs
        </button>
      </nav>
      <div class="tabs">
        <div class="tab active" data-tab="inscriptions">
          <section class="export-container">
            <h1 class="title-tertiary">Heure de passage</h1>
            <div class="export-actions">
              <a
                :href="`/api/v1/admin/export/schedule/organization/${this.content['organizations'].subscriptions[0].id}/${this.content['organizations'].id}?token=${accesstoken}`"
                target="_blank"
                class="btn btn-primary btn-inverted"
                >Exporter</a
              >
            </div>
          </section>
          <section
            class="routine-container"
            :set="
              (subscription = this.content['organizations'].subscriptions[0])
            "
          >
            <h1 class="title-tertiary">
              {{ $t("admin.title.routines") }}
            </h1>
            <div
              class="alert alert-no-data"
              v-if="!subscription.routines.length"
            >
              <p class="alert-text text-body-display">
                {{ $t("admin.text.noRoutines") }}
              </p>
            </div>
            <div class="table" v-else>
              <div class="table-header">
                <ul class="table-list">
                  <li class="table-item grid-4">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.name")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.category")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.level")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.style")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.totalSubscription")
                    }}</span>
                  </li>
                  <!-- <li class="table-item grid-2"> -->
                  <!-- <span class="text-subhead"
                      ># {{ $t("dashboard.table.title.qbo") }}</span
                    > -->
                  <!-- </li> -->
                </ul>
              </div>
              <div class="table-body">
                <ul
                  class="table-list table-list-body"
                  v-for="routine in subscription.routines"
                  v-bind:key="routine.id"
                  ref="routines"
                >
                  <li class="table-item grid-4">
                    <span class="table-text text-body-display">{{
                      routine.name
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      routine.category.name
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      routine.level.name
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      routine.style.name
                    }}</span>
                  </li>

                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      routine.dancers.length
                    }}</span>
                    <!-- </li> -->
                    <!-- <li class="table-item grid-2"> -->
                    <!-- <span class="table-text text-body-display">{{
                      routine.doc_number || ""
                    }}</span> -->
                    <!-- </li> -->
                    <div class="table-menu" @click.prevent="openActions">
                      <icon icon="menu" class></icon>
                    </div>
                    <div class="actions-container">
                      <router-link
                        :to="{
                          name: 'admin.routineEdit',
                          params: {
                            id: routine.subscription_id,
                            routine_id: routine.id,
                          },
                        }"
                        class="action table-action"
                      >
                        <icon icon="edit" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.edit")
                        }}</span>
                      </router-link>
                      <router-link
                        :to="{
                          name: 'admin.routineDuplicate',
                          params: {
                            id: routine.subscription_id,
                            routine_id: routine.id,
                          },
                        }"
                        class="action table-action"
                      >
                        <icon icon="duplicate" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.duplicate")
                        }}</span>
                      </router-link>
                      <a
                        href="#"
                        class="action action-table"
                        @click.prevent="deleteRoutine($event, routine.id)"
                      >
                        <icon icon="delete" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.delete")
                        }}</span>
                      </a>
                      <div
                        class="action-close-overlay"
                        @click.prevent="closeActions"
                      ></div>
                    </div>
                    <div
                      class="music-play-container"
                      v-if="routine.music != 'default.mp3'"
                    >
                      <a
                        :href="
                          '/storage/' +
                          subscription.event.slug +
                          '/' +
                          routine.music
                        "
                        target="_blank"
                      >
                        <icon icon="play"></icon>
                      </a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <section
              style="margin-bottom: 3rem; margin-top: 3rem"
              class="invoice-container"
              :set="
                (subscription = this.content['organizations'].subscriptions[0])
              "
            >
              <h1 class="title-tertiary">
                {{ "Total" }}
              </h1>
              <div
                class="alert alert-no-data"
                v-if="!subscription.routines.length"
              >
                <p class="alert-text text-body-display">
                  {{ $t("admin.text.noInvoice") }}
                </p>
              </div>
              <div class="table" v-if="subscription.routines.length">
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
                      <span class="text-subhead">Factured</span>
                    </li>

                    <li class="table-item grid-2">
                      <span class="text-subhead">Credit</span>
                    </li>
                  </ul>
                </div>
                <div class="table-body">
                  <TotalTable
                    v-for="category in this.content['allCategories']"
                    v-bind:key="category.id"
                    :category="category"
                  />
                </div>
                <ul class="invoice-list-total">
                  <li class="invoice-item text-body-display">
                    <span class="invoice-data grid-4">Total Entries</span>
                    <span class="invoice-int grid-3"
                      >{{ this.content["newpayment"].sub_total }} $</span
                    >
                  </li>
                  <li
                    class="invoice-item text-body-display"
                    v-if="subscription.event.state_id == 57"
                  >
                    <span class="invoice-data grid-4">Total Payments</span>
                    <span class="invoice-int grid-3"
                      >{{ this.content["newpayment"].tps }} $</span
                    >
                  </li>

                  <li class="invoice-item invoice-total text-body-display">
                    <span class="invoice-data grid-4">Balance</span>
                    <span class="invoice-int grid-3"
                      >{{ this.content["newpayment"].total }} $</span
                    >
                  </li>
                </ul>
              </div>
              <div class="export-actions" v-if="calculatedCredit.length">
                <button
                  @click.prevent="submitCreditNote"
                  :disabled="saving"
                  class="btn btn-primary"
                >
                  Credit
                </button>
              </div>
            </section>

            <div class="export-actions">
              <!-- <export-excel
                :name="'routines_' + this.content['organizations'].name + '.xls'"
                :data="getRoutineData()"
                :class="`btn btn-primary btn-inverted`"
              >Exporter</export-excel>-->

              <a
                :href="`/api/v1/admin/export/subscription/${this.content['organizations'].subscriptions[0].id}?token=${accesstoken}`"
                target="_blank"
                class="btn btn-primary btn-inverted"
                >Exporter</a
              >
              <a
                class="btn btn-primary btn-inverted"
                @click.prevent="clickAddToSchedule"
                >Ajout à l'ordre</a
              >
              <router-link
                :to="{
                  name: 'admin.routineCreate',
                  params: {
                    event: this.event_name,
                    id: this.content['organizations'].subscriptions[0].id,
                    organization_id: this.content['organizations'].id,
                  },
                }"
                class="btn btn-primary"
              >
                <span class="text-subhead">{{
                  $t("forms.actions.addRoutine")
                }}</span>
              </router-link>
            </div>
          </section>
          <section
            class="invoice-container"
            :set="
              (subscription = this.content['organizations'].subscriptions[0])
            "
          >
            <h1 class="title-tertiary">
              {{ $t("admin.title.invoice") }}
            </h1>

            <div
              class="alert alert-no-data"
              v-if="!this.content['categories'].length"
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
                  <!-- <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.routine")
                    }}</span>
                  </li> -->
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
                  v-for="category in this.content['categories']"
                  v-bind:key="category.id"
                >
                  <li class="table-item grid-4">
                    <span class="table-text text-body-display">{{
                      category.name
                    }}</span>
                  </li>
                  <!-- <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      category.routines_count
                    }}</span>
                  </li> -->
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
                      >{{ category.total }} $</span
                    >
                  </li>
                </ul>
              </div>
            </div>

            <admin-fee
              :fees="subscription.fees"
              :event="this.event_name"
              :subscription_id="this.subscription_id"
              :feeTypes="this.content['feeTypes']"
            />

            <ul class="invoice-list-total">
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">Sous-total</span>
                <span class="invoice-int grid-3"
                  >{{ this.content["newpayment"].sub_total }} $</span
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
                  >{{ this.content["newpayment"].tps }} $</span
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
                  >{{ this.content["newpayment"].tvq }} $</span
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
                  >{{ this.content["newpayment"].tvh }} $</span
                >
              </li>
              <li class="invoice-item invoice-total text-body-display">
                <span class="invoice-data grid-4">{{
                  $t("admin.label.total_cost")
                }}</span>
                <span class="invoice-int grid-3"
                  >{{ this.content["newpayment"].total }} $</span
                >
              </li>
            </ul>

            <div class="export-actions">
              <select
                style="text-align-last: center"
                v-if="
                  this.content['organizations'].subscriptions[0].status_id ==
                    1 ||
                  this.content['organizations'].subscriptions[0].status_id ==
                    2 ||
                  this.content['organizations'].subscriptions[0].status_id == 5
                "
                class="btn btn-primary btn-inverted text-center"
                @change="changeCategoryPrice"
              >
                <option disabled selected>
                  {{ $t("admin.label.price_list") }}
                </option>
                <option :value="year" v-for="(year, i) in years" :key="i">
                  {{ year }}
                </option>
              </select>
              <a
                class="btn btn-primary btn-inverted"
                :href="authUrl"
                target="_blank"
                >{{ $t("admin.actions.generate.invoice") }}</a
              >
              <a
                class="btn btn-primary"
                @click.prevent="showFeeModal($event)"
                target="_blank"
                >{{ $t("admin.actions.add.fee") }}</a
              >
              <form
                class="form-update-status"
                @submit.prevent="changeStatus(5)"
                v-if="
                  this.content['organizations'].subscriptions[0].status_id == 2
                "
              >
                <button
                  class="btn btn-primary btn-change-status btn-waiting"
                  type="submit"
                  :disabled="saving"
                >
                  {{ $t("admin.actions.add.waiting") }}
                </button>
              </form>
              <form
                class="form-update-status"
                @submit.prevent="changeStatus(3)"
                v-if="
                  this.content['organizations'].subscriptions[0].status_id ==
                    1 ||
                  this.content['organizations'].subscriptions[0].status_id ==
                    2 ||
                  this.content['organizations'].subscriptions[0].status_id == 5
                "
              >
                <button
                  class="btn btn-primary btn-change-status"
                  type="submit"
                  :disabled="saving"
                >
                  {{ $t("admin.actions.approve") }}
                </button>
              </form>
            </div>
          </section>

          <!-- START HERE -->
          <section
            class="invoice-container"
            style="margin: 4rem 0px"
            :set="(subscription = content['organizations'].subscriptions[0])"
          >
            <h1 class="title-tertiary">
              {{ "Invoice Routined" }}
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
              <vsa-item
                v-for="invoice in content['invoices']"
                v-bind:key="invoice.id"
              >
                <vsa-heading>
                  <div class="table-list table-list-body">
                    <li class="table-item grid-4">
                      <span class="table-text text-body-display">{{
                        new Date(
                          invoice["invoice"].created_at
                        ).toLocaleDateString()
                      }}</span>
                    </li>
                    <li class="table-item grid-2">
                      <span class="table-text text-body-display">{{
                        invoice["invoice"].doc_number
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
                        >{{ invoice["invoice"].amount }} $</span
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
                      v-if="!invoice['invoice']['categories'].length"
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
                          <!-- <li class="table-item grid-2">
                            <span class="text-subhead">{{
                              $t("dashboard.table.title.routine")
                            }}</span>
                          </li> -->
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
                          v-for="category in invoice['invoice']['categories']"
                          v-bind:key="category.id"
                        >
                          <li class="table-item grid-4">
                            <span class="table-text text-body-display">{{
                              category.name
                            }}</span>
                          </li>
                          <!-- <li class="table-item grid-2">
                            <span class="table-text text-body-display">{{
                              category.routines_count
                            }}</span>
                          </li> -->
                          <li class="table-item grid-2">
                            <span class="table-text text-body-display">{{
                              category.entries
                            }}</span>
                          </li>
                          <li class="table-item grid-2">
                            <span class="table-text text-body-display"
                              >{{
                                category.price.formatted_rebate_price
                              }}
                              $</span
                            >
                          </li>
                          <li class="table-item grid-2">
                            <span class="table-text text-body-display"
                              >{{ category.total }} $</span
                            >
                          </li>
                        </ul>
                      </div>
                    </div>

                    <admin-fee
                      :fees="subscription.fees"
                      :event="event_name"
                      :subscription_id="subscription_id"
                      :feeTypes="content['feeTypes']"
                    />

                    <ul class="invoice-list-total">
                      <li class="invoice-item text-body-display">
                        <span class="invoice-data grid-4">Sous-total</span>
                        <span class="invoice-int grid-3"
                          >{{ invoice["payment"].sub_total }} $</span
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
                          >{{ invoice["payment"].tps }} $</span
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
                          >{{ invoice["payment"].tvq }} $</span
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
                          >{{ invoice["payment"].tvh }} $</span
                        >
                      </li>
                      <li class="invoice-item invoice-total text-body-display">
                        <span class="invoice-data grid-4">{{
                          $t("admin.label.total_cost")
                        }}</span>
                        <span class="invoice-int grid-3"
                          >{{ invoice["payment"].total }} $</span
                        >
                      </li>
                    </ul>
                  </div>
                </vsa-content>
              </vsa-item>
            </vsa-list>
          </section>
          <!-- END HERE -->

          <credit-routine
            :subscription="this.content['organizations'].subscriptions[0]"
            :credits="this.content['credits']"
            :feeTypes="this.content['feeTypes']"
            :subscription_id="subscription_id"
            :event="event_name"
          />

          <section
            class="payments-container"
            :set="(subscription = content['organizations'].subscriptions[0])"
          >
            <h1 class="title-tertiary">
              {{ $t("admin.title.payments") }}
            </h1>
            <div
              class="alert alert-no-data"
              v-if="!subscription.payments.length"
            >
              <p class="alert-text text-body-display">
                {{ $t("admin.text.noPayments") }}
              </p>
            </div>
            <div class="table" v-else>
              <div class="table-header">
                <ul class="table-list">
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.date")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.type")
                    }}</span>
                  </li>
                  <li class="table-item grid-6">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.note")
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.amount")
                    }}</span>
                  </li>
                </ul>
              </div>
              <div class="table-body">
                <ul
                  class="table-list table-list-body"
                  v-for="payment in subscription.payments"
                  v-bind:key="payment.id"
                >
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      payment.receive_on
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display">{{
                      payment.payment_type.name
                    }}</span>
                  </li>
                  <li class="table-item grid-6">
                    <span class="table-text text-body-display">{{
                      payment.note
                    }}</span>
                  </li>
                  <li class="table-item grid-2">
                    <span class="table-text text-body-display"
                      >{{ payment.amount }} $</span
                    >
                    <div class="table-menu" @click.prevent="openActions">
                      <icon icon="menu" class></icon>
                    </div>
                    <div class="actions-container">
                      <a
                        href="#"
                        @click.prevent="editPayment(payment.id, $event)"
                        class="action action-table"
                      >
                        <icon icon="edit" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.edit")
                        }}</span>
                      </a>
                      <a
                        href="#"
                        @click.prevent="onDeletePayment(payment.id)"
                        class="action action-table"
                      >
                        <icon icon="delete" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.delete")
                        }}</span>
                      </a>
                      <div
                        class="action-close-overlay"
                        @click.prevent="closeActions"
                      ></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <ul class="invoice-list-total">
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">Sous-total Factured</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.factured_sub_total }} $</span
                >
              </li>
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">Sous-total Credit</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.credit_sub_total }} $</span
                >
              </li>
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">Sous-total</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.sub_total_payment }} $</span
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
                  >{{ subscription.tps_payment }} $</span
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
                  >{{ subscription.tvq_payment }} $</span
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
                  >{{ subscription.tvh_payment }} $</span
                >
              </li>
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">{{
                  $t("admin.text.totalToPay")
                }}</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.total_payment }} $</span
                >
              </li>
              <li class="invoice-item text-body-display">
                <span class="invoice-data grid-4">{{
                  $t("admin.text.totalPayments")
                }}</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.sum_payments }} $</span
                >
              </li>
              <li class="invoice-item invoice-total text-body-display">
                <span class="invoice-data grid-4">{{
                  $t("admin.text.balance")
                }}</span>
                <span class="invoice-int grid-3"
                  >{{ subscription.balance }} $</span
                >
              </li>
            </ul>
            <div class="export-actions">
              <button class="btn btn-primary" @click.prevent="showPaymentModal">
                {{ $t("admin.actions.add.payment") }}
              </button>
            </div>
          </section>
        </div>
        <div class="tab tab-information" data-tab="informations">
          <div class="tab-col grid-4">
            <form autocomplete="off" class="form-user-edit" @submit.prevent>
              <section class="form-step">
                <fieldset>
                  <div
                    class="form-group"
                    v-bind:class="{
                      'has-error': errors.has('name'),
                    }"
                  >
                    <div class="floating-label-container">
                      <input
                        id="user_name"
                        class="form-text-field-input"
                        v-bind:class="{
                          'has-value': organization.user.name,
                        }"
                        type="text"
                        :disabled="true"
                        v-on:change="isEmpty"
                        v-model="organization.user.name"
                      />
                      <label class="floating-label" for="user_name">{{
                        $t("forms.label.name")
                      }}</label>
                    </div>
                    <p v-show="errors.has('name')" class="form-msg-error">
                      {{ errors.first("name") }}
                    </p>
                  </div>
                  <div
                    class="form-group"
                    v-bind:class="{
                      'has-error': errors.has('email'),
                    }"
                  >
                    <div class="floating-label-container">
                      <input
                        id="user_email"
                        class="form-text-field-input"
                        v-bind:class="{
                          'has-value': user.email,
                        }"
                        type="email"
                        v-on:change="isEmpty"
                        :disabled="true"
                        v-model="organization.user.email"
                      />
                      <label class="floating-label" for="user_email">{{
                        $t("forms.label.email")
                      }}</label>
                    </div>
                    <p v-show="errors.has('email')" class="form-msg-error">
                      {{ errors.first("email") }}
                    </p>
                  </div>
                </fieldset>
              </section>
            </form>
          </div>
          <div class="tab-col grid-4">
            <form
              autocomplete="off"
              class="form-edit-organization"
              @submit.prevent="onSubmitOrganization"
            >
              <section class="form-step" data-step="1">
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('name'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_name"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.name,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.name"
                    />
                    <label class="floating-label" for="organization_name">{{
                      $t("forms.label.organizationName")
                    }}</label>
                  </div>
                  <p v-show="errors.has('name')" class="form-msg-error">
                    {{ errors.first("name") }}
                  </p>
                </div>
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('accronyme'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_accronyme"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.accronyme,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.accronyme"
                    />
                    <label
                      class="floating-label"
                      for="organization_accronyme"
                      >{{ $t("forms.label.organizationAccronyme") }}</label
                    >
                  </div>
                  <p v-show="errors.has('accronyme')" class="form-msg-error">
                    {{ errors.first("accronyme") }}
                  </p>
                </div>
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('address'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_address"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.address,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.address"
                    />
                    <label class="floating-label" for="organization_address">{{
                      $t("forms.label.organizationAddress")
                    }}</label>
                  </div>
                  <p v-show="errors.has('address')" class="form-msg-error">
                    {{ errors.first("address") }}
                  </p>
                </div>
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('city'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_city"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.city,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.city"
                    />
                    <label class="floating-label" for="organization_city">{{
                      $t("forms.label.organizationCity")
                    }}</label>
                  </div>
                  <p v-show="errors.has('city')" class="form-msg-error">
                    {{ errors.first("city") }}
                  </p>
                </div>
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('zipcode'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_zipcode"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.zipcode,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.zipcode"
                    />
                    <label class="floating-label" for="organization_zipcode">{{
                      $t("forms.label.organizationZipcode")
                    }}</label>
                  </div>
                  <p v-show="errors.has('zipcode')" class="form-msg-error">
                    {{ errors.first("zipcode") }}
                  </p>
                </div>
                <div
                  class="form-group"
                  v-bind:class="{
                    'has-error': errors.has('phone'),
                  }"
                >
                  <div class="floating-label-container">
                    <input
                      id="organization_phone"
                      class="form-text-field-input"
                      v-bind:class="{
                        'has-value': organization.phone,
                      }"
                      type="text"
                      v-on:change="isEmpty"
                      v-model="organization.phone"
                    />
                    <label class="floating-label" for="organization_phone">{{
                      $t("forms.label.organizationPhone")
                    }}</label>
                  </div>
                  <p v-show="errors.has('phone')" class="form-msg-error">
                    {{ errors.first("phone") }}
                  </p>
                </div>

                <div class="form-group">
                  <div class="form-radio-container">
                    <input
                      id="organization_locale_fr"
                      class="form-radio-input"
                      type="radio"
                      v-model="organization.locale"
                      value="fr"
                    />
                    <label
                      class="form-radio-label text-body-display"
                      for="organization_locale_fr"
                      >{{ $t("global.text.localeFr") }}</label
                    >
                  </div>
                  <div class="form-radio-container">
                    <input
                      id="organization_locale_en"
                      class="form-radio-input"
                      type="radio"
                      v-model="organization.locale"
                      value="en"
                    />
                    <label
                      class="form-radio-label text-body-display"
                      for="organization_locale_en"
                      >{{ $t("global.text.localeEn") }}</label
                    >
                  </div>
                </div>
              </section>
              <div class="form-actions">
                <button
                  class="btn btn-primary"
                  type="submit"
                  :disabled="saving"
                >
                  {{ $t("forms.actions.save") }}
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="tab tab-dancers" data-tab="dancers">
          <section
            class="dancer-container"
            :set="(dancers = content['organizations'].dancers)"
          >
            <div class="alert-no-data" v-if="!dancers.length">
              <p class="alert-text text-body-display">
                {{ $t("dashboard.text.noDancers") }}
              </p>
            </div>
            <div class="table" v-else>
              <div class="table-header">
                <ul class="table-list">
                  <li class="table-item grid-3">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.firstName")
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.lastName")
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.birthday")
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="text-subhead">{{
                      $t("dashboard.table.title.age")
                    }}</span>
                  </li>
                </ul>
              </div>
              <div class="table-body">
                <ul
                  class="table-list table-list-body"
                  v-for="dancer in dancers"
                  v-bind:key="dancer.id"
                >
                  <li class="table-item grid-3">
                    <span class="table-text text-body-display">{{
                      dancer.first_name
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="table-text text-body-display">{{
                      dancer.last_name
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="table-text text-body-display">{{
                      dancer.date_of_birth
                    }}</span>
                  </li>
                  <li class="table-item grid-3">
                    <span class="table-text text-body-display">{{
                      dancer.age
                    }}</span>
                    <div class="table-menu" @click.prevent="openActions">
                      <icon icon="menu" class></icon>
                    </div>
                    <div class="actions-container">
                      <a
                        href="#"
                        @click.prevent="editDancer(dancer.id, $event)"
                        class="action action-table"
                      >
                        <icon icon="edit" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.edit")
                        }}</span>
                      </a>
                      <a
                        href="#"
                        @click.prevent="deleteDancer(dancer.id)"
                        class="action action-table"
                      >
                        <icon icon="delete" class></icon>
                        <span class="text-subhead">{{
                          $t("forms.actions.delete")
                        }}</span>
                      </a>
                      <div
                        class="action-close-overlay"
                        @click.prevent="closeActions"
                      ></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <section class="dancer-actions">
              <button class="btn btn-primary" @click.prevent="addDancer">
                {{ $t("dashboard.label.add.dancer") }}
              </button>
            </section>
          </section>
        </div>
      </div>
    </div>
    <modal
      name="payment"
      :classes="'modal'"
      height="auto"
      @before-open="beforeOpenPayment"
    >
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">
            {{ $t("forms.title.add.payment") }}
          </h1>
          <span class="modal-close" @click.prevent="hidePaymentModal">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
          <form
            autocomplete="off"
            class="form-payment-add"
            @submit.prevent="onSubmitPayment($event)"
          >
            <section class="form-step" data-step="1">
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('receive_on'),
                }"
              >
                <div class="floating-label-container">
                  <cleave
                    v-model="payment.receive_on"
                    :raw="false"
                    :options="{
                      date: true,
                      delimiter: '-',
                      datePattern: ['Y', 'm', 'd'],
                    }"
                    id="payment_receive_on"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': payment.receive_on,
                    }"
                    name="receive_on"
                    v-on:change.native="isEmpty"
                  ></cleave>
                  <label class="floating-label" for="payment_receive_on">{{
                    $t("forms.label.receive_on")
                  }}</label>
                </div>
                <p v-show="errors.has('receive_on')" class="form-msg-error">
                  {{ errors.first("receive_on") }}
                </p>
                <p class="form-hint text-caption">AAAA/MM/JJ</p>
              </div>
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('payment_type_id'),
                }"
              >
                <div class="floating-label-container">
                  <select
                    id="payment_type"
                    class="form-select has-value"
                    v-model="payment.payment_type_id"
                  >
                    <option
                      v-for="option in content['paymentTypes']"
                      v-bind:key="option.id"
                      v-bind:value="option.id"
                    >
                      {{ option.name }}
                    </option>
                  </select>
                  <label class="floating-label" for="payment_type">{{
                    $t("forms.label.type")
                  }}</label>
                </div>
              </div>
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('amount'),
                }"
              >
                <div class="floating-label-container">
                  <input
                    id="payment_amount"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': payment.amount,
                    }"
                    type="text"
                    v-on:change="isEmpty"
                    v-model="payment.amount"
                  />
                  <label class="floating-label" for="payment_amount">{{
                    $t("forms.label.amount")
                  }}</label>
                </div>
                <p v-show="errors.has('amount')" class="form-msg-error">
                  {{ errors.first("amount") }}
                </p>
              </div>
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('note'),
                }"
              >
                <div class="floating-label-container">
                  <input
                    id="payment_note"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': payment.note,
                    }"
                    type="text"
                    v-on:change="isEmpty"
                    v-model="payment.note"
                  />
                  <label class="floating-label" for="payment_note">{{
                    $t("forms.label.note")
                  }}</label>
                </div>
                <p v-show="errors.has('note')" class="form-msg-error">
                  {{ errors.first("note") }}
                </p>
              </div>
              <div class="form-actions">
                <div
                  class="btn btn-secondary"
                  @click.prevent="hidePaymentModal"
                >
                  {{ $t("forms.actions.cancel") }}
                </div>
                <button
                  class="btn btn-primary"
                  type="submit"
                  :disabled="saving"
                >
                  {{ $t("forms.actions.save") }}
                </button>
              </div>
            </section>
          </form>
        </div>
      </article>
    </modal>
    <modal
      name="dancer"
      :classes="'modal'"
      height="auto"
      @before-open="beforeOpenDancer"
    >
      <article class="modal-inner">
        <header class="modal-header">
          <h1 class="modal-title title-primary">
            {{ $t("forms.title.add.dancer") }}
          </h1>
          <span class="modal-close" @click.prevent="hideModalDancer">
            <icon icon="close" class></icon>
          </span>
        </header>
        <div class="modal-body">
          <form
            autocomplete="off"
            class="form-dancer-add"
            @submit.prevent="onSubmitDancer($event)"
          >
            <section class="form-step" data-step="1">
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('first_name'),
                }"
              >
                <div class="floating-label-container">
                  <input
                    id="dancer_first_name"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': dancer.first_name,
                    }"
                    type="text"
                    @change="isEmpty"
                    v-model="dancer.first_name"
                  />
                  <label class="floating-label" for="dancer_first_name">{{
                    $t("forms.label.firstname")
                  }}</label>
                </div>
                <p v-show="errors.has('first_name')" class="form-msg-error">
                  {{ errors.first("first_name") }}
                </p>
              </div>
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('last_name'),
                }"
              >
                <div class="floating-label-container">
                  <input
                    id="dancer_last_name"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': dancer.last_name,
                    }"
                    type="text"
                    @change="isEmpty"
                    v-model="dancer.last_name"
                  />
                  <label class="floating-label" for="dancer_last_name">{{
                    $t("forms.label.lastname")
                  }}</label>
                </div>
                <p v-show="errors.has('last_name')" class="form-msg-error">
                  {{ errors.first("last_name") }}
                </p>
              </div>
              <div
                class="form-group"
                v-bind:class="{
                  'has-error': errors.has('date_of_birth'),
                }"
              >
                <div class="floating-label-container">
                  <cleave
                    v-model="dancer.date_of_birth"
                    :raw="false"
                    :options="{
                      date: true,
                      delimiter: '-',
                      datePattern: ['Y', 'm', 'd'],
                    }"
                    id="dancer_date_of_birth"
                    class="form-text-field-input"
                    v-bind:class="{
                      'has-value': dancer.date_of_birth,
                    }"
                    name="date_of_birth"
                    v-on:change.native="isEmpty"
                  ></cleave>
                  <label class="floating-label" for="dancer_date_of_birth">{{
                    $t("forms.label.birthday")
                  }}</label>
                </div>
                <p class="form-hint text-caption">AAAA/MM/JJ</p>
                <p v-show="errors.has('date_of_birth')" class="form-msg-error">
                  {{ errors.first("date_of_birth") }}
                </p>
              </div>
              <div class="form-actions">
                <div class="btn btn-secondary" @click.prevent="hideModalDancer">
                  {{ $t("forms.actions.cancel") }}
                </div>
                <button
                  class="btn btn-primary"
                  type="submit"
                  :disabled="saving"
                >
                  {{ $t("forms.actions.done") }}
                </button>
              </div>
            </section>
          </form>
        </div>
      </article>
    </modal>
  </div>
</template>
<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import AdminFee from "../components/partials/admin-fee";
import TotalTable from "../components/partials/TotalTable";

import { i18n } from "../plugins/i18n.js";
import axios from "axios";
import CreditRoutine from "../components/CreditRoutine.vue";

export default {
  data: function () {
    return {
      years: [],
      status_id: "",
      saving: false,
      organization: {
        user: {
          name: null,
          email: null,
        },
        id: null,
        name: null,
        address: null,
        city: null,
        zipcode: null,
        phone: null,
        website: null,
        accronyme: null,
        locale: null,
      },
      payment: {
        id: "",
        payment_type_id: 1,
        subscription_id: "",
        receive_on: "",
        amount: "",
        note: "",
      },
      dancer: {
        id: "",
        first_name: "",
        last_name: "",
        date_of_birth: "",
      },
      authUrl: "",
    };
  },
  beforeRouteEnter(to, from, next) {
    store
      .dispatch("admin/subscription", {
        event: to.params.event,
        subscription_id: to.params.subscription_id,
      })
      .then(next)
      .catch((error) =>
        store.dispatch("feedback/setFeedback", {
          message: error.data,
          type: "warning",
        })
      );
  },

  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      user: "auth/user",
      isAdmin: "auth/isAdmin",
      content: "admin/subscription",
      accesstoken: "auth/accesstoken",
      //subscriptions: 'subscriptions/subscriptions',
      // ...
    }),
    subscription_id() {
      return this.$route.params.subscription_id;
    },
    event_name() {
      return this.$route.params.event;
    },

    calculatedCredit() {
      let categories = this.content["allCategories"];
      let mappedCats = categories.filter((category) => {
        return category.credit < 0;
      });

      return mappedCats;
    },
  },

  mounted() {},

  methods: {
    ...mapActions({
      // search: 'admin/search',
      // resetSearch: 'admin/resetSearch',
      updateUser: "auth/update",
      updateOrganization: "organizations/update",
      destroyRoutine: "subscriptions/destroyRoutine",
      addPayment: "admin/addPayment",
      updatePayment: "admin/updatePayment",
      deletePayment: "admin/deletePayment",
      updateStatus: "admin/updateStatus",
      createCreditNote: "admin/createCreditNote",
      updateDancer: "dancers/update",
      storeDancer: "dancers/store",
      destroyDancer: "dancers/destroy",
      setFeedback: "feedback/setFeedback",
      addToSchedule: "schedules/addToSchedule",
      getCategoriesByYear: "admin/categoriesByYear",
    }),
    async submitCreditNote() {
      this.saving = true;
      let mappedCats = this.calculatedCredit;

      // .content["allCategories"].filter((category) => {
      //   return category.credit < 0;
      // });

      const data = {};
      data.invoices = {};
      data.invoices.data = mappedCats;
      data.invoices.customer = this.organization;
      data.invoices.event_name = this.event_name;
      data.status_id = this.status_id;
      data.subscription_id = this.subscription_id;
      // this.createCreditNote(data);
      await store.dispatch("admin/createCreditNote", data);
      this.saving = false;
      this.$router.go(0);
    },
    changeCategoryPrice(event) {
      store
        .dispatch("admin/subscription", {
          event: this.event,
          subscription_id: this.subscription_id,
          year: event.target.value,
        })
        .catch((error) =>
          store.dispatch("feedback/setFeedback", {
            message: error.data,
            type: "warning",
          })
        );
    },
    beforeOpenPayment(event) {
      this.payment.id = event.params.id;
    },
    beforeOpenDancer(event) {
      this.dancer.id = event.params.id;
    },
    exportSubscriptionResume(event) {},
    getRoutineData() {
      let routines = [];

      this.content["organizations"].subscriptions[0].routines.map(function (
        routine,
        key
      ) {
        let obj = {};

        obj.id = routine.id;
        obj.Name = routine.name;
        obj.Categories = routine.category.name;
        obj.Niveau = routine.level.name;
        obj.Style = routine.style.name;
        obj.Entrées = routine.dancers.length;

        routines.push(obj);
      });

      return routines;
    },
    clickAddToSchedule() {
      store.dispatch("loading/setLoading", true);
      this.addToSchedule({
        routines: this.getRoutineData(),
        event: this.event_name,
        organization_id: this.content["organizations"].id,
      })
        .then(() => {
          store.dispatch("loading/setLoading", false);
          this.setFeedback({
            message: i18n.t("messages.global.success"),
            type: "success",
          });
        })
        .catch((error) => {
          this.setFeedback({
            message: error.data.msg,
            type: "warning",
          });

          //this.$setLaravelValidationErrorsFromResponse(error.data);
          store.dispatch("loading/setLoading", false);
        })
        .then((_) => store.dispatch("loading/setLoading", false));
    },
    deleteRoutine(ev, id) {
      if (window.confirm(i18n.t("messages.routine.deleteRoutine"))) {
        this.saving = true;
        this.destroyRoutine(id)
          .then(() => {
            //this.hide();
          })
          .catch((error) => {
            this.saving = false;
          })
          .then((_) => (this.saving = false));

        store.dispatch("admin/subscription", {
          event: this.event_name,
          subscription_id: this.subscription_id,
        });
      }
    },
    onSubmitUser(ev) {
      // TODO
    },
    onSubmitOrganization(ev) {
      this.saving = true;

      this.updateOrganization({
        id: this.organization.id,
        name: this.organization.name,
        address: this.organization.address,
        city: this.organization.city,
        zipcode: this.organization.zipcode,
        phone: this.organization.phone,
        website: this.organization.website,
        accronyme: this.organization.accronyme,
        locale: this.organization.locale,
        country_id: this.organization.country_id,
        state_id: this.organization.state_id,
      })
        .then(() => {
          this.setFeedback({
            message: i18n.t("messages.global.success"),
            type: "success",
          });
          //this.$router.push({ name: "dashboard.index" });
        })
        .catch((error) => {
          this.$setLaravelValidationErrorsFromResponse(error.data);
          this.saving = false;
        })
        .then((_) => (this.saving = false));
    },
    onSubmitPayment(ev) {
      this.saving = true;
      if (this.payment.id !== "") {
        this.updatePayment({
          id: this.payment.id,
          payment_type_id: this.payment.payment_type_id,
          subscription_id: this.subscription_id,
          receive_on: this.payment.receive_on,
          amount: this.payment.amount,
          note: this.payment.note,
        })
          .then(() => {
            this.$modal.hide("payment");
            this.payment.id = "";
            this.payment.payment_type_id = 1;
            this.payment.receive_on = "";
            this.payment.amount = "";
            this.payment.note = "";
            store.dispatch("admin/subscription", {
              event: this.event_name,
              subscription_id: this.subscription_id,
            });
          })
          .catch((error) => {
            this.$setLaravelValidationErrorsFromResponse(error.data);
            if (!error.data.errors) {
            }

            this.saving = false;
          })
          .then((_) => (this.saving = false));
      } else {
        this.addPayment({
          payment_type_id: this.payment.payment_type_id,
          subscription_id: this.subscription_id,
          receive_on: this.payment.receive_on,
          amount: this.payment.amount,
          note: this.payment.note,
          //query: this.query,
        })
          .then(() => {
            this.$modal.hide("payment");
            this.payment.payment_type_id = 1;
            this.payment.receive_on = null;
            this.payment.amount = null;
            this.payment.note = null;
            store.dispatch("admin/subscription", {
              event: this.event_name,
              subscription_id: this.subscription_id,
            });
          })
          .catch((error) => {
            this.$setLaravelValidationErrorsFromResponse(error.data);
            if (!error.data.errors) {
            }

            this.saving = false;
          })
          .then((_) => (this.saving = false));
      }
    },
    onDeletePayment(id) {
      this.saving = true;

      this.deletePayment(id)
        .then(() => {
          store.dispatch("admin/subscription", {
            event: this.event_name,
            subscription_id: this.subscription_id,
          });
        })
        .catch((error) => {
          this.saving = false;
        })
        .then((_) => (this.saving = false));
    },
    editPayment(id, ev) {
      let payments = this.content["organizations"].subscriptions[0].payments;
      let i = payments.map((item) => item.id).indexOf(id);
      this.payment.receive_on = payments[i].receive_on;
      this.payment.amount = payments[i].formatted_amount;
      this.payment.note = payments[i].note;
      this.$modal.show("payment", { id: id });
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    showPaymentModal(ev) {
      this.$modal.show("payment", { id: "" });
    },

    hidePaymentModal(ev) {
      this.$modal.hide("payment");
      // this.dancer.first_name = '';
      // this.dancer.last_name = '';
      // this.dancer.date_of_birth = '';
      // this.$setLaravelValidationErrorsFromResponse([]);
    },
    onSubmitFee(ev) {
      this.saving = true;
      // if (this.payment.id !== "") {
      //   this.updatePayment({
      //     id: this.payment.id,
      //     payment_type_id: this.payment.payment_type_id,
      //     subscription_id: this.subscription_id,
      //     receive_on: this.payment.receive_on,
      //     amount: this.payment.amount,
      //     note: this.payment.note
      //   })
      //     .then(() => {
      //       this.$modal.hide("payment");
      //       this.payment.id = "";
      //       this.payment.payment_type_id = 1;
      //       this.payment.receive_on = "";
      //       this.payment.amount = "";
      //       this.payment.note = "";
      //       store.dispatch("admin/subscription", {
      //         event: this.event_name,
      //         subscription_id: this.subscription_id
      //       });
      //     })
      //     .catch(error => {
      //       this.$setLaravelValidationErrorsFromResponse(error.data);
      //       if (!error.data.errors) {
      //       }

      //       this.saving = false;
      //     })
      //     .then(_ => (this.saving = false));
      // } else {
      //   this.addPayment({
      //     payment_type_id: this.payment.payment_type_id,
      //     subscription_id: this.subscription_id,
      //     receive_on: this.payment.receive_on,
      //     amount: this.payment.amount,
      //     note: this.payment.note
      //     //query: this.query,
      //   })
      //     .then(() => {
      //       this.$modal.hide("payment");
      //       this.payment.payment_type_id = 1;
      //       this.payment.receive_on = null;
      //       this.payment.amount = null;
      //       this.payment.note = null;
      //       store.dispatch("admin/subscription", {
      //         event: this.event_name,
      //         subscription_id: this.subscription_id
      //       });
      //     })
      //     .catch(error => {
      //       this.$setLaravelValidationErrorsFromResponse(error.data);
      //       if (!error.data.errors) {
      //       }

      //       this.saving = false;
      //     })
      //     .then(_ => (this.saving = false));
      // }
    },
    onDeleteFee(id) {
      this.saving = true;

      // this.deletePayment(id)
      //   .then(() => {
      //     store.dispatch("admin/subscription", {
      //       event: this.event_name,
      //       subscription_id: this.subscription_id
      //     });
      //   })
      //   .catch(error => {
      //     this.saving = false;
      //   })
      //   .then(_ => (this.saving = false));
    },
    editFee(id, ev) {
      // let payments = this.content["organizations"].subscriptions[0].payments;
      // let i = payments.map(item => item.id).indexOf(id);
      // this.payment.receive_on = payments[i].receive_on;
      // this.payment.amount = payments[i].formatted_amount;
      // this.payment.note = payments[i].note;
      // this.$modal.show("payment", { id: id });
      // ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    showFeeModal(ev) {
      this.$modal.show("fee", { id: "" });
    },
    hideFeeModal(ev) {
      this.$modal.hide("fee");
      // this.dancer.first_name = '';
      // this.dancer.last_name = '';
      // this.dancer.date_of_birth = '';
      // this.$setLaravelValidationErrorsFromResponse([]);
    },
    onSubmitDancer(ev) {
      this.saving = true;
      if (this.dancer.id !== "") {
        //let formatedDate = this.formatDate(this.dancer.date_of_birth);
        this.updateDancer({
          id: this.dancer.id,
          first_name: this.dancer.first_name,
          last_name: this.dancer.last_name,
          date_of_birth: this.dancer.date_of_birth,
          organization_id: this.content["organizations"].id,
        })
          .then(() => {
            store
              .dispatch("admin/subscription", {
                event: this.event_name,
                subscription_id: this.subscription_id,
              })
              .then(() => {
                this.hideModalDancer();
              });
          })
          .catch((error) => {
            this.$setLaravelValidationErrorsFromResponse(error.data);
            this.saving = false;
          })
          .then((_) => (this.saving = false));
      } else {
        //let formatedDate = this.formatDate(this.dancer.date_of_birth);
        this.storeDancer({
          first_name: this.dancer.first_name,
          last_name: this.dancer.last_name,
          date_of_birth: this.dancer.date_of_birth,
          organization_id: this.content["organizations"].id,
        })
          .then(() => {
            this.hideModalDancer();
            store
              .dispatch("admin/subscription", {
                event: this.event_name,
                subscription_id: this.subscription_id,
              })
              .then(() => {
                this.hideModalDancer();
              });
          })
          .catch((error) => {
            this.$setLaravelValidationErrorsFromResponse(error.data);
            this.saving = false;
          })
          .then((_) => (this.saving = false));
      }
    },
    hideModalDancer(ev) {
      this.$modal.hide("dancer");
      this.dancer.first_name = "";
      this.dancer.last_name = "";
      this.dancer.date_of_birth = "";
      this.$setLaravelValidationErrorsFromResponse([]);
    },
    addDancer() {
      this.$modal.show("dancer", { id: "" });
    },
    editDancer(id, ev) {
      let i = this.content["organizations"].dancers
        .map((item) => item.id)
        .indexOf(id);
      this.dancer.first_name = this.dancers[i].first_name;
      this.dancer.last_name = this.dancers[i].last_name;
      this.dancer.date_of_birth = this.dancers[i].date_of_birth;
      this.$modal.show("dancer", { id: id });
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    deleteDancer(id) {
      this.saving = true;

      this.destroyDancer(id)
        .then(() => {
          //this.hide();
          store.dispatch("admin/subscription", {
            event: this.event_name,
            subscription_id: this.subscription_id,
          });
        })
        .catch((error) => {
          this.saving = false;
        })
        .then((_) => (this.saving = false));
    },
    openActions(ev) {
      ev.currentTarget.parentNode.classList.toggle("has-menu-open");
    },
    closeActions(ev) {
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    changeStatus(status_id) {
      this.saving = true;
      this.status_id = status_id;
      const data = {};
      if (this.status_id == 3 && this.content["categories"].length) {
        data.invoices = {};
        data.invoices.data = this.content["categories"];
        data.invoices.customer = this.organization;
        data.invoices.event_name = this.event_name;
        data.status_id = this.status_id;
        data.subscription_id = this.subscription_id;
        //query: this.query,
      } else {
        data.status_id = this.status_id;
        data.subscription_id = this.subscription_id;
      }
      this.updateStatus(data)
        .then((res) => {
          // console.log(res, "first");
          store.dispatch("admin/subscription", {
            event: this.event_name,
            subscription_id: this.subscription_id,
          });
          let actions = document.getElementsByClassName("has-menu-open");

          if (actions.length > 0) {
            actions[0].classList.remove("has-menu-open");
          }
          this.setFeedback({
            message:
              this.status_id == 3
                ? i18n.t("messages.global.QBO_created")
                : i18n.t("messages.global.success"),
            type: "success",
          });
        })
        .catch((error) => {
          this.saving = false;
          this.setFeedback({
            message: i18n.t("messages.global.fail"),
            type: "warning",
          });
        })
        .then((_) => {
          // console.log(_, "second");
          this.saving = false;
        });
    },
    back() {
      this.$router.go(-1);
    },
    onClickTab(ev) {
      if (!ev.currentTarget.classList.contains("active")) {
        let link = document.querySelector(".nav-tabs-link.active");
        let tab = document.querySelector(".tab.active");
        let nextTab = ev.currentTarget.dataset.tab;

        link.classList.remove("active");
        tab.classList.remove("active");

        ev.currentTarget.classList.add("active");
        document
          .querySelector('.tab[data-tab="' + nextTab + '"]')
          .classList.add("active");
      }
    },
    isEmpty(ev) {
      if (ev.currentTarget.value.length > 0) {
        ev.currentTarget.classList.add("has-value");
      } else {
        ev.currentTarget.classList.remove("has-value");
        //document.getElementById("myDiv").classList.remove("form-group");
      }
    },
  },
  components: {
    Icon,
    Feedback,
    AdminFee,
    TotalTable,
    CreditRoutine,
  },

  created() {
    // const data = [];
    // data.year = 2020;
    // this.getCategoriesByYear(data);
    this.organization.user.name = this.content["organizations"].user.name;
    this.organization.user.email = this.content["organizations"].user.email;
    this.organization.id = this.content["organizations"].id;
    this.organization.name = this.content["organizations"].name;
    this.organization.address = this.content["organizations"].address;
    this.organization.city = this.content["organizations"].city;
    this.organization.accronyme = this.content["organizations"].accronyme;
    this.organization.phone = this.content["organizations"].phone;
    this.organization.zipcode = this.content["organizations"].zipcode;
    this.organization.locale = this.content["organizations"].locale;
    this.authUrl = this.content["authUrl"];
    this.years = this.content["years"];
  },
};
</script>
<style lang="scss" scoped>
.form-payment-add {
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
.btn-change-status {
  margin-left: 1.6rem;
}
.btn-generate-invoice {
  margin-top: 2.4rem;
}
.form-dancer-add {
  width: 304px;
  margin: 0 auto 0 auto;
}

.btn-inverted {
  margin-right: 1.6rem;
}
.card-col {
  width: auto;
  margin: 0 2.4rem;
}
.subscription-header {
  position: relative;
}
.music-play-container {
  right: 10px;
}
</style>
