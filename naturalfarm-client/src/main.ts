import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./style.css";
import App from "./App.vue";
import CustomerList from "./components/CustomerList.vue";
import CustomerForm from "./components/CustomerForm.vue";
import ArrayCalculator from "./components/ArrayCalculator.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/", component: CustomerList },
    { path: "/customer/create", component: CustomerForm },
    { path: "/customer/edit/:id", component: CustomerForm },
    { path: "/array-calculator", component: ArrayCalculator },
  ],
});

const app = createApp(App);
app.use(router);
app.mount("#app");
