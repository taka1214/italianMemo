<template>
  <div class="edit-spreadsheet">
    <h2>編集画面</h2>

    <!-- Form to edit the spreadsheet data -->
    <form @submit.prevent="updateSpreadsheetData">
      <!-- You can structure this form based on the columns of your spreadsheet. -->
      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="data.japanese"
          type="text"
          required
          class="form-control"
          rows="3"
          style="width: 90%"
        ></textarea>
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="data.italian"
          type="text"
          required
          class="form-control"
          rows="3"
          style="width: 90%"
        ></textarea>
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="data.answer"
          type="text"
          required
          class="form-control"
          rows="1"
          style="width: 90%"
        ></textarea>
      </div>
      <button type="submit" class="btn btn-primary">更新</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "EditSpreadsheet",
  data() {
    return {
      data: {
        id: null,
        japanese: "",
        italian: "",
        answer: "",
      },
    };
  },
  mounted() {
    this.getItem();
  },
  methods: {
    async updateSpreadsheetData() {
      
      const formData = new FormData();
      formData.append("italian", this.data.japanese);
      formData.append("japanese", this.data.italian);
      formData.append("answer", this.data.answer);

      try {
        await axios.post(
          `/spreadsheet/item/${this.data.id}`,
          formData
        );
        this.$router.push("/dataFromSpreadsheet");
      } catch (error) {
        console.error("Error updating the item:", error); 
      }  
      
    },

    getItem() {
      this.data.id = this.$route.params.id;
      this.data.japanese = this.$route.params.japanese;
      this.data.italian = this.$route.params.italian;
      this.data.answer = this.$route.params.answer;
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
