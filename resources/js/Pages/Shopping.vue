<template>
    <header-component></header-component>
    <div role="main" class="main" style="margin-bottom:20px;">				
		
		<section class="section top-section-padd section-with-shape-divider page-header page-header-modern page-header-lg border-0 my-0" style="background-size: cover; background-position: center;">
			<div class="container pb-5 my-3">
				<div class="row mb-4">
					<div class="col-md-12 align-self-center p-static order-2 text-center">
						<h1 class="font-weight-bold text-color-dark text-10">Shopping</h1>
						<p class="text-4 font-weight-medium mt-3 mb-0">Feel free to shop from USA & UK and get your packages at doorstep. </p>
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
						<div class="bg-dark h-100">
							<div class="text-center text-md-start p-5 h-100" style="background-color:#d4d6d9" >
								<form v-if="profile_complete"  @submit.prevent="submit" class="contact-form form-style-4 form-placeholders-light form-errors-light mb-5 mb-lg-0">
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<div class="input-title text-white mb-2 text-6 font-weight-medium text-center">Where is your merchandise?</div>
												<select v-model="form.ship_from" class="form-select text-4" aria-label="Default select example">
													<option v-for="city in cities_from" :value="city.zip" >{{ city.name}} </option>
												</select>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group md-mrgn">
												<div class="input-title text-white mb-2 text-6 font-weight-medium text-center">Where are you shipping to?</div>
												<select  v-model="form.ship_to" class="form-select text-4" aria-label="Default select example">
													<option v-for="city in cities_to" :value="city.zip" >{{ city.name}}</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-heading text-center mt-5">
											<h6 class="text-6 text-primary font-weight-bold">Tell us about the package size and weight...</h6>
										</div>
									</div>

									<div v-for="(pkg,index) in form.packages" class="row" style="margin-top:10px;">										

										<div class="col-md-3">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Declared Value</label>
												<input v-model="pkg.declared_value"  type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Weight</label>
												<input v-model="pkg.weight"  type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Unit</label>
												<select v-model="pkg.unit" class="form-select text-4 mt-2" aria-label="Default select example">
													<option value="lb_in" selected>Lb / Inch</option>
													<option value="kg_cm">Kg / Cm</option>
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Length</label>
												<input  v-model="pkg.length"  type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Width</label>
												<input v-model="pkg.width" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">Height</label>
												<input v-model="pkg.height" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div v-if="index==0" class="col-md-1">
											<div class="form-group col text-center mt-4">
												<a v-on:click="addPackage" class="btn btn-primary" style="margin-top:75px;">
													<span>Add</span>
												</a>
											</div>
										</div>

										<div v-else class="col-md-1">
											<div class="form-group col text-center mt-4">
												<a v-on:click="removePackage(index)" class="btn btn-primary" style="margin-top:75px;">
													<span>Remove</span>
												</a>
											</div>
										</div>
									</div>
									
									<div class="dim-warning col text-center">
										<a class="" href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
										Why dimensions matter? Learn more about "Volumetric" weight
										</a>
									</div>

									<div class="row" v-show="serverError!=''">
										<div class="form-group col text-center mt-4">
											<p style="color:red;font-weight:bold;"> {{ serverError }}</p>
										</div>
									</div>

									<div class="row" v-show="!showOrderButton">
										<div class="form-group col text-center mt-4">
											<button type="submit" class="btn btn-primary custom-btn-style-1 font-weight-normal btn-px-4 btn-py-2 text-5-5" data-loading-text="Loading..." data-cursor-effect-hover="plus" data-cursor-effect-hover-color="light">
												<span>Get Rates </span>
											</button>
										</div>
									</div>

									<div class="row" v-show="showOrderButton">
										<div class="form-heading text-center mt-5">
											<h6 class="text-6 text-primary font-weight-bold">Product Details</h6>
										</div>
									</div>

									<div v-for="(item,index) in form.items" v-show="showOrderButton" class="row" style="margin-top:10px;">										

										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Product Id</label>
												<input v-model="item.productId" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Sku</label>
												<input v-model="item.sku"  type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Title</label>
												<input v-model="item.title"  type="text" class="form-control text-dark text-4 mt-2" name="title" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Price</label>
												<input  v-model="item.price"  type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="weight">Qty</label>
												<input v-model="item.quantity" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">Weight</label>
												<input v-model="item.weight" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">Image</label>
												<input v-model="item.imgUrl" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">HTS Number</label>
												<input v-model="item.htsNumber" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>


										<div class="col-md-3">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">Origin Country</label>
												<input v-model="item.countryOfOrigin" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="form-group sizes-input mt-5">
												<label class="text-6 text-center text-white font-weight-medium d-block" for="height">Line No</label>
												<input v-model="item.lineId" type="text" class="form-control text-dark text-4 mt-2" name="name" placeholder="" required="">
											</div>
										</div>
										

										<div v-if="index==0" class="col-md-1">
											<div class="form-group col text-center mt-4">
												<a v-on:click="addItem" class="btn btn-primary" style="margin-top:75px;">
													<span>Add</span>
												</a>
											</div>
										</div>

										<div v-else class="col-md-1">
											<div class="form-group col text-center mt-4">
												<a v-on:click="removeItem(index)" class="btn btn-primary" style="margin-top:75px;">
													<span>Remove</span>
												</a>
											</div>
										</div>
									</div>
									
									<div class="row" v-show="showOrderButton">
										<div class="col-md-6">
											<div class="form-group">
												<div class="input-title text-white mb-2 text-6 font-weight-medium text-center">Select Prefered service  for shipping</div>
												<select v-model="form.selected_service" class="form-select text-4" aria-label="Default select example">
													<option v-for="service in services" :value="service.serviceCode" > {{ service.label}}{{ service.totalAmount }} {{ service.currency }} </option>
												</select>
											</div>
										</div>
									</div>

									<div class="row" v-show="showOrderButton">
										<div class="form-group col text-center mt-4">
											<a v-on:click="createOrder" class="btn btn-primary custom-btn-style-1 font-weight-normal btn-px-4 btn-py-2 text-5-5" data-loading-text="Loading..." data-cursor-effect-hover="plus" data-cursor-effect-hover-color="light">
												<span>Place Order </span>
											</a>
										</div>
									</div>

								</form>
								<p v-else style="color:red;text-align:center;"><b>Please complete your profile first to continue shopping.</b>
								<inertia-link class="nav-link" :href="route('profile')">
                                    <i class="fas fa-external-link-alt"></i><span>Update Profile</span>
                                </inertia-link>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
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
		showOrderButton:false,
		services:[],
		serverError:'',
		form: this.$inertia.form({
			ship_from: '',
			ship_to: '',
			declared_value: '',
			weight: '',
			unit: 'lb_in',
			length : '',
			width : '',
			height : '',
			selected_service : '',
			packages:[
				{
					declared_value: '',
					weight: '',
					unit: 'lb_in',
					length : '',
					width : '',
					height : '',
				}
			],
			items:[
				{
					productId: "856673",
					sku: "ade3-fe21-bb9a",
					title: "Socks",
					price: "3.99",
					quantity: 2,
					weight: "0.5",
					imgUrl: "http://sockstore.egg/img/856673",
					htsNumber: "555555",
					countryOfOrigin: "US",
					lineId: "1"
				}
			],
		})
    };
  },
  props: {
	auth: Object,
	errors: Object,
	cities_from:Object,
	cities_to:Object,
	profile_complete : Boolean
  },
  methods: {
	addPackage(){
      this.form.packages.push({
			declared_value: '',
			weight: '',
			unit: 'lb_in',
			length : '',
			width : '',
			height : '',
		})
	},
	removePackage(index){
 		this.form.packages.splice(index, 1);
	},
	addItem(){
      this.form.items.push({
			productId: "",
			sku: "",
			title: "",
			price: "",
			quantity: 1,
			weight: "",
			imgUrl: "",
			htsNumber: "",
			countryOfOrigin: "",
			lineId: ""
		})
	},
	removeItem(index){
 		this.form.items.splice(index, 1);
	},

	submit() {
	    /*
			{
				ship_from : this.form.ship_from,
				ship_to : this.form.ship_to,
				weight : this.form.weight,
				unit : this.form.unit,	
				length : this.form.length,
				declared_value: this.form.declared_value,
				width : this.form.width,
				height : this.form.height,	
				selected_service : this.form.selected_service		
			} 
		*/	
		this.serverError = '';	
		axios.get("/orders/quote",{	
			params : this.form
		})
        .then(({ data }) => {
				if(data.status){
					this.services = data.services;
					this.showOrderButton=true;	
					this.form.selected_service = this.services[0].serviceCode;			                				
				}else{
					this.serverError = data.message;
				}
            }
        );

	},
	createOrder(){
	
		axios.post("/orders",this.form)
        .then(({ data }) => {
				if(data.status){
					this.$inertia.visit('/dashboard');
				}else{
					//alert('There was some issue');
				}
            }
        );

	}
  },
	created: function () {
		console.log("herer");
		console.log(this.form.packages);
  }
};
</script>
