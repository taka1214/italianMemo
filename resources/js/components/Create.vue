<template>
  <div>
    <h1>Iscrizione</h1>
    <form @submit.prevent="createItem">
      <!-- イタリア語 -->
      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="newItem.italian"
          placeholder="Italiano"
          class="form-control"
          style="width: 90%"
          rows="5"
          required
        ></textarea>
      </div>

      <!-- 日本語 -->
      <div class="mb-3 d-flex justify-content-center">
        <textarea
          v-model="newItem.japanese"
          placeholder="giapponese"
          class="form-control"
          style="width: 90%"
          rows="5"
          required
        ></textarea>
      </div>

      <!-- メモ -->
      <div class="mb-3">
        <div class="d-flex align-items-center mb-2">
          <label class="me-1 mx-2">メモ</label>
          <input
            class="form-check-input"
            type="checkbox"
            v-model="memoChecked"
          />
        </div>
        <div class="d-flex justify-content-center">
          <textarea
            v-model="newItem.memo"
            :disabled="!memoChecked"
            class="form-control"
            style="width: 90%"
            rows="5"
          ></textarea>
        </div>
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
          <button @click="triggerFileInput" class="px-2 py-1 border rounded-3 border-2 mx-2" type="button">
            音声ファイル
          </button>
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
            v-model="newItem.category"
            value="spreadsheet"
          />
          <label class="form-check-label">Spreadsheet</label>
        </div>
        <div class="form-check">
          <input
            class="form-check-input"
            type="radio"
            v-model="newItem.category"
            value="kentei"
          />
          <label class="form-check-label">検定</label>
        </div>
      </div>

      <button type="submit" class="py-1 px-4 my-3 border rounded-3 border-1">登録</button>
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
      memoChecked: false,
      selectedFileName: "",
    };
  },
  methods: {
    async createItem() {
      const formData = new FormData();
      formData.append("italian", this.newItem.italian);
      formData.append("japanese", this.newItem.japanese);
      if (this.$refs.voiceScriptFile.files[0]) {
        formData.append("voice_script", this.$refs.voiceScriptFile.files[0]);
      }
      formData.append("memo", this.newItem.memo);
      formData.append("category", this.newItem.category);

      try {
        const response = await axios.post("/api/items", formData);
        console.log("Item created:", response.data);
        this.$router.push("/");
      } catch (error) {
        console.error("Error creating the item:", error);
      }
    },

    triggerFileInput() {
      this.$refs.voiceScriptFile.click();
    },
    updateFileName(event) {
      if (event.target.files.length > 0) {
        this.selectedFileName = event.target.files[0].name;
      }
    },
  },
};
</script>
