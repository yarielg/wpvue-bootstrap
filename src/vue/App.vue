<template>
  <v-app id="inspire">
    <!-- mini-variant prop on drawer -->
    <v-navigation-drawer
      dark
      src="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"
      width="250px"
      permanent
      app
      class="pt-4"
      color="grey lighten-3"
    >
      <v-list>
        <v-list-item
          v-for="([icon, text,component], i) in items"
          :key="i"
          link
        @click="showComponent(component)">
          <v-list-item-icon>
            <v-icon>{{ icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn @click="goToWordpress" block>
            Wordpress Dashboard
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-main>

      <v-container>

        <div v-if="activeComponent == 'badges'">
          <badges @showBadge="showBadgeHandler"></badges>
        </div>

        <div v-if="activeComponent == 'new-badge'">
          <new-badge></new-badge>
        </div>

      </v-container>

    </v-main>
  </v-app>
</template>

<script>
  const axios = require('axios');
  import $ from 'jquery';

  export default {
    components: {
    },
    data () {
      return {
        sharedData: sharedData,
        items: [
          ['mdi-shield', 'Badges','badges'],
          ['mdi-shield-plus', 'New Badge','new-badge'],
          ['mdi-cogs', 'Settings','settings'],
        ],
        dialog: false,
        activeComponent: 'badges'
      }
    },
    mounted() {

    },
    methods:{
      loadDefaults(){
        $('#vwp-plugin-loading').css('display','none');
      },

      goToWordpress(){
        window.location.replace("/wp-admin");
      },
      showComponent(component){
        this.activeComponent = component;
      },
      showBadgeHandler(){
        console.log('asdasdasdasdasd');
      }
    },
    created() {
       this.loadDefaults()
    },


  }
</script>

<style scoped>

</style>
