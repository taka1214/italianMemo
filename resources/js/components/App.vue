<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="d-flex justify-content-between">
            <button @click="currentComponent = 'Spreadsheet'">
              spreadsheet用
            </button>
            <button @click="currentComponent = 'Kentei'">検定用</button>
          </div>
          <component v-if="currentComponent" :is="currentComponent"></component>
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
      currentComponent: null,
    };
  },
  methods: {
    showSpreadsheet() {
      this.currentComponent = "Spreadsheet";
    },
    showKentei() {
      this.currentComponent = "Kentei";
    },
    setCurrentComponentBasedOnRoute() {
      // URLのパスに応じてcurrentComponentを設定
      switch (this.$route.path) {
        case "/":
        case "/spreadSheet":
          this.currentComponent = "Spreadsheet";
          break;
        case "/kentei":
          this.currentComponent = "Kentei";
          break;
        default:
          this.currentComponent = null;
      }
    },
  },
  watch: {
    $route: function (to, from) {
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
