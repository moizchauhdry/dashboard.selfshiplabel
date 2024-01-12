<template>
  <header-component></header-component>
<div role="main" class="main">
      <section class="section border-0 m-0 pb-3">
        <div class="container container-lg-custom">
          <div class="row pb-1">
          <div class="col-md-9">
            <slot />
          </div>
          <div class="col-md-3">
              <side-bar></side-bar>
          </div>
          </div>
        </div>
      </section>
    </div>    
    <footer-component></footer-component>
</template>
<script>
    import SideBar from '@/Components/SideBar'
    import HeaderComponent from '@/Components/Header.vue'
    import FooterComponent from '@/Components/Footer.vue'

    export default {
        components: {
            'side-bar' : SideBar,
            'header-component': HeaderComponent,
            'footer-component': FooterComponent,    
        },
         props: {
        },
        data() {

        },
        computed : {
          },
        methods: {

        }
    }
</script>

