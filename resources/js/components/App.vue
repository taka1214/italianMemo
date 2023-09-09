<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <button @click="currentComponent = 'Spreadsheet'">spreadsheet用</button>
                  <button @click="currentComponent = 'Kentei'">検定用</button>
                  <component :is="currentComponent"></component>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import Spreadsheet from './Spreadsheet.vue';
import Kentei from './Kentei.vue';


  export default {
      data(){
        return {
          currentComponent: "Spreadsheet"
        }
      },
      components:{
        Spreadsheet,
        Kentei,
      }
  }
</script>
