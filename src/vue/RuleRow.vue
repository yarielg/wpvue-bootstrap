<template>

</template>

<script>
  const axios = require('axios');

  export default {
    components: {
    },
    data () {
      return {
        sharedData: sharedData,
        items: [
          { title: 'Home', icon: 'mdi-view-dashboard' },
          { title: 'About', icon: 'mdi-forum' },
        ],
        badges: [],
      }
    },
    mounted() {

    },
    methods:{
      getBadges(){
        const formData = new FormData();
        formData.append('action', 'get_badges');
        axios.post(this.sharedData.ajax_url, formData)
          .then( response => {
            this.badges = response.data.badges;
          })
      },

    },
    created() {
      this.getBadges()
    },
  }
</script>

<style>
  .img-circle {
    border-radius: 50% !important;
  }
</style>
