<template>
  <div>
    <button @click="goToShuffle" class="mb-3 btn btn-info">シャッフル</button>
    <div
      v-for="item in items"
      :key="item.id"
      class="d-flex justify-content-between align-items-start mb-2"
    >
      <div @click="editItem(item.id)" class="text-left px-2">
        <div class="text-left-class">{{ truncateText(item.italian) }}</div>
        <div class="text-left-class">
          {{ truncateJapaneseText(item.japanese) }}
        </div>
      </div>
      <div class="ml-auto px-2">
        <button @click="playVoiceScript(item.voice_script_url, $event)">
          音声
        </button>
      </div>
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
    };
  },
  mounted() {
    this.fetchData();
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
    truncateText(text) {
      if (text.length > 35) {
        return text.substring(0, 35) + "...";
      }
      return text;
    },
    truncateJapaneseText(text) {
      if (text.length > 17) {
        return text.substring(0, 17) + "...";
      }
      return text;
    },
  },
};
</script>
  
<style scoped>
.text-left-class {
  text-align: left;
}
</style>