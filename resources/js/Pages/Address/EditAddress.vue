<template>
	<MainLayout>
		<div class="card">
			<div class="card-header">Shipping Address - Edit</div>
			<div class="card-body">
				<form @submit.prevent="submit">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="form-group col-md-6">
									<breeze-label for="fullname" value="Full Name *" />
									<input name="fullname" id="fullname" type="text" class="form-control" placeholder="Full Name" v-model="form.fullname"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="Business/Residential" value="Business/Residential *"/>
									<select class="form-control" v-model="form.is_residential">
										<option value="0">Business</option>
										<option value="1">Residential</option>
									</select>
								</div>

								<div class="form-group col-md-12">
									<breeze-label for="address" value="Address *" />
									<input type="text" class="form-control" placeholder="Address" v-model="form.address"/>
								</div>

								<div class="form-group col-md-12">
									<breeze-label for="" value="Address 2" />
									<input type="text" class="form-control" placeholder="Address 2" v-model="form.address_2"/>
								</div>

								<div class="form-group col-md-12">
									<breeze-label for="address" value="Address 3" />
									<input type="text" class="form-control" placeholder="Address 3" v-model="form.address_3"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="country" value="Country *" />
									<select v-model="form.country_id" class="form-control" v-on:change="country">
										<option value="0">--Select Country--</option>
										<template v-for="country in countries" :key="country">
											<option :value="country.id">{{ country.name }}</option>
										</template>
									</select>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="state" :value="form.state_required ? 'State *' : 'State'"/>
									<input name="state" id="state" type="text" class="form-control" placeholder="e.g NY,CA,DE" v-model="form.state"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="city" value="City *" />
									<input name="city" id="city" type="text" class="form-control" placeholder="City" v-model="form.city"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="zip_code" value="Zip Code *" />
									<input name="zip_code" id="zip_code" type="text" class="form-control" placeholder="Zip Code" v-model="form.zip_code"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="tax_no" value="Tax ID" />
									<input name="tax_no" id="tax_no" type="text" class="form-control" placeholder="Tax/ VAT ID" v-model="form.tax_no"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="phone" value="Phone *" />
									<input name="phone" id="phone" type="text" class="form-control" placeholder="Phone" v-model="form.phone"/>
								</div>

								<div class="form-group col-md-6">
									<breeze-label for="email" value="Email *" />
									<input type="email" class="form-control" placeholder="Phone" v-model="form.email"/>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
									<input type="submit" value="Save & Update" class="btn btn-success"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<breeze-validation-errors/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
			BreezeValidationErrors,
		},
		data() {
			return {
				form: this.$inertia.form({
					id: this.address.id,
					fullname: this.address.fullname,
					country_id: this.address.country_id,
					state: this.address.state,
					city: this.address.city,
					zip_code: this.address.zip_code,
					phone: this.address.phone,
					email: this.address.email,
					address: this.address.address,
					address_2: this.address.address_2,
					address_3: this.address.address_3,
					is_residential: this.address.is_residential,
					tax_no: this.address.tax_no,
					state_required: false,
				}),
			};
		},
		props: {
			auth: Object,
			address: Object,
			countries: Object,
		},
		mounted() {
			this.country();
		},
		methods: {
			submit() {
				this.form.put(this.route("address.update"));
			},
			country() {
				if (
					this.form.country_id == 226 ||
					this.form.country_id == 138 ||
					this.form.country_id == 38
				) {
					this.form.state_required = true;
				} else {
					this.form.state_required = false;
				}
			},
		},
	};
</script>
