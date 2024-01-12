<template>
    <editor
        api-key="no-api-key"
        :init="{
          height: 500,
          menubar: false,
          plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
          ],
          toolbar:
            'undo redo | formatselect | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | removeformat | help'
        }"
      />
    <input id="content" type="hidden" :name="inputname" v-model="content" />  
</template>

<script>
import HeaderComponent from '../Components/Header.vue'

export default {
  data() {
    return {
      recentBlogs:[]
    };
  },
  methods: {
    getRecentBlogs(){
      axios
        .get("/api/recentBlogs")
        .then(({ data }) => {
            this.recentBlogs = data.data;                
            }
        );
    }
  },
  mounted() {},
  created() {
    //this.$Progress.start();
    this.getRecentBlogs();
    //this.$Progress.finish();
  }
};
</script>
