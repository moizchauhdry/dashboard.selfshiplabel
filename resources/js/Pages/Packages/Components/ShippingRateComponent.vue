<template>
	<div class="row" v-if="$page.props.auth.user.type == 'customer' &&
		(packag.status == 'filled' || packag.status == 'consolidated' || packag.address_type == 'domestic') &&
		packag.pkg_dim_status == 'done' &&
		packag.return_label == 0">

		<div class="col-md-12" v-if="packag.service_code == null">
			<div class="card mt-2">
				<div class="card-header">
					<h3 class="text-uppercase">Shipping Rates</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<template v-if="service_request_pending_count == 0">
								<button class="btn btn-success" v-on:click="getShippingRates()" :disabled="loading">
									<span v-if="!loading">Get Shipping Rates</span>
									<span v-else>Loading ... Please wait.</span>
								</button>
							</template>
							<template v-else>
								<span class="text-uppercase badge badge-danger p-1">
									<i class="fas fa-exclamation-circle mr-1"></i>There are some
									pending service request.
								</span>
							</template>
						</div>
						<div class="col-md-9">
							<table class="table table-sm table-striped">
								<thead>
									<tr>
										<th>Shipping Service</th>
										<th>Price</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<template v-for="rate in shipping_rates" :key="rate.id">
										<tr>
											<td>
												<img src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-01.png"
													style="width: 100px" v-if="rate.code == 'usps'" />
												<img src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-02.png"
													style="width: 100px" v-if="rate.code == 'dhl'" />
												<img src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-03.png"
													style="width: 100px" v-if="rate.code == 'fedex'" />
												<img src="https://app.shippingxps.com/theme/img/demos/business-consulting-3/partner-04.png"
													style="width: 100px" v-if="rate.code == 'ups'" />
												{{ rate.name }}
											</td>
											<td>${{ rate.total }}</td>
											<td>
												<a v-on:click="setShippingService(rate)" class="btn btn-info">Confirm</a>
											</td>
										</tr>
									</template>
									<tr>
										<td colspan="3">
											<p class="text-white bg-warning p-1">
												<b>Note:</b> Selected service cannot be changed. So make
												sure you choose correct service.
											</p>
											<p class="text-white bg-danger p-1" v-show="rates_error">
												<b>Note:</b> Make sure your address is valid to get
												shipping rates.
											</p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "Shipping Rate Component",
	props: {
		packag: Object,
		shipping_services: Object,
		service_request_pending_count: Object,
	},
	data() {
		return {
			loading: false,
			rates_error: false,
			shipping_rates: [],
			ship_service_form: this.$inertia.form({
				package_id: this.packag.id,
				code: "",
				type: "",
				name: "",
				pkg_type: "",
				markup: "",
				total: "",
			}),
		};
	},
	methods: {
		getShippingRates() {
			this.loading = true;
			this.rates_error = false;
			this.shipping_rates = [];

			let pieces = [];
			this.packag.boxes.forEach(function (value, index) {
				let piece = {
					length: value.length.toString(),
					width: value.width.toString(),
					weight: value.weight.toString(),
					height: value.height.toString(),
				};
				pieces.push(piece);
			});

			var address_type =
				this.packag.address.is_residential == 1 ? true : false;

			let quote_params = {
				ship_from: this.packag.warehouse_id,
				ship_from_country_code: "US",
				ship_from_city: this.packag.warehouse.city,
				ship_to_postal_code: this.packag.address.zip_code,
				ship_to_country_code: this.packag.address.country.iso,
				ship_to_city: this.packag.address.city,
				customs_value: this.packag.shipping_total,
				address_type: address_type,
				units: false,
				dimensions: pieces,
			};

			axios
				.post(route("shipping-rates.index"), quote_params)
				.then((response) => {
					this.shipping_rates = response.data.data;
					this.loading = false;
				})
				.catch((error) => {
					this.loading = false;
					this.rates_error = true;
				})
				.finally(() => {
					this.loading = false;
				});
		},
		setShippingService(rate) {
			var result = window.confirm(
				"After Confirming the shippment method you wont be able to change. Are you sure to confirm ?"
			);
			if (result) {
				this.ship_service_form.code = rate.code;
				this.ship_service_form.name = rate.name;
				this.ship_service_form.type = rate.type;
				this.ship_service_form.pkg_type = rate.pkg_type;
				this.ship_service_form.markup = rate.markup;
				this.ship_service_form.total = rate.total;

				this.ship_service_form.post(
					this.route("packages.set-shipping-service")
				);
			}
		},
	},
};
</script>
