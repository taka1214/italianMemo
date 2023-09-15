<template>
  <div>
    <h1>編集</h1>
    <form @submit.prevent="updateItem">
      <!-- イタリア語 -->
      <div class="mb-3 d-flex justify-content-center">
        <input
          v-model="currentItem.italian"
          type="text"
          required
          class="form-control"
          rows="5"
          style="width: 90%"
        />
      </div>

      <!-- 日本語 -->
      <div class="mb-3 d-flex justify-content-center">
        <input
          v-model="currentItem.japanese"
          type="text"
          rows="5"
          required
          class="form-control"
          style="width: 90%"
        />
      </div>

      <!-- メモ -->
      <div class="mb-3">
        <label class="me-1 mx-2">Memo:</label>
        <div class="d-flex justify-content-center">
          <textarea
            v-model="currentItem.memo"
            class="form-control"
            style="width: 90%"
            rows="5"
          ></textarea>
        </div>
      </div>

      <!-- 音声プレーヤー -->
      <div
        v-if="currentItem.voice_script_url"
        class="mb-3 d-flex justify-content-center"
      >
        <audio controls>
          <source :src="currentItem.voice_script_url" />
          Your browser does not support the audio element.
        </audio>
      </div>

      <!-- 音声 -->
      <div class="mb-3">
        <div class="d-flex mb-2">
          <!-- Hidden actual input -->
          <input
            ref="voiceScriptFile"
            type="file"
            @change="updateFileName"
            class="d-none"
            id="fileInput"
          />
          <!-- Custom button -->
          <button @click="triggerFileInput" class="btn btn-primary mx-2" type="button">音声ファイル</button>
        </div>
        <!-- Display selected file name -->
        <span v-if="selectedFileName">{{ selectedFileName }}</span>
      </div>

      <!-- ラジオボタン -->
      <div class="mb-3 d-flex align-items-center mx-2">
        <div class="form-check me-3">
          <input
            class="form-check-input"
            type="radio"
            v-model="currentItem.category"
            value="spreadsheet"
          />
          <label class="form-check-label">Spreadsheet</label>
        </div>
        <div class="form-check">
          <input
            class="form-check-input"
            type="radio"
            v-model="currentItem.category"
            value="kentei"
          />
          <label class="form-check-label">検定</label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">更新</button>
      <button @click.prevent="deleteItem" class="btn btn-danger">削除</button>
    </form>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data() {
    return {
      currentItem: {
        italian: "",
        japanese: "",
        voice_script: null,
        memo: "",
        category: "",
      },
      selectedFileName: null, // こちらを追加
    };
  },
  methods: {
    async getItemDetails() {
      try {
        const response = await axios.get(`/api/items/${this.$route.params.id}`);
        this.currentItem = response.data;
      } catch (error) {
        console.error("Error fetching item:", error);
      }
    },

    async updateItem() {
      const formData = new FormData();
      formData.append("italian", this.currentItem.italian);
      formData.append("japanese", this.currentItem.japanese);
      if (this.$refs.voiceScriptFile && this.$refs.voiceScriptFile.files[0]) {
        formData.append("voice_script", this.$refs.voiceScriptFile.files[0]);
      }
      formData.append("memo", this.currentItem.memo);
      formData.append("category", this.currentItem.category);

      try {
        const response = await axios.post(
          `/api/item/${this.$route.params.id}`,
          formData
        );
        this.$router.push("/");
      } catch (error) {
        console.error("Error updating the item:", error);
      }
    },
    async deleteItem() {
      if (!confirm("このアイテムを削除してよろしいですか？")) {
        return;
      }

      try {
        await axios.delete(`/api/item/${this.$route.params.id}`);
        this.$router.push("/"); // 削除後、ホームページにリダイレクト
      } catch (error) {
        console.error("Error deleting the item:", error);
      }
    },
    triggerFileInput() {
      event.preventDefault();
      this.$refs.voiceScriptFile.click();
    },
    updateFileName(event) {
      // こちらもmethodsセクションに移動
      if (event.target.files.length > 0) {
        this.selectedFileName = event.target.files[0].name;
      }
    },
  },
  created() {
    this.getItemDetails();
  },
};
</script>
