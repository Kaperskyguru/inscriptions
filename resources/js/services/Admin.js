import Vue from "vue";
import axios from "axios";

export default {
    creditNote(data) {
        return axios
            .post(`/api/v1/admin/creditnote/create`, data)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    index() {
        return axios
            .get(`/api/v1/admin`)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    event(event) {
        return axios
            .get(`/api/v1/admin/${event}`)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    subscription(event, subscription_id, year) {
        return axios
            .get(`/api/v1/admin/${event}/${subscription_id}?year=${year}`)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    search(data) {
        return axios
            .post("/api/v1/admin/search", data)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    addPayment(data) {
        return axios
            .post("/api/v1/admin/add/payment", data)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    updatePayment(data) {
        return axios
            .post("/api/v1/admin/update/payment", data)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    deletePayment(id) {
        return axios
            .delete(`/api/v1/admin/delete/payment/${id}`)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    updateStatus(data) {
        return axios
            .post("/api/v1/admin/update/status", data)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    },
    categoriesByYear(data) {
        return axios
            .get(`/api/v1/admin/categories/${data.year}`)
            .then(response => response.data)
            .catch(error => Promise.reject(error.response));
    }
};
