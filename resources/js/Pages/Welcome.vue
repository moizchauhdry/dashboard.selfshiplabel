<template>
    <header-component ></header-component>
    <div role="main" class="main">
    <home-top-section :promotionalMessage="promotionalMessage"  />
    </div>
    <FooterComponent />

</template>

<style scoped>

</style>

<script>
import HeaderComponent from '../Components/Header.vue'
import HomeTopSection from '../Components/HomeTopSection.vue'
import FooterComponent from '../Components/Footer.vue'


export default {
components: {
    'header-component': HeaderComponent,
    'home-top-section' : HomeTopSection,
    FooterComponent,
  },    
  data() {
    return {
      promotionalMessage:this.promotionalMessage,
    };
  },
  props: {
            auth: Object,
            canLogin: Boolean,
            canRegister: Boolean,
            promotionalMessage:String,
            errors: Object,
            laravelVersion: String,
            phpVersion: String,
        },
  methods: {
  },
  mounted() {},
  created() {
    //this.$Progress.start();
    //this.$Progress.finish();
  }
};
</script>
