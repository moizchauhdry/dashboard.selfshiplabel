<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-2 mb-5">
				<div class="card-header">
					<h3>Mailout Details</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<table class="table">
								<thead>
									<tr>
										<th colspan="2">Shipping Address</th>
									</tr>
								</thead>
								<template v-if="packag.address_book_id == 0">
									<tbody>
										<tr>
											<td colspan="2">To be Filled</td>
										</tr>
									</tbody>
								</template>
								<template v-else>
									<tbody>
										<tr>
											<td colspan="2">
												{{ packag?.address?.fullname }}
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<span>{{ packag?.address?.address }} <br /> </span>
												<span v-if="packag.address.address_2">
													{{ packag?.address?.address_2 }}
													<br />
												</span>
												<span v-if="packag.address.address_3">
													{{ packag?.address?.address_3 }} <br />
												</span>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												{{ packag?.address?.city }},
												{{ packag?.address?.state }},
												{{ packag?.address?.zip_code }},
												{{ packag?.address?.country_name }}
											</td>
										</tr>
										<tr>
											<td colspan="2">
												Contact: {{ packag?.address?.phone }} <br>
												Email: {{ packag?.address?.email }} <br>
												<template v-if="packag?.address?.tax_no">
													VAT ID: {{ packag?.address?.tax_no }} <br>
												</template>
												Type: {{ packag?.address?.is_residential == 1 ? 'Residential' : 'Commercial'
												}} <br />
											</td>
										</tr>
									</tbody>
								</template>
							</table>
						</div>
						<div class="col-md-4">
							<table class="table">
								<thead>
									<tr>
										<th colspan="2">Shipping Service Details</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Carrier</td>
										<td>
											<template v-if="packag.carrier_code">
												{{ packag.carrier_code }}
											</template>
											<template v-else>Not Set</template>
										</td>
									</tr>
									<tr>
										<td>Service</td>
										<td>
											<template v-if="packag.service_code">
												{{ packag.service_label }}
											</template>
											<template v-else>Not Set</template>
										</td>
									</tr>
									<tr>
										<td>Package Type</td>
										<td>
											<template v-if="packag.package_type_code">
												{{ packag.package_type_code.split("_").join(" ") }}
											</template>
											<template v-else>Not Set</template>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-4">
							<table class="table">
								<thead>
									<tr>
										<th colspan="4">Services/Charges</th>
									</tr>
								</thead>
								<tbody>
									<template v-if="packag.pkg_type == 'consolidation' &&
										packag.pkg_dim_status == 'done'
										">
										<tr>
											<td>Consolidation</td>
											<td>
												{{
													"$1.5 x " + packag.child_packages.length + " + $" + 5
												}}
											</td>
											<td>${{ formatNumber(packag.consolidation_fee) }}</td>
										</tr>
									</template>
									<template v-for="package_service_request in package_service_requests"
										:key="package_service_request.id">
										<tr>
											<td colspan="2">
												<span>
													{{ package_service_request.name }}
													<br />
													<small v-if="package_service_request.child_package_id">
														<inertia-link :href="route(
															'packages.show',
															package_service_request.child_package_id
														)
															">
															PKG #{{
																package_service_request.child_package_id
															}}
														</inertia-link>
													</small>
												</span>
											</td>
											<td>
												${{ formatNumber(package_service_request.amount) }}
												<button v-if="$page.props.auth.user.type == 'admin' &&
													packag.payment_status != 'Paid'
													" class="btn btn-link" @click="editServiceCharges(package_service_request)">
													<i class="fa fa-edit"></i>
												</button>
											</td>
										</tr>
									</template>
									<tr>
										<td>Mail Out Fee</td>
										<td></td>
										<td>${{ formatNumber(mailout_fee) }}</td>
										<td></td>
									</tr>
									<tr v-if="eei_charges > 0">
										<td>EEI Charges</td>
										<td></td>
										<td>${{ formatNumber(eei_charges) }}</td>
										<td></td>
									</tr>
									<tr>
										<td>Label Charges</td>
										<td></td>
										<td>${{ formatNumber(label_charges) }}</td>
										<td></td>
									</tr>
									<tr v-if="packag.storage_fee > 0">
										<td>Storage Fee</td>
										<td></td>
										<td>${{ formatNumber(packag.storage_fee) }}</td>
										<td></td>
									</tr>
									<tr>
										<td>Discount</td>
										<td></td>
										<td colspan="2">

											${{ formatNumber(packag.discount) }}

											<template
												v-if="packag.payment_status != 'Paid' && $page.props.auth.user.type == 'customer'">
												<button class="btn btn-link" @click="removeCoupon()"
													v-if="packag.coupon">Remove</button>
												<button v-else type="button" class="btn btn-link float-right"
													data-toggle="modal" data-target="#coupon_modal"
													@click="couponModal()"><b>Apply
														Coupon</b></button>
											</template>
										</td>
									</tr>
									<tr>
										<td>Shipping Charges</td>
										<td></td>
										<td>
											${{ packag.shipping_charges ?? 0 }}
											<button class="btn btn-link"
												v-if="$page.props.auth.user.type == 'admin' && packag.payment_status != 'Paid'"
												@click="editCharges(packag.shipping_charges, 'shipping_charges')">
												<i class="fa fa-edit"></i>
											</button>
										</td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: center">
											<strong>Total</strong>
										</td>
										<td class="bg-dark text-white">
											${{ formatNumber(this.total) }}
										</td>
										<td></td>
									</tr>
									<tr>
										<!-- <template v-if="$page.props.auth.user.type == 'customer'">
											<td v-if="(packag.carrier_code != null || packag.return_label == 1) && packag.payment_status != 'Paid'"
												colspan="4">
												<button type="button" @click="checkout()"
													class="btn btn-primary">Checkout</button>
											</td>
											<td v-else colspan="4">
												<button v-if="packag.payment_status != 'Paid'" type="button"
													class="btn btn-primary disabled">
													Checkout
												</button>
											</td>
										</template> -->

										<template
											v-if="$page.props.auth.user.type == 'customer' && packag.payment_status != 'Paid'">
											<td>
												<button type="button" @click="checkout()"
													class="btn btn-primary">Checkout</button>

												<span v-for="cm in checkout_message" :key="cm.id" class="badge badge-danger">
													{{ cm }}
												</span>
											</td>
										</template>

									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div v-if="$page.props.auth.user.type == 'admin' && packag.payment_status != 'Paid'
		" class="modal fade" id="charges_update_modal" tabindex="-1" aria-labelledby="charges_update_label"
		aria-hidden="true">
		<div class="modal-dialog border">
			<form @submit.prevent="updateServiceCharges">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="charges_update_label">
							Charges Update
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"
							@click="closeServiceChargesModal">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row col-md-12">
							<breeze-validation-errors class="mb-4" />
							<div class="form-group">
								<input type="text" class="form-control" v-model="charges_form.amount" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal"
							@click="closeServiceChargesModal">
							Close
						</button>
						<button type="submit" class="btn btn-success">Update</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div v-if="$page.props.auth.user.type == 'customer' && packag.payment_status != 'Paid'" class="modal fade"
		id="coupon_modal" tabindex="-1" aria-labelledby="charges_update_label" aria-hidden="true">
		<div class="modal-dialog border">
			<form @submit.prevent="applyCoupon">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="charges_update_label">
							Apply Coupon
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"
							@click="closeCouponModal">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row col-md-12">
							<breeze-validation-errors class="mb-4" />
							<div class="form-group">
								<input type="text" class="form-control" v-model="coupon_form.code" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeCouponModal">
							Close
						</button>
						<button type="submit" class="btn btn-success">Apply</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
import $ from "jquery";

export default {
	name: "Mailout Component",
	props: {
		packag: Object,
		package_service_requests: Object,
		total: Object,
		mailout_fee: Number,
		eei_charges: Number,
		label_charges: Number,
	},
	data() {
		return {
			edit_mode: false,
			form_checkout: this.$inertia.form({
				package_id: this.packag.id,
				payment_module: "package",
			}),
			tracking_edit: false,
			charges_form: this.$inertia.form({
				package_id: "",
				id: "",
				amount: "",
				type: "",
			}),
			coupon_form: this.$inertia.form({
				code: ""
			}),
			checkout_message: []
		};
	},
	methods: {
		formatNumber(num) {
			return parseFloat(num).toFixed(2);
		},
		checkout() {

			this.checkout_message = [];

			if (!this.packag.address_book_id && this.packag.return_label == 0) {
				this.checkout_message.push('The address is required');
			}

			if (this.packag.address_type == 'international' && this.packag.custom_form_status == 0) {
				this.checkout_message.push('The custom form required');
			}

			if (this.packag.carrier_code == null && this.packag.return_label == 0) {
				this.checkout_message.push('The shipping rate required');
			}

			if (this.checkout_message.length === 0) {
				this.$inertia.post(route("payment.index"), this.form_checkout);
			}
		},
		editCharges(amount, type) {
			this.edit_mode = true;
			this.charges_form.package_id = this.packag.id;
			this.charges_form.amount = amount;
			this.charges_form.type = type;
			var modal = document.getElementById("charges_update_modal");
			modal.classList.add("show");
			$("#charges_update_modal").show();
		},
		editServiceCharges(package_service_request) {
			this.edit_mode = true;
			this.charges_form.id = package_service_request.id;
			this.charges_form.amount = package_service_request.amount;
			this.charges_form.type = "service_request";
			var modal = document.getElementById("charges_update_modal");
			modal.classList.add("show");
			$("#charges_update_modal").show();
		},
		updateServiceCharges() {
			this.charges_form.post(this.route("packages.charges.update"));
			this.closeServiceChargesModal();
		},
		closeServiceChargesModal() {
			document.getElementById("charges_update_modal").style.display = "none";
		},
		closeCouponModal() {
			document.getElementById("coupon_modal").style.display = "none";
		},
		applyCoupon() {
			this.coupon_form.package_id = this.packag.id;
			this.$inertia.post(route("packages.coupon", this.coupon_form));
			this.closeCouponModal();
		},
		couponModal() {
			var modal = document.getElementById("coupon_modal");
			modal.classList.add("show");
			$("#coupon_modal").show();
		},
		removeCoupon() {
			this.coupon_form.package_id = this.packag.id;
			this.$inertia.post(route("packages.coupon.remove", this.coupon_form));
		},
	},
};
</script>
