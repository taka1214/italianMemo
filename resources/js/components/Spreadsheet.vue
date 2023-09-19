<template>
  <div>
    <button @click="goToShuffle" class="mb-3 btn btn-info">シャッフル</button>
    <div
      v-for="item in paginatedItems"
      :key="item.id"
      class="d-flex justify-content-between align-items-start mb-2"
    >
      <div @click="editItem(item.id)" class="text-left px-2">
        <div class="text-left-class text-truncate">{{ item.italian }}</div>
        <div class="text-left-class text-truncate">{{ item.japanese }}</div>
      </div>
      <div class="ml-auto px-2">
        <button v-if="item.voice_script_url" @click="playVoiceScript(item.voice_script_url, $event)">
          音声
        </button>
        <span v-else>なし</span>
      </div>
    </div>
    <div v-if="items.length > itemsPerPage">
      <button @click="previousPage" :disabled="currentPage == 1">前へ</button>
      <span class="mx-3">{{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage == totalPages">
        次へ
      </button>
    </div>
  </div>
</template>
  
<script>
import axios from "axios";

export default {
  data() {
    return {
      items: [],
      currentAudio: null, // 現在再生中のオーディオオブジェクトを保持するためのデータ
      currentPage: 1, // 現在のページ
      itemsPerPage: 10, // 1ページあたりのアイテム数
    };
  },
  mounted() {
    this.fetchData();
  },
  computed: {
    paginatedItems() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.items.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    },
  },
  methods: {
    fetchData() {
      axios
        .get("/api/items/spreadsheet")
        .then((response) => {
          this.items = response.data;
        })
        .catch((error) => {
          console.error("データの取得中にエラーが発生しました:", error);
        });
    },
    playVoiceScript(voiceScriptPath, event) {
      const url = voiceScriptPath;

      if (this.currentAudio) {
        this.currentAudio.pause(); // すでに再生中の音声があれば、停止
        this.currentAudio = null;
        return;
      }

      const audio = new Audio(url);
      audio.play();

      audio.onended = () => {
        this.currentAudio = null;
      };

      this.currentAudio = audio;
      event.stopPropagation();
    },
    goToShuffle() {
      this.$router.push("/shuffle");
    },
    editItem(itemId) {
      this.$router.push(`/edit/${itemId}`);
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
  },
};
</script>

<style scoped>
.text-left-class {
  text-align: left;
  max-width: 280px;
}
</style>
