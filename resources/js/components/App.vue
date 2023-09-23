<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="">
          <div
            class="d-flex flex-column align-items-start py-2 px-2 text-gray-500 bgColor"
          >
            <!-- ハンバーガーアイコンとドロップダウンメニュー -->
            <div class="d-flex d-md-none">
              <button
                class="btn btn-secondary dropdown-toggle bg-white border border-light"
                type="button"
                id="dropdownMenuButton"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="color: gray;"
              >
                ☰
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button class="dropdown-item" @click="showDataFromSpreadsheet">
                  spreadsheetからデータ取得用
                </button>
                <button class="dropdown-item" @click="showSpreadsheet">
                  spreadsheet用
                </button>
                <button class="dropdown-item" @click="showKentei">
                  検定用
                </button>
              </div>
            </div>

            <!-- 通常のボタン（デスクトップサイズでのみ表示） -->
            <div class="d-none d-md-flex justify-content-around w-100">
              <button
                class="my-1 px-3 border rounded-lg"
                @click="showDataFromSpreadsheet"
              >
                spreadsheetからデータ取得用
              </button>
              <button
                class="my-1 px-3 border rounded-lg"
                @click="showSpreadsheet"
              >
                spreadsheet用
              </button>
              <button class="my-1 px-3 border rounded-lg" @click="showKentei">
                検定用
              </button>
            </div>
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
import DataFromSpreadsheet from "./DataFromSpreadsheet.vue";

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
    showDataFromSpreadsheet() {
      this.currentComponentName = "DataFromSpreadsheet";
      this.$router.push("/dataFromSpreadsheet");
    },
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
        case "/dataFromSpreadsheet":
          this.currentComponentName = "DataFromSpreadsheet";
          break;
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
    DataFromSpreadsheet,
  },
};
</script>

<style scoped>
.bgColor {
  background-color: rgb(242, 248, 255);
}
</style>