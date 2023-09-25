<template>
  <div>
    <div class="bgColor">
      <p class="pt-2">イタリア語テスト</p>
      <button @click="goToShuffle" class="mb-3 px-3 border rounded-4 text-gray-500 bg-white">シャッフル</button>
      <div class="search-box mt-2">
        <input
          type="text"
          v-model="searchKeyword"
          placeholder="キーワード検索"
          class="form-control"
        />
      </div>
    </div>
    <div
      v-for="item in paginatedItems"
      :key="item.id"
      class="d-flex justify-content-between align-items-start mb-2 mt-3"
    >
      <div @click="editItem(item.id)" class="text-left px-2 py-1">
        <div class="text-left-class text-truncate">{{ item.italian }}</div>
        <div class="text-left-class text-truncate">{{ item.japanese }}</div>
      </div>
      <div class="ml-auto px-2">
        <button class="py-1 px-2 border rounded-3 fs-small" v-if="item.voice_script_url" @click="playVoiceScript(item.voice_script_url, $event)">
          音声
        </button>
        <span v-else>なし</span>
      </div>
    </div>
    <div v-if="filteredData.length > itemsPerPage">
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
      searchKeyword: '', // キーワード検索用のデータプロパティ
    };
  },
  mounted() {
    this.fetchData();
  },
  computed: {
    paginatedItems() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredData.slice(start, end);  // filteredDataを利用
    },
    totalPages() {
      return Math.ceil(this.filteredData.length / this.itemsPerPage);  // filteredDataの長さを元に計算
    },
    filteredData() {
      return this.items.filter(item => {
        return (
          item.italian.toLowerCase().includes(this.searchKeyword.toLowerCase()) ||
          item.japanese.toLowerCase().includes(this.searchKeyword.toLowerCase())
        );
      });
    }
  },
  methods: {
    fetchData() {
      axios
        .get("/api/items/kentei")
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
      this.$router.push("/shuffleKentei");
    },
    editItem(itemId) {
      this.$router.push(`/edit/${itemId}`);
    },
  },
};
</script>
  
<style scoped>
.text-left-class {
  text-align: left;
  max-width: 270px;
  font-size: 0.9em;
}
.bgColor {
  background-color: rgb(242, 248, 255);
  font-size: 0.8em;
}
.fs-small {
  font-size: 0.8em;
}
.form-control::placeholder {
  font-size: 0.8em;
}
</style>