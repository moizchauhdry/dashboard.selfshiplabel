<template>
	<div class="container mt-5 mb-5">
		<div class="row">
			<div class="col-md-12">
				<h1
					class="text-5 text-lg-5 text-xl-5 line-height-3 text-transform-none font-weight-semibold"
				>
					Shipping Calculator
				</h1>

				<h6 class="text-4 mb-4 mt-2">
					To get shipping rates for any destination just use the shipping
					calculator calculator below. We offer discounted rates on all shipping
					methods. These rates are tens of percents better than official rates
					of carriers.
				</h6>
			</div>
			<div class="col-md-6">
				<div class="card p-4 shadow">
					<div class="d-flex justify-content-center mb-2">
						<span class="font-weight-semibold" style="font-size: 14px">
							Note: Please enter "00000" in the zip code field if your country
							does not have a postal code.
						</span>
					</div>
					<form @submit.prevent="submit">
						<div class="d-flex justify-content-center">
							<div class="form-group">
								<h6>Ship from</h6>
								<select
									required
									v-model="form.ship_from"
									class="form-control custom-select"
								>
									<option value="">Select</option>
									<option
										v-for="warehouse in warehouses"
										:value="warehouse.id"
										:key="warehouse.id"
									>
										{{ warehouse.name }}
									</option>
									<option value="other">Other USA</option>
								</select>
							</div>

							<div class="form-group" v-show="form.ship_from == 'other'">
								<h6 for="city">City</h6>
								<input
									v-model="form.ship_from_city"
									type="text"
									class="form-control"
								/>
							</div>

							<div class="form-group" v-show="form.ship_from == 'other'">
								<h6 for="zip">Zip</h6>
								<input
									v-model="form.ship_from_postal_code"
									type="text"
									class="form-control"
								/>
							</div>
						</div>

						<div class="d-flex justify-content-center">
							<div class="form-group">
								<h6>Ship To</h6>
								<select
									required
									v-model="form.ship_to_country_code"
									class="form-control custom-select"
								>
									<option value="">Select</option>
									<option
										v-for="country in countries"
										:value="country.iso"
										:key="country.id"
									>
										{{ country.name }}
									</option>
								</select>
							</div>

							<div class="form-group">
								<h6 for="">City</h6>
								<input
									v-model="form.ship_to_city"
									type="text"
									class="form-control"
								/>
							</div>

							<div class="form-group">
								<h6>Zip</h6>
								<input
									v-model="form.ship_to_postal_code"
									type="text"
									class="form-control"
								/>
							</div>
							<div class="form-group" v-if="form.ship_to_country_code != 'US'">
								<h6>Value</h6>
								<input
									v-model="form.customs_value"
									type="text"
									class="form-control"
								/>
							</div>
						</div>

						<div class="d-flex justify-content-center p-2">
							<input type="checkbox" v-model="form.units" />
							<label for="" class="ml-1 mr-3"
								>Metric <small>(kg/cm)</small></label
							>

							<input type="checkbox" v-model="form.address_type" />
							<label for="" class="ml-1">Residential</label>
						</div>

						<div
							class="d-flex justify-content-center"
							v-for="(item, index) in form.dimensions"
							:key="item.id"
						>
							<div class="form-group">
								<h6>
									Weight <small>{{ form.units ? "kg" : "lbs" }}</small>
								</h6>
								<input
									v-model="item.weight"
									type="number"
									class="form-control"
									name="name"
									:step="0.01"
									:min="0"
									required=""
								/>
							</div>

							<div class="form-group">
								<h6>
									Dimensions
									<small>(L x W x H) {{ form.units ? "cm" : "inch" }}</small>
								</h6>
								<div class="d-flex">
									<input
										v-model="item.length"
										type="number"
										class="form-control dimension"
										name="name"
										:step="0.01"
										:min="1"
										required=""
									/>

									<input
										v-model="item.width"
										type="number"
										class="form-control dimension"
										name="name"
										:step="0.01"
										:min="1"
										required=""
									/>

									<input
										v-model="item.height"
										type="number"
										class="form-control dimension"
										name="name"
										:step="0.01"
										:min="1"
										required=""
									/>
								</div>
							</div>

							<div class="form-group" v-show="index != 0">
								<a
									v-on:click="remove_dimension(index)"
									class="btn btn-link float-right font-bold"
								>
									<i class="fas fa-times"></i>
								</a>
							</div>
						</div>

						<div class="d-flex justify-content-center">
							<button
								class="btn btn-link mt-2 mb-2 font-bold"
								v-on:click="add_dimension()"
							>
								<i class="fa fa-plus mr-1"></i>ADD ANOTHER PACKAGE
							</button>
						</div>

						<div class="d-flex justify-content-center">
							<button type="submit" class="btn btn-primary" :disabled="loading">
								<span>
									<span v-if="!loading">Get Shipping Rates</span>
									<span v-else>Loading...</span>
								</span>
							</button>
						</div>
					</form>

					<div class="row mt-5">
						<div class="col">
							<h3
								class="text-5 text-center text-lg-5 text-xl-5 line-height-3 text-transform-none font-weight-semibold mb-4 mb-lg-5"
							>
								Via Trusted Shipping Partners
							</h3>
							<div class="row col-md-12">
								<div class="col text-center">
									<img
										class="d-inline-block img-fluid"
										src="/theme/img/demos/business-consulting-3/partner-02.png"
										alt=""
									/>
									<p class="text-0 font-weight-medium text-center mt-2">
										DHL Express
									</p>
								</div>
								<div class="col text-center">
									<img
										class="d-inline-block img-fluid"
										src="/theme/img/demos/business-consulting-3/partner-03.png"
										alt=""
									/>
									<p class="text-0 font-weight-medium text-center mt-2">
										FedEx International
									</p>
								</div>
								<div class="col text-center">
									<img
										class="d-inline-block img-fluid"
										src="/theme/img/demos/business-consulting-3/partner-04.png"
										alt=""
									/>
									<p class="text-0 font-weight-medium text-center mt-2">
										UPS International
									</p>
								</div>
							</div>
							<p class="font-weight-medium text-center mt-3">
								* Service offerings may change depending on the destination
								country. <br />
								Not all carriers offer services to all countries
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<template v-if="shipping_rates.length == 0">
					<div class="d-flex justify-content-center">
						<a href="http://shippingxps.com">
							<img
								class="mt-4"
								alt="shippingxps"
								width="237"
								height="55"
								src="/theme/img/logo.png"
							/>
						</a>
					</div>

					<div class="d-flex justify-content-center">
						<h1
							class="text-6 text-lg-8 text-xl-8 line-height-3 text-transform-none font-weight-semibold text-primary"
						>
							Worldwide Shipping from the USA
						</h1>
					</div>

					<div class="d-flex justify-content-center" v-if="loading">
						<h1
							class="text-3 text-lg-3 text-xl-3 line-height-3 text-transform-none font-weight-semibold mt-5"
						>
							Please wait, we are fetching best rates for you.
						</h1>
					</div>
				</template>

				<div class="card shadow" v-for="rate in shipping_rates" :key="rate.id">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<img
									src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-03.png"
									style="width: 100px"
									v-if="rate.code == 'fedex'"
								/>
								<img
									src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-02.png"
									style="width: 100px"
									v-if="rate.code == 'dhl'"
								/>
								<img
									src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-04.png"
									style="width: 100px"
									v-if="rate.code == 'ups'"
								/>
							</div>
							<div class="col-md-4">
								<h6 class="text-lg">
									{{ rate.name }}
								</h6>
							</div>
							<div class="col-md-4">
								<h6 class="text-lg float-right">
									<span>${{ rate.total }}</span>
								</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import LoaderComponent from "@/Components/LoaderComponent.vue";
	import MainLayout from "@/Layouts/Main";

	export default {
		components: {
			MainLayout,
			LoaderComponent,
		},
		props: {
			auth: Object,
			warehouses: Object,
			countries: Object,
		},
		data() {
			return {
				loading: false,
				shipping_rates: [],
				form: this.$inertia.form({
					ship_from: "",
					ship_from_postal_code: "",
					ship_from_country_code: "US",
					ship_from_city: "",
					ship_to_postal_code: "",
					ship_to_country_code: "",
					ship_to_city: "",
					customs_value: "",
					address_type: true,
					units: false,
					dimensions: [
						{
							length: "",
							width: "",
							weight: "",
							height: "",
						},
					],
				}),
			};
		},
		methods: {
			submit() {
				this.loading = true;
				this.shipping_rates = [];
				axios
					.post(route("shipping-rates.index"), this.form)
					.then((response) => {
						this.shipping_rates = response.data.data;
						this.loading = false;
					})
					.catch((error) => {
						this.loading = false;
					})
					.finally(() => {
						this.loading = false;
					});
			},
			add_dimension() {
				this.form.dimensions.push({
					weight: "",
					length: "",
					width: "",
					height: "",
				});
			},
			remove_dimension(index) {
				this.form.dimensions.splice(index, 1);
				this.submit();
			},
		},
		mounted() {
			//
		},
	};
</script>

<style>
	.form-group {
		margin-right: 1px !important;
	}
	.dimension {
		margin-right: 1px !important;
	}
</style>
