<template>
  <div>
    <button @click="shuffleItems">シャッフル</button>
    <div v-if="shuffledItems.length">
      <audio controls :key="currentItem.id" class="audio">
        <source :src="currentItem.voice_script_url" />
        Your browser does not support the audio element.
      </audio>
      <div class="itemWrapper">
        <button @click="toggleItalian" class="block-element-button">
          イタリア語
        </button>
        <transition name="slide-fade">
          <div v-if="showItalian" class="block-element">
            {{ currentItem.italian }}
          </div>
        </transition>
        <button @click="toggleJapanese" class="block-element-button">
          日本語
        </button>
        <transition name="slide-fade">
          <div v-if="showJapanese" class="block-element">
            {{ currentItem.japanese }}
          </div>
        </transition>
      </div>
      <button @click="nextItem">次へ</button>
    </div>
    <button v-else @click="goHome">ホームへ</button>
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
      showItalian: false,
      showJapanese: false,
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
        .get("/api/items/spreadsheet")
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
        this.goHome();
      }
    },
    goHome() {
      this.$router.push("/");
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
    toggleItalian() {
      this.showItalian = !this.showItalian;
    },
    toggleJapanese() {
      this.showJapanese = !this.showJapanese;
    },
  },
};
</script>

<style scoped>
.audio {
  margin-bottom: 15px;
}
.itemWrapper {
  margin-left: 10px;
}
.block-element {
  margin-bottom: 5px;
  margin-left: 5px;
  text-align: left;
  animation: fadein-bottom 1s ease-out forwards;
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(5px);
  opacity: 0;
}
.block-element-button {
  margin-bottom: 10px;
  position: relative;
  /* text-decoration: none; */
  /* color: #1d1d1d; */
  display: block;
  max-width: 200px;
  padding: 0.2rem 0.5rem;
  border-radius: 40px;
  border: 1px solid #212529;
  text-align: center;
  background-color: #fff;
  transition: transform 0.2s;
}
.block-element-button:active {
  transform: scale(0.95);
}
</style>
