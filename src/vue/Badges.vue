<template>
        <v-data-table
          :headers="headers"
          :items="badges"
          :items-per-page="20"
          class="badges-table"
        >
          <template v-slot:item="badge">
            <tr>
              <td>{{badge.item.id}}</td>
              <td>
                <v-img class="img-circle" :src="badge.item.image_url" :width="50" height="50" />
              </td>
              <td>{{badge.item.name}}</td>
              <td>{{badge.item.type}}</td>
              <td>{{badge.item.position}}</td>
              <td><v-btn depressed color="primary" @click="editBadge(badge.item.id)"> Edit </v-btn></td>
            </tr>
          </template>
        </v-data-table>
</template>

<script>
  const axios = require('axios');

  export default {
    components: {
    },
    data () {
      return {
        sharedData: sharedData,
        headers: [
          {
            text: 'ID ',
            align: 'start',
            sortable: false,
            value: 'id',
          },
          { text: 'Image', value: 'image_url' },
          { text: 'Name', value: 'name' },
          { text: 'Type', value: 'type' },
          { text: 'Position', value: 'position' },
          { text: 'Actions', value: 'actions' },
        ],
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
      editBadge(){
        this.$emit('showBadge');
      }
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
