<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="auto"
      @click:outside="closeDialog"
    >
      <v-card>
        <v-card-title class="headline grey lighten-2">
          Privacy Policy
        </v-card-title>

        <v-card-text>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </v-card-text>

        <v-divider></v-divider>

      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    props:['dialog'],
    methods:{
      closeDialog(){
        this.$emit('closeDialog');
      }
    }
  }
</script>

<style scoped>

</style>
