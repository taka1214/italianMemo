<template>
  <div>
    <div class="item-container mt-4" v-if="shuffledData[currentIndex]">
      <div class="my-3">{{ shuffledData[currentIndex][0] }}</div>
      <div class="my-3">{{ shuffledData[currentIndex][1] }}</div>
      <div class="mt-3" v-if="isVisible">{{ shuffledData[currentIndex][2] }}</div>
      <div v-if="isVisible">{{ shuffledData[currentIndex][3] }}</div>
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
      const sheetId = "1804Jv1V8MRlk1UYi-fSfewHg6HsUUCIO26NGCYGr8Vs"; // あなたのスプレッドシートIDを指定
      const apiKey = "AIzaSyCSLxIeQ-hyhETjXLYfQcrbeikTXPXgOpE"; // あなたのAPIキーを指定
      const range = `時制(仮)!B1:E`; // 取得したい範囲を指定

      const SHEETS_API_URL = `https://sheets.googleapis.com/v4/spreadsheets/${sheetId}/values/${range}?key=${apiKey}`;

      try {
        const response = await fetch(SHEETS_API_URL);
        const result = await response.json();
        this.shuffledData = this.shuffle(result.values);
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
