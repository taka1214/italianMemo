<template>
  <div>
    <button @click="shuffleItems">シャッフル</button>
    <div v-for="item in shuffledItems" :key="item.id">
      {{ item.italian }}
      {{ item.japanese }}
      <button @click="playVoiceScript(item.voice_script_url, $event)">
        音声
      </button>
    </div>
    <button @click="goToKentei">一覧表示へ</button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      items: [],
      shuffledItems: [],
      currentAudio: null,
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
          this.shuffleItems();
        })
        .catch((error) => {
          console.error("データの取得中にエラーが発生しました:", error);
        });
    },
    shuffleItems() {
      this.shuffledItems = [...this.items].sort(() => Math.random() - 0.5);
    },
    playVoiceScript(voiceScriptPath, event) {
      const url = voiceScriptPath;

      if (this.currentAudio) {
        this.currentAudio.pause();
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
    goToKentei() {
      this.$router.push("/kentei");
    },
  },
};
</script>
