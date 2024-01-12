<template>
            <div class="row" v-if="images.lenghth > 0">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3>Package Images</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div v-for="image in images" :key="'image'+image.order_id" class="col-md-2">
                    <div class="text-center">
                        <img style="width:100px;height:auto;" class="img-thumbnail" @click="viewImage($event)" :src="imgURL(image.img_url)"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</template>

<script>
    export default {
        name: "Package Image Component",
        props: {
            packag:Object,
            images:Object,
        },
        data() {
            return {
                // 
            }
        },
        methods:{
            // 
        }
    }
</script>