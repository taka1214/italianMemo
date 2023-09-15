<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card custom-card">
          <router-link
            v-if="showRegisterButton"
            to="/create"
            class="btn btn-primary"
            >新規登録</router-link>
          <div class="d-flex justify-content-between py-2 px-2">
            <button @click="showSpreadsheet">spreadsheet用</button>
            <button @click="showKentei">検定用</button>
          </div>
          <component
            v-if="currentComponentName"
            :is="currentComponentName"
          ></component>
          <router-view v-else></router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Spreadsheet from "./Spreadsheet.vue";
import Kentei from "./Kentei.vue";

export default {
  data() {
    return {
      currentComponentName: null,
    };
  },
  computed: {
    showRegisterButton() {
      return (
        this.$route.path === "/spreadSheet" || this.$route.path === "/kentei"
      );
    },
  },
  methods: {
    showSpreadsheet() {
      this.currentComponentName = "Spreadsheet";
      this.$router.push("/spreadSheet");
    },
    showKentei() {
      this.currentComponentName = "Kentei";
      this.$router.push("/kentei");
    },
    setCurrentComponentBasedOnRoute() {
      switch (this.$route.path) {
        case "/":
        case "/spreadSheet":
          this.currentComponentName = "Spreadsheet";
          break;
        case "/kentei":
          this.currentComponentName = "Kentei";
          break;
        default:
          this.currentComponentName = null;
      }
    },
  },
  watch: {
    $route(to, from) {
      this.setCurrentComponentBasedOnRoute();
    },
  },
  created() {
    this.setCurrentComponentBasedOnRoute();
  },
  components: {
    Spreadsheet,
    Kentei,
  },
};
</script>