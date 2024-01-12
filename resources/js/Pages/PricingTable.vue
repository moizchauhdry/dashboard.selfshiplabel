<template>
    <header-component></header-component>
    <div role="main" class="main">
    
		<loading v-model:active="isLoading"
				:can-cancel="true"
				:on-cancel="onCancel"
				:is-full-page="fullPage"/>


				<section class="section top-section-padd section-with-shape-divider page-header page-header-modern page-header-lg border-0 my-0" style="background-size: cover; background-position: center;">
					<div class="container pb-5 my-3">
						<div class="row mb-4">
							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="font-weight-bold text-color-dark text-10">Services & Pricing</h1>
								<p class="text-4 font-weight-medium mt-3 mb-0">Everyone gets treated as Premium Customer. DON’T PAY EXTRA when you can get same PREMIUM service for FREE!</p>
                                <p class="text-4 font-weight-medium mt-3 mb-0"><strong>NO HIDDEN FEES</strong> in shipping price. You only pay for the services you order. </p>
							</div>
						</div>
					</div>
					<div class="shape-divider shape-divider-bottom shape-divider-reverse-x divider-index" style="height: 123px;">
						<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1920 123" preserveAspectRatio="xMinYMin">
							<polygon fill="#F3F3F3" points="0,90 221,60 563,88 931,35 1408,93 1920,41 1920,-1 0,-1 "/>
							<polygon fill="#FFFFFF" points="0,75 219,44 563,72 930,19 1408,77 1920,25 1920,-1 0,-1 "/>
						</svg>
					</div>
				</section>
				<section class="section price-section-padd section-height-3 bg-light border-0 pt-4 m-0" style="background-size: 100%; background-repeat: no-repeat;">
					<div class="container">
						<div class="row mx-3 mx-xl-0">
							<div class="col-md-12 px-0">
                                <table class="table table-striped table-bordered">

                                <thead>
                                    <tr>
                                        <td>Service</td>
                                        <td>Charges</td>
                                    </tr>    
                                </thead>
                                <tbody>
                                <tr v-for="item in services" :key="item.id">
                                  <td v-html="item.name"></td>
                                  <td v-html="item.description"></td>
                                </tr>
                                <!-- <tr>
                                        <td>Account Fee</td>
                                        <td>Free</td>
                                    </tr>

                                    <tr>
                                        <td>Tax free shopping </td>
                                        <td>Yes</td>
                                    </tr>

                                    <tr>
                                        <td>Mail out fee</td>
                                        <td>Free</td>
                                    </tr>

                                    <tr>
                                        <td>Storage fee</td>
                                        <td>Free for 75 days </td>
                                    </tr>

                                    <tr>
                                        <td>Additional storage</td>
                                        <td>$0.01 per lbs (Dimensional weight)</td>
                                    </tr>

                                    <tr>
                                        <td>Consolidation</td>
                                        <td>$5+$1.50 per each consolidation package  </td>
                                    </tr>

                                    <tr>
                                        <td>Trash</td>
                                        <td>Free</td>
                                    </tr>

                                    <tr>
                                        <td>Incoming Mail and product picture</td>
                                        <td>Free, no serial number or model number </td>
                                    </tr>

                                    <tr>
                                        <td>Additional photos</td>
                                        <td>additional picture such as serial number, model number etc $2 each or $5 for 3 </td>
                                    </tr>

                                    <tr>
                                        <td>Purchase from Costco and Sam club</td>
                                        <td>5% of your total bill </td>
                                    </tr>

                                    <tr>
                                        <td>Purchase from outlets in USA</td>
                                        <td>$20 for a trip to each store for California, tory Burch is $25, 5% of the total invoice (Tax included)</td>
                                    </tr>

                                    <tr>
                                        <td>Trip to the store includes 1 hour of shopping for you,<br /> after an hour another charge is added</td>
                                        <td>$25 for Tax free store trip  </td>
                                    </tr>

                                    <tr>
                                        <td>Special request</td>
                                        <td>$2 per request <br />Ex: <br /> bubble wrap- $2 <br /> Shrink wrap - $2 <br />Fragile sticker- $2</td>
                                    </tr>

                                    <tr>
                                        <td>Online Shopping assistant</td>
                                        <td>$5 or 5% whichever is higher for online purchases</td>
                                    </tr>

                                    <tr>
                                        <td>Overweight handling</td>
                                        <td>Surcharge above 50Lbs</td>
                                    </tr>

                                    <tr>
                                        <td>Can purchase Gift Cards</td>
                                        <td>6% of your total bill if electronic available  and  $20 per visit if physical store visit is required</td>
                                    </tr>-->
                                </tbody>

                                </table>

							</div>
						</div>
					</div>
				</section>


	
    </div>
    <PricingContentComponent />
    <FooterComponent />


</template>

<style scoped>
    thead{ background-color: #325FAB; color:#fff; padding:25px;}
    thead tr td{ font-size: 18px;}
</style>

<script>
import HeaderComponent from '../Components/Header.vue'
import HomeTopSection from '../Components/HomeTopSection.vue'
import FooterComponent from '../Components/Footer.vue'
import PricingContentComponent from '../Components/PricingContent.vue'

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
components: {
    'header-component': HeaderComponent,
    'home-top-section' : HomeTopSection,
    FooterComponent,
    PricingContentComponent,
	Loading
  },    
  data() {
    return {

    };
  },
  props: {
            auth: Object,
            canLogin: Boolean,
            canRegister: Boolean,
            errors: Object,
            services: Object,
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
