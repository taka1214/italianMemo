<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="">
          <div
            class="d-flex flex-column align-items-start py-2 px-2 text-gray-500 bgColor"
          >
            <div class="d-flex justify-content-end w-100">
              <button
                @click="navigate"
                class="my-2 px-3 border rounded-lg bg-white fs-small"
              >
                {{ buttonLabel }}
              </button>
            </div>
            <!-- ハンバーガーアイコンとドロップダウンメニュー -->
            <div class="d-flex">
              <button
                class="btn btn-secondary dropdown-toggle bg-white border border-light fs-small"
                type="button"
                id="dropdownMenuButton"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="color: gray"
              >
                カテゴリー
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button class="dropdown-item" @click="showDataFromSpreadsheet">
                  Spreadsheetからのデータ
                </button>
                <button class="dropdown-item" @click="showSpreadsheet">
                  Spreadsheet用
                </button>
                <button class="dropdown-item" @click="showKentei">
                  検定用
                </button>
              </div>
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
      buttonLabel: "新規登録",
    };
  },
  mounted() {
    this.updateButtonBasedOnRoute(); // コンポーネントがマウントされたときにボタンのラベルと動作を更新
  },
  watch: {
    "$route.path": "updateButtonBasedOnRoute", // ルートが変更されたときにも更新
    $route(to, from) {
      this.setCurrentComponentBasedOnRoute();
    },
  },
  created() {
    this.setCurrentComponentBasedOnRoute();
  },
  computed: {
    showRegisterButton() {
      return (
        this.$route.path === "/spreadSheet" || this.$route.path === "/kentei"
      );
    },
  },
  methods: {
    navigate() {
      this.$router.push(this.targetRoute);
    },
    updateButtonBasedOnRoute() {
      const path = this.$route.path;

      // /dataFromSpreadsheet, /dataFromSpreadsheetShuffle, /editSpreadsheet/:id の場合
      if (
        path === "/dataFromSpreadsheet" ||
        path === "/dataFromSpreadsheetShuffle" ||
        path.match(/^\/editSpreadsheet\/\d+/)
      ) {
        this.buttonLabel = "一覧画面";
        this.targetRoute = "/dataFromSpreadsheet";
      }
      // /spreadSheet の場合
      else if (path === "/spreadSheet") {
        this.buttonLabel = "新規登録";
        this.targetRoute = "/create";
      }
      // /shuffle, /edit/:id /create の場合
      else if (path === "/shuffle" || path.match(/^\/edit\/\d+/) || path === "/create") {
        this.buttonLabel = "一覧画面";
        this.targetRoute = "/spreadSheet";
      }
      // /kentei の場合
      else if (path === "/kentei") {
        this.buttonLabel = "新規登録";
        this.targetRoute = "/create";
      }
      // /shuffleKentei の場合
      else if (path === "/shuffleKentei") {
        this.buttonLabel = "一覧画面";
        this.targetRoute = "/kentei";
      } else {
        this.buttonLabel = "新規登録"; // デフォルトの設定
        this.targetRoute = "/create";
      }
    },

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
.dropdown-item {
  font-size: 0.9em
}
.fs-small {
  font-size: 0.9em;
}
</style>