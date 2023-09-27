<template>
  <div class="detail-spreadsheet my-4">
    <h2>Dettaglio</h2>
    <!-- Display the spreadsheet data -->
    <div class="fs-smaller">
      <div class="m-4 d-flex justify-content-center">
        <p style="width: 90%">{{ japanese }}</p>
      </div>

      <div class="m-4 d-flex justify-content-center">
        <p style="width: 90%">{{ italian }}</p>
      </div>

      <div class="mt-4 d-flex justify-content-center">
        <p style="width: 90%">{{ answer }}</p>
      </div>

      <div class="mb-4 d-flex justify-content-center">
        <p style="width: 90%">{{ memo }}</p>
      </div>
    </div>
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
.fs-smaller {
  font-size: 0.9em;
}
.form-control {
  font-size: 0.9em;
}
</style>
