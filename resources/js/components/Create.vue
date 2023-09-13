<template>
  <div>
    <h1>アイテムの作成</h1>
    <form @submit.prevent="createItem">
      <div>
        <label>Italian:</label>
        <input v-model="newItem.italian" type="text" required />
      </div>

      <div>
        <label>Japanese:</label>
        <input v-model="newItem.japanese" type="text" required />
      </div>

      <div>
        <label>Voice Script:</label>
        <input ref="voiceScriptFile" type="file">
      </div>

      <div>
        <label>Memo:</label>
        <textarea v-model="newItem.memo"></textarea>
      </div>

      <div>
        <label>Category:</label>
        <input type="radio" v-model="newItem.category" value="spreadsheet" />
        Spreadsheet
        <input type="radio" v-model="newItem.category" value="kentei" /> Kentei
      </div>

      <button type="submit">登録</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      newItem: {
        italian: "",
        japanese: "",
        voice_script: null,
        memo: "",
        category: "",
      },
    };
  },
  methods: {
    async createItem() {
      const formData = new FormData();
      formData.append("italian", this.newItem.italian);
      formData.append("japanese", this.newItem.japanese);
      formData.append("voice_script", this.$refs.voiceScriptFile.files[0]); 
      formData.append("memo", this.newItem.memo);
      formData.append("category", this.newItem.category);

      try {
        const response = await axios.post("/api/items", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        console.log("Item created:", response.data);
        this.$router.push("/"); 
      } catch (error) {
        console.error("Error creating the item:", error);
      }
    },
  },
};
</script>
