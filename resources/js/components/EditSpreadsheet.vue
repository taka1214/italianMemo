<template>
  <div class="edit-spreadsheet">
    <h2>編集画面</h2>

    <!-- Form to edit the spreadsheet data -->
    <form @submit.prevent="updateSpreadsheetData">
      <!-- You can structure this form based on the columns of your spreadsheet. -->
      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="this.japanese"
          type="text"
          required
          class="form-control"
          rows="3"
          style="width: 90%"
        ></textarea>
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="this.italian"
          type="text"
          required
          class="form-control"
          rows="3"
          style="width: 90%"
        ></textarea>
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="this.answer"
          type="text"
          required
          class="form-control"
          rows="1"
          style="width: 90%"
        ></textarea>
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="this.memo"
          type="text"
          required
          class="form-control"
          rows="1"
          style="width: 90%"
        ></textarea>
      </div>
      <button type="submit" class="btn btn-primary">更新</button>
    </form>
    <button @click="back()" class="my-2">戻る</button>
  </div>
</template>

<script>
export default {
  name: "EditSpreadsheet",
  data() {
    return {
      id: this.$route.params.id,
      japanese: "",
      italian: "",
      answer: "",
      memo: "",
      item: null,
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      const rowId = this.id;
      const sheetId = "1804Jv1V8MRlk1UYi-fSfewHg6HsUUCIO26NGCYGr8Vs";
      const apiKey = "AIzaSyCSLxIeQ-hyhETjXLYfQcrbeikTXPXgOpE";
      const range = `時制(仮)!B${rowId}:E${rowId}`;

      try {
        const response = await fetch(
          `https://sheets.googleapis.com/v4/spreadsheets/${sheetId}/values/${range}?key=${apiKey}`
        );
        const result = await response.json();
        this.item = result.values[0];
        this.japanese = this.item[0];
        this.italian = this.item[1];
        this.answer = this.item[2];
        this.memo = this.item[3];
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },

    async updateSpreadsheetData() {
      
    },

    back() {
      this.$router.push("/dataFromSpreadsheet");
    },
  },
};
</script>

<style scoped>
/* Add your styles here */
.form-group {
  margin-bottom: 20px;
}
</style>
