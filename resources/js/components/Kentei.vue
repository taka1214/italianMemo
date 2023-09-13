<template>
  <div>
    <button @click="goToShuffle">シャッフル</button>
    <div v-for="item in items" :key="item.id">
      {{ item.italian }}
      {{ item.japanese }}
      <button @click="playVoiceScript(item.voice_script_url, $event)">
        音声
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
  },
};
</script>
  
  