<template>
	<div class="col-md-12"
		v-if="$page.props.auth.user.type == 'customer' && !packag.service_code && packag.return_label == 0 && packag.payment_status == 'Pending'">
		<div class="card mt-2">
			<div class="card-header">
				<h3 class="text-uppercase">Shipping Address</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<form @submit.prevent="submitPkgAddress">
								<div class="form-group col-md-6">
									<select name="address_book_id" class="form-select text-uppercase"
										v-model="address_form.address_book_id" required @change="submitPkgAddress">
										<option :value="0">--Select Address--</option>
										<template v-for="address in shipping_address" :key="address.id">
											<option :value="address.id">
												{{ address.fullname }}, {{ address.address }}
											</option>
										</template>
									</select>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row" v-if="!create_shipping_address">
							<div class="col-md-12">
								<button type="button" class="btn btn-success float-right"
									v-on:click="create_shipping_address = true">
									<i class="fa fa-plus mr-1"></i>Address
								</button>
							</div>
						</div>
						<div class="row">
							<div v-if="create_shipping_address">
								<div class="card p-4 shadow">
									<form @submit.prevent="submitShippingAddress">
										<div class="order-form">
											<breeze-validation-errors class="mb-4" />
											<flash-messages class="mb-4" />

											<div class="row">
												<div class="form-group col-md-7">
													<breeze-label for="name" value="Full Name *" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.fullname" required />
												</div>

												<div class="form-group col-md-5">
													<breeze-label for="Business/Residential"
														value="Business/Residential *" />
													<select class="form-control" v-model="create_shipping_address_form.is_residential
														" required>
														<option value="" selected>Select</option>
														<option value="0">Business</option>
														<option value="1">Residential</option>
													</select>
												</div>

												<div class="form-group col-md-12">
													<breeze-label for="address" value="Address *" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.address" required />
												</div>

												<div class="form-group col-md-12">
													<breeze-label for="address2" value="Address 2" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.address_2" />
												</div>

												<div class="form-group col-md-12">
													<breeze-label for="address3" value="Address 3" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.address_3" />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="country" value="Country *" />
													<select required v-model="create_shipping_address_form.country_id"
														class="form-control" v-on:change="country()">
														<option value="" selected>Select</option>
														<template v-for="country in countries" :key="country">
															<option :value="country.id">
																{{ country.name }}
															</option>
														</template>
													</select>
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="state" :value="create_shipping_address_form.state_required
														? 'State *'
														: 'State (optional)'
														" />
													<input type="text" class="form-control" placeholder="e.g NY,CA,DE"
														v-model="create_shipping_address_form.state" />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="city" value="City *" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.city" required />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="zip_code" value="Zip Code *" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.zip_code" required />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="tax_no" value="Tax ID" />
													<input name="tax_no" id="tax_no" type="text" class="form-control"
														placeholder="Tax/ VAT ID" v-model="create_shipping_address_form.tax_no" />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="phone" value="Phone *" />
													<input type="text" class="form-control"
														v-model="create_shipping_address_form.phone" required />
												</div>

												<div class="form-group col-md-6">
													<breeze-label for="email" value="Email *" />
													<input type="email" class="form-control"
														v-model="create_shipping_address_form.email" required />
												</div>
											</div>

											<div class="order-button">
												<input type="submit" value="Save & Submit" class="btn btn-success" />
												<button type="button" class="btn btn-danger float-right"
													v-on:click="cancelShippingAddress">
													Cancel
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import { Inertia } from "@inertiajs/inertia";

export default {
	name: "Shipping Address Component",
	props: {
		packag: Object,
		shipping_address: Object,
		countries: Object,
	},
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		BreezeValidationErrors,
	},
	data() {
		return {
			create_shipping_address: false,
			address_form: this.$inertia.form({
				package_id: this.packag.id,
				address_book_id: this.packag.address_book_id,
			}),
			create_shipping_address_form: this.$inertia.form({
				fullname: "",
				is_residential: "",
				address: "",
				address_2: "",
				address_3: "",
				city: "",
				state: "",
				zip_code: "",
				tax_no: "",
				country_id: "",
				phone: "",
				email: "",
				state_required: false,
				packages_address: true
			}),
		};
	},
	methods: {
		submitPkgAddress() {
			this.address_form.post(this.route("packages.address.update"));
		},
		submitShippingAddress() {
			Inertia.post(
				route("address.store"),
				this.create_shipping_address_form,
				{
					onSuccess: (response) => {
						console.log(response);
						this.create_shipping_address = false;
						this.create_shipping_address_form.reset();
					},
					onError: () => {
						this.create_shipping_address = true;
					},
				}
			);
		},
		cancelShippingAddress() {
			this.create_shipping_address_form.reset();
			this.create_shipping_address = false;
		},
		country() {
			if (
				this.create_shipping_address_form.country_id == 226 ||
				this.create_shipping_address_form.country_id == 138 ||
				this.create_shipping_address_form.country_id == 38
			) {
				this.create_shipping_address_form.state_required = true;
			} else {
				this.create_shipping_address_form.state_required = false;
			}
		},
		mounted() {
			this.country();
		},
	},
};
</script>
