<template>
  <div>
    <div class="bgColor">
      <p class="pt-2">イタリア語テスト</p>
      <button
        @click="shuffleAndNavigate"
        class="mb-4 px-3 py-1 border rounded-4 text-gray-500 bg-white"
      >
        シャッフル
      </button>
      <div class="search-box my-2">
        <input v-model="searchTerm" placeholder="キーワード検索..." class="form-control" />
      </div>
    </div>
    <div
      v-for="row in paginatedData"
      :key="row[0]"
      class="row-container d-flex justify-content-between align-items-start my-3"
      @click="toEditSpreadsheet(row[0])"
    >
      <div class="vertical-values px-2">
        <div class="text-truncate">{{ row[1] }}</div>
        <div class="text-truncate">{{ row[2] }}</div>
      </div>
      <div class="horizontal-value ml-auto px-2 text-truncate">
        {{ row[3] }}
      </div>
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
      sheetId: "1804Jv1V8MRlk1UYi-fSfewHg6HsUUCIO26NGCYGr8Vs", // スプレッドシートIDを指定
      apiKey: "AIzaSyCSLxIeQ-hyhETjXLYfQcrbeikTXPXgOpE", // APIキーを指定
      range: `時制(仮)!A1:E`, // 取得したい範囲を指定
      searchTerm: "", // 検索文字列のためのデータプロパティ
    };
  },
  mounted() {
    this.fetchSpreadsheetData();
  },
  computed: {
    paginatedData() {
      const filteredData = this.filteredData;
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return filteredData.slice(start, end);
    },
    filteredData() {
      if (!this.searchTerm) return this.fetchDataFromSpreadsheet;

      return this.fetchDataFromSpreadsheet.filter((row) => {
        return row.some((cell) =>
          cell.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      });
    },
    totalPages() {
      return Math.ceil(this.filteredData.length / this.perPage);
    },
  },
  methods: {
    async fetchSpreadsheetData() {
      const SHEETS_API_URL = `https://sheets.googleapis.com/v4/spreadsheets/${this.sheetId}/values/${this.range}?key=${this.apiKey}`;

      try {
        const response = await fetch(SHEETS_API_URL);
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
    },
    toEditSpreadsheet(id) {
      this.$router.push(`/editSpreadsheet/${id}`);
    },
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
  font-size: 0.9em;
}

.horizontal-value {
  text-align: right;
  max-width: 100px;
  font-size: 0.9em;
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
.bgColor {
  background-color: rgb(242, 248, 255);
  font-size: 0.8em;
}
.form-control::placeholder {
  font-size: 0.8em;
}
</style>