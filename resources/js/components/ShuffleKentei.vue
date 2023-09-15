<template>
  <div>
    <button @click="shuffleItems">シャッフル</button>
    <div v-if="shuffledItems.length">
      {{ currentItem.italian }}
      {{ currentItem.japanese }}
      <label>音声</label>
      <audio controls :key="currentItem.id">
        <source :src="currentItem.voice_script_url" />
        Your browser does not support the audio element.
      </audio>
      <button @click="nextItem">次へ</button>
    </div>
    <button v-else @click="goToKentei">ホームへ</button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      items: [],
      shuffledItems: [],
      currentItemIndex: 0,
      currentAudio: null,
    };
  },
  computed: {
    currentItem() {
      return this.shuffledItems[this.currentItemIndex];
    },
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
    nextItem() {
      if (this.currentItemIndex < this.shuffledItems.length - 1) {
        this.currentItemIndex++;
      } else {
        this.goToKentei();
      }
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
