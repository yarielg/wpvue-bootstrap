<template>

  <div>
    <h1 v-if="editMode">Edit Badge</h1>
    <h1 v-else >New Badge</h1>

    <v-form v-model="valid" ref="form">
      <v-container>
        <v-row>
          <v-col
            cols="12"
            md="8"
          >

            <v-container>
              <v-row>

                <v-col cols="12" md="12">
                  <v-text-field
                    v-model="name"
                    :rules="nameRules"
                    :counter="20"
                    label="Badge Name"
                    required
                  ></v-text-field>
                </v-col>
              </v-row>

              <h3>Badge Design</h3>
              <hr>
              <p>Customize the badge and choose the look and feel that you want</p>
              <v-row>
                <v-col cols="12" md="4">
                  <v-select
                    v-model="badge_type"
                    :items="badge_type_items"
                    :rules="badgeTypeRules"
                    label="Type of Badge"
                    required
                    @change="badgeTypeChanged"
                  ></v-select>
                </v-col>
                <v-col
                  cols="12"
                  md="4"
                  class="img-file-loader"
                >
                  <v-file-input v-if="is_image" v-model="image" @change="fileChanged" label="Choose Image" />
                  <v-img :src="url" v-if="is_image" :rules="imageRules" />

                  <v-text-field
                    v-if="!is_image"
                    v-model="text_badge"
                    :counter="20"
                    label="Badge Text"
                    required
                  ></v-text-field>
                </v-col>


                <v-col
                  cols="12"
                  md="4"
                >
                  <v-select
                    v-model="position"
                    :items="position_items"
                    :rules="positionRules"
                    label="Badge Position"
                    required
                    @change="positionChanged"
                  ></v-select>
                </v-col>
              </v-row>

              <h3>Badge Rule</h3>
              <hr>
              <p>The badge will be shown if the product met the follow criteria</p>
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-select
                    v-model="type"
                    :items="type_items"
                    :rules="typeRules"
                    label="Type"
                    required
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-col>

          <!-- Preview Section -->
          <v-col cols="12" md="4" class="badge_preview">
             <div class="badge_container" :style="badgeContainerCss">
               <span :style="badgeCss" class="badge_show">
                 <img class="img-badge" v-if="is_image" :src="image_badge" alt="">
                 <span class="text-badge" v-if="!is_image" alt="">{{ text_badge }}</span>
               </span>
             </div>
          </v-col>


        </v-row>
      </v-container>

      <v-row>
        <v-col cols="12">
          <v-btn class="success" @click="submit" :loading="loading">Save</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </div>
</template>

<script>
  const axios = require('axios');
  import $ from 'jquery';

  export default {
    data: () => ({
      sharedData: sharedData,
      editMode: false,
      valid: false,
      name: '',
      type: '',
      defaultBadge: true,
      type_items: [
        'Category',
        'Single Product',
        'Low Stock',
      ],
      typeRules:[
        v => !!v || 'Type is required'
      ],
      image: null,
      nameRules: [
        v => !!v || 'Name is required',
        v => v.length <= 15 || 'Name must be less than 20 characters',
      ],
      position: '',
      position_items:[
        { text: 'Top Left', value:1 }, { text:'Top Right', value: 2 }, { text: 'Bottom Right', value: 3 },{ text: 'Bottom Left', value: 4}
      ],
      positionRules: [
        v => !!v || 'Position is required',
      ],
      imageRules: [
        v => !!v || 'Image is required',
      ],
      badge_type: { text: 'Image Badge', value: 1},
      badge_type_items: [ { text: 'Image Badge', value: 1}, { text: 'Text Badge', value: 2 } ],
      badgeTypeRules:[],
      loading: false,
      image_badge: sharedData.plugin_path + '/src/img/default_badge.png',
      text_badge: 'Featured',
      is_image: true,
      badgeCss:{
        position: 'relative',
        top:0,
        left:0
      }

    }),
    computed: {
      url() {
        if (!this.image) return;
        return URL.createObjectURL(this.image);
      },
      badgeContainerCss(){
        return {
          background: `url(${this.sharedData.plugin_path}/src/img/preview.jpg)`
        }
      }

    },
    created() {
      this.showBadgeOnPreview()
    },
    methods: {
      submit(){

        if(this.$refs.form.validate()){
          this.loading = true;
          const formData = new FormData();
          formData.append('action', 'new_badge');
          formData.append('image', this.image);
          formData.append('type', this.type);
          formData.append('name', this.name);
          formData.append('position', this.position);
          axios.post(this.sharedData.ajax_url, formData)
            .then( response => {
              if(response.data.success){
                this.$refs.form.reset()
                this.loading = false;
              }
            })
        }

      },
      showBadgeOnPreview(){
         $('.badge_preview .v-image__image').on('click',  () => {
           alert('asdsad');
           $('.badge_preview .v-image__image').append('<span class="badge_show"></span>')
         });
      },
      positionChanged(type){
        switch (type) {
          case 1:
            this.badgeCss = {
              position: 'absolute',
              top:0,
              left:0
            }
            break;

          case 2:
            this.badgeCss = {
              position: 'absolute',
              top:0,
              right:0
            }
            break;

          case 3:
            this.badgeCss = {
              position: 'absolute',
              right:0,
              bottom:0
            }
            break;

          case 4:
            this.badgeCss = {
              position: 'absolute',
              left:0,
              bottom:0
            }
            break;
        }
      },
      fileChanged(e){
        this.image_badge = URL.createObjectURL(e);
      },
      badgeTypeChanged(badgeType){
        if(badgeType == 1){
          this.is_image = true;
        }else{
          this.is_image = false;
        }
      }
    }
  }
</script>

<style>
  input{
    border: 0 !important;
  }
  .img-file-loader .v-image__image{
    width: 100px;
    height: 100px;
  }

  .v-select__selections input{
    display: none !important;
  }

  .img-file-loader .v-responsive__sizer {
    padding-bottom: 20% !important;
  }
  .img-badge{
    max-width: 64px;
  }

  .badge_container{
    background-size: cover !important;
    background-repeat: no-repeat !important;
    height: 350px !important;
    margin: 0 auto !important;
    background-position: center !important;
    border: 1px solid darkgrey !important;
    width: 80% !important;
    position: relative;
  }

</style>

