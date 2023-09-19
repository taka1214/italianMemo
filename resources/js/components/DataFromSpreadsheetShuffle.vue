<template>
  <div>
    <div class="item-container" v-if="shuffledData[currentIndex]">
      <!-- A列とB列の表示 -->
      <div class="my-3">{{ shuffledData[currentIndex][0] }}</div>
      <div class="my-3">{{ shuffledData[currentIndex][1] }}</div>
      <!-- C列の表示。初期は非表示 -->
      <div class="mt-3" v-if="isVisible">{{ shuffledData[currentIndex][2] }}</div>
      <div v-if="isVisible">{{ shuffledData[currentIndex][3] }}</div>
      <!-- ボタンをクリックするとC列の内容をトグルする -->
      <button class="mt-3" @click="toggleVisibility">
        {{ isVisible ? "隠す" : "答え" }}
      </button>
    </div>
    <button @click="nextItem" v-if="currentIndex < shuffledData.length - 1">
      次へ
    </button>
    <button @click="navigateBack" v-else>終了</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      shuffledData: [],
      currentIndex: 0,
      fetchDataFromSpreadsheet: [],
      isVisible: false,
    };
  },
  mounted() {
    this.fetchSpreadsheetData();
  },
  methods: {
    shuffle(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
    },
    nextItem() {
      if (this.currentIndex < this.shuffledData.length - 1) {
        this.currentIndex++;
        this.isVisible = false;
      }
    },
    navigateBack() {
      this.$router.push("/dataFromSpreadsheet");
    },
    async fetchSpreadsheetData() {
      const GAS_URL =
        "https://script.google.com/macros/s/AKfycbyYMw077q8ntDcb_rBChfhmdn8NtTPnQjAkas7FrFLu__yXjlOY_LNs_ohMjEe2fb_ZdQ/exec";

      try {
        const response = await fetch(GAS_URL);
        const result = await response.json();
        this.fetchDataFromSpreadsheet = result.values;
        this.shuffledData = this.shuffle(this.fetchDataFromSpreadsheet);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    toggleVisibility() {
      this.isVisible = !this.isVisible;
    },
  },
};
</script>

<style scoped>
.item-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 300px;
  font-size: 1.0rem;
}
</style>
