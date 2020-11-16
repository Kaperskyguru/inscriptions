<template>
  <div>
    <div id="layout-dashboard">
      <feedback></feedback>
      <header class="dashboard-header">
        <h1 class="title-primary">{{ this.content["city"] }}</h1>
        <admin-event-nav />
      </header>
      <form class="form-organization-search" @submit.prevent>
        <div class="form-group grid-12">
          <icon icon="search" class="icon-autocomplete"></icon>
          <input
            id="autocomplete_input"
            class="form-text-field-autocomplete"
            type="text"
            @keyup="autoComplete"
            v-model="query"
          />
        </div>
      </form>
      <section class="organization-container">
        <div
          class="alert-no-data"
          v-if="!this.content['organizations'].length"
        >
          <p class="alert-text text-body-display">
            {{ $t("admin.text.noSubscriptions") }}
          </p>
        </div>
        <div class="table" v-else v-bind:class="{ 'table-loading': loading }">
          <div class="table-header">
            <ul class="table-list">
              <li class="table-item grid-3">
                <span class="text-subhead">
                  {{ $t("admin.table.title.organization") }}
                </span>
              </li>
              <li class="table-item grid-2">
                <span
                  href="#"
                  class="text-subhead btn-filter"
                  @click.prevent="openActions"
                >
                  {{ $t("admin.table.title.status") }}
                  <icon icon="filter" class=""></icon>
                </span>
                <div class="actions-container action-filters">
                  <form autocomplete="off" class="form-filters" @submit.prevent>
                    <div class="form-group form-group-filters">
                      <div class="form-checkbox-container">
                        <input
                          id="filter_not_submitted"
                          class="form-checkbox-input"
                          type="checkbox"
                          value="1"
                          v-model="filters"
                          v-on:change="filterStatus()"
                        />
                        <label
                          class="form-checkbox-label text-body-display"
                          for="filter_not_submitted"
                        >
                          <div class="status status-default">
                            <span class="text-subhead">
                              {{ $t("admin.label.not_submitted") }}
                            </span>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="form-group form-group-filters">
                      <div class="form-checkbox-container">
                        <input
                          id="filter_to_be_reviewed"
                          class="form-checkbox-input"
                          type="checkbox"
                          value="2"
                          v-model="filters"
                          v-on:change="filterStatus()"
                        />
                        <label
                          class="form-checkbox-label text-body-display"
                          for="filter_to_be_reviewed"
                        >
                          <div class="status status-danger">
                            <span class="text-subhead">
                              {{ $t("admin.label.to_be_reviewed") }}
                            </span>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="form-group form-group-filters">
                      <div class="form-checkbox-container">
                        <input
                          id="filter_waiting_payment"
                          class="form-checkbox-input"
                          type="checkbox"
                          value="3"
                          v-model="filters"
                          v-on:change="filterStatus()"
                        />
                        <label
                          class="form-checkbox-label text-body-display"
                          for="filter_waiting_payment"
                        >
                          <div class="status status-warning">
                            <span class="text-subhead">
                              {{ $t("admin.label.waiting_payment") }}
                            </span>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="form-group form-group-filters">
                      <div class="form-checkbox-container">
                        <input
                          id="filter_paid"
                          class="form-checkbox-input"
                          type="checkbox"
                          value="4"
                          v-model="filters"
                          v-on:change="filterStatus()"
                        />
                        <label
                          class="form-checkbox-label text-body-display"
                          for="filter_paid"
                        >
                          <div class="status status-success">
                            <span class="text-subhead">
                              {{ $t("admin.label.paid") }}
                            </span>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="form-group form-group-filters">
                      <div class="form-checkbox-container">
                        <input
                          id="filter_waiting"
                          class="form-checkbox-input"
                          type="checkbox"
                          value="5"
                          v-model="filters"
                          v-on:change="filterStatus()"
                        />
                        <label
                          class="form-checkbox-label text-body-display"
                          for="filter_waiting"
                        >
                          <div class="status status-waiting">
                            <span class="text-subhead">
                              {{ $t("admin.label.waiting") }}
                            </span>
                          </div>
                        </label>
                      </div>
                    </div>
                  </form>
                  <div
                    class="action-close-overlay"
                    @click.prevent="closeActions"
                  ></div>
                </div>
              </li>

              <li class="table-item grid-1">
                <span class="text-subhead">
                  {{ $t("admin.table.title.routines") }}
                </span>
              </li>
              <li class="table-item grid-1">
                <span class="text-subhead">
                  {{ $t("admin.table.title.entries") }}
                </span>
              </li>
              <li class="table-item grid-2">
                <span class="text-subhead">
                  {{ $t("admin.table.title.updated") }}
                </span>
              </li>
              <li class="table-item grid-2">
                <span class="text-subhead">
                  {{ $t("admin.table.title.type") }}
                </span>
              </li>
            </ul>
          </div>

          <div class="table-body">
            <!-- {{ this.content["organizations"] }} -->
            <ul
              class="table-list table-list-body"
              v-for="organization in this.content['organizations']"
              v-bind:key="organization.id"
            >
              <li class="table-item grid-3">
                <span class="table-text text-body-display"
                  >{{ organization.name }}
                </span>
              </li>
              <li class="table-item grid-2">
                <div
                  :class="`status status-${organization.subscriptions[0].status.type}`"
                >
                  <span class="text-subhead">{{
                    organization.subscriptions[0].status.admin_label
                  }}</span>
                </div>
              </li>

              <li class="table-item grid-1">
                <span class="text-subhead">
                  {{ organization.subscriptions[0].routines_count }}
                </span>
              </li>
              <li class="table-item grid-1">
                <span class="text-subhead">
                  {{ organization.subscriptions[0].total_dancer }}
                </span>
              </li>
              <li class="table-item grid-2">
                <span class="text-subhead">
                  {{ organization.updatedago }}
                </span>
              </li>
              <li class="table-item grid-2">
                <span class="table-text text-body-display">
                  {{ organization.organization_type.name }}
                </span>
                <div class="table-menu" @click.prevent="openActions">
                  <icon icon="menu" class=""></icon>
                </div>
                <div class="actions-container">
                  <a
                    href="#"
                    @click.prevent="
                      deleteSubscription(organization.subscriptions[0].id)
                    "
                    class="action action-table"
                  >
                    <icon icon="delete" class=""></icon>
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

              <li class="table-item-link">
                <router-link
                  :to="{
                    name: 'admin.subscription',
                    params: {
                      event: getEvent(),
                      subscription_id: organization.subscriptions[0].id,
                    },
                  }"
                  class=""
                >
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </section>
    </div>
    <!-- Pagination here -->
    <!-- <pagination
      :data="Object.assign({}, this.content['organizations'])"
      :limit="3"
      @pagination-change-page="getResults"
    ></pagination> -->
  </div>
</template>
<script>
import Icon from "laravel-mix-vue-svgicon/IconComponent.vue";
import { mapActions, mapGetters } from "vuex";
import { store } from "../store";
import Feedback from "../components/Feedback";
import AdminEventNav from "../components/AdminEventNav";
//Object.assign({}, this.content['organizations'])

import { log } from "util";

export default {
  data: function () {
    return {
      loading: false,
      saving: false,
      filters: [],
      query: "",
    };
  },
  beforeRouteEnter(to, from, next) {
    store
      .dispatch("admin/subscriptions", to.params.event)
      .then(next)
      .catch((error) =>
        store.dispatch("feedback/setFeedback", {
          message: error,
          type: "warning",
        })
      );
  },
  beforeRouteUpdate(to, from, next) {
    store
      .dispatch("admin/subscriptions", to.params.event)
      .then(next)
      .catch((error) =>
        store.dispatch("feedback/setFeedback", {
          message: error,
          type: "warning",
        })
      );
  },

  computed: {
    // mix the getters into computed with object spread operator
    ...mapGetters({
      user: "auth/user",
      isAdmin: "auth/isAdmin",
      content: "admin/subscriptions",
      //subscriptions: 'subscriptions/subscriptions',
      // ...
    }),
    event() {
      return this.$route.params.event;
    },
  },

  mounted() {
    // Fetch initial results
    // this.getResults();
  },

  methods: {
    ...mapActions({
      search: "admin/search",
      destroy: "admin/destroySubscriptions",
      resetSearch: "admin/resetSearch",
    }),
    getResults(page = 1) {
      this.$axios
        .get(`/api/v1/admin/${this.event}?page=` + page)
        .then((response) => {
          this.content["organizations"] = response;
        });
    },
    getEvent() {
      return this.event;
    },
    filterStatus(ev) {
      if (this.loading === false) {
        this.loading = true;
        this.search({
          query: this.query,
          filters: this.filters,
          event: this.event,
        })
          .then(() => {
            this.loading = false;
            //console.log(this.results)
            //this.$router.push({ name: "dashboard.index" });
          })
          .catch((error) => {
            this.loading = false;
            //this.$setLaravelValidationErrorsFromResponse(error)
            //this.saving = false;
          });
      }
    },
    autoComplete(ev) {
      if (this.query.length > 2 && this.loading === false) {
        this.loading = true;
        this.search({
          query: this.query,
          filters: this.filters,
          event: this.event,
        })
          .then(() => {
            this.loading = false;
            //console.log(this.results)
            //this.$router.push({ name: "dashboard.index" });
          })
          .catch((error) => {
            this.loading = false;
            //this.$setLaravelValidationErrorsFromResponse(error)
            //this.saving = false;
          });
        //.then(_ => (this.saving = false));
      } else if (this.query.length == 0) {
        this.filters = [];
        this.resetSearch(this.event);
      }
    },
    openActions(ev) {
      ev.currentTarget.parentNode.classList.toggle("has-menu-open");
    },
    closeActions(ev) {
      ev.currentTarget.parentNode.parentNode.classList.remove("has-menu-open");
    },
    deleteSubscription(id) {
      this.saving = true;

      this.destroy(id)
        .then(() => {
          //this.hide();
        })
        .catch((error) => {
          this.saving = false;
        })
        .then((_) => (this.saving = false));
    },
  },
  components: {
    Icon,
    Feedback,
    AdminEventNav,
  },

  created() {},
};
</script>
