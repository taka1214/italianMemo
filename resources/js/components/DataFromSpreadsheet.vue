<template>
  <div>
    <button @click="shuffleAndNavigate" class="mb-3 btn btn-info">シャッフル</button>
    <div
      v-for="row in paginatedData"
      :key="row[0]"
      class="row-container d-flex justify-content-between align-items-start mb-2"
    >
      <div class="vertical-values px-2">
        <div class="text-truncate">{{ row[0] }}</div>
        <div class="text-truncate">{{ row[1] }}</div>
      </div>
      <div class="horizontal-value ml-auto px-2 text-truncate">{{ row[2] }}</div>
    </div>
    <div class="pagination-buttons mt-3 d-flex justify-content-center">
      <button @click="prevPage" :disabled="currentPage === 1">戻る</button>
      <span class="px-3">{{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">
        次へ
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fetchDataFromSpreadsheet: [],
      perPage: 20, // 1ページに表示するアイテム数
      currentPage: 1, // 現在のページ番号
    };
  },
  mounted() {
    this.fetchSpreadsheetData();
  },
  computed: {
    paginatedData() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.fetchDataFromSpreadsheet.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.fetchDataFromSpreadsheet.length / this.perPage);
    },
  },
  methods: {
    async fetchSpreadsheetData() {
      const GAS_URL =
        "https://script.google.com/macros/s/AKfycbyYMw077q8ntDcb_rBChfhmdn8NtTPnQjAkas7FrFLu__yXjlOY_LNs_ohMjEe2fb_ZdQ/exec";

      try {
        const response = await fetch(GAS_URL);
        const result = await response.json();
        this.fetchDataFromSpreadsheet = result.values;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    shuffleAndNavigate() {
      this.$router.push("/dataFromSpreadsheetShuffle");
    }
  },
};
</script>

<style scoped>
.row-container {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.vertical-values {
  display: flex;
  flex-direction: column;
  text-align: left;
  max-width: 280px;
}

.horizontal-value {
  text-align: right;
  max-width: 100px;
}
.pagination-buttons button {
  margin: 0 10px;
  padding: 5px 15px;
}

.pagination-buttons button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.pagination-buttons span {
  font-size: 1.1rem;
  font-weight: bold;
}
</style>