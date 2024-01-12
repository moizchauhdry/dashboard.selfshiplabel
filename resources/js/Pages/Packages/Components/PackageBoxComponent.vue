<template>
	<div class="row" v-if="packag.status != 'open' && packag.pkg_dim_status == 'done'">
		<div class="col-md-12">
			<div class="card mt-2">
				<div class="card-header">
					<h3 class="text-uppercase">{{ packag.pkg_type }} Package</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4" v-for="box in packag.boxes" :key="box.id">
							<div class="card shadow p-4">
								<div>
									Dimension:
									<b>{{ box.length }} x {{ box.width }} x {{ box.height }} {{ box.dim_unit }}</b>
								</div>
								<div>
									Weight: <b>{{ box.weight }} {{ box.weight_unit }}</b>
								</div>
								<div>
									Tracking In: <b>{{ packag.tracking_number_in ?? "N/A" }}</b>
								</div>
								<div>
									Tracking Out:
									<span v-if="box.tracking_out" class="font-bold text-primary underline">
										<a :href="'https://www.fedex.com/apps/fedextrack/?action=track&amp;trackingnumber=' + box.tracking_out"
											target="_blank" v-if="packag.carrier_code == 'fedex'">
											{{ box.tracking_out }}</a>
										<a :href="'http://www.dhl.com/en/express/tracking.html?brand=DHL&amp;AWB=' + box.tracking_out"
											target="_blank" v-if="packag.carrier_code == 'dhl'">
											{{ box.tracking_out }}</a>
										<a :href="'https://www.ups.com/track?loc=en_US&tracknum=' + box.tracking_out + '&requester=WT%2Ftrackdetails'"
											target="_blank" v-if="packag.carrier_code == 'ups'">
											{{ box.tracking_out }}</a>
										<a :href="'https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=' + box.tracking_out + '%2C&tABt=false'"
											target="_blank" v-if="packag.carrier_code == 'usps'">
											{{ box.tracking_out }}</a>
									</span>
									<span v-else>-</span>
								</div>

								<template v-if="packag.payment_status == 'Paid' &&
									$page.props.auth.user.type == 'admin'
									">
									<hr class="m-4" />
									<div class="form-group">
										<input class="form-control" type="text" placeholder="Tracking out"
											v-model="tracking_out_form.tracking_out" />
										<button type="button" class="btn btn-success btn-sm mt-1"
											@click="update_tracking_out(box.id)">
											Update
										</button>
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "Package Box Component",
	props: {
		packag: Object,
	},
	data() {
		return {
			tracking_out_form: this.$inertia.form({
				tracking_out: "",
				box_id: "",
			}),
		};
	},
	methods: {
		edit_tracking_out(box_id) {
			this.tracking_out_form.box_id = box_id;
			this.tracking_out_form.post(this.route("packages.ship-package"));
		},
		update_tracking_out(box_id) {
			this.tracking_out_form.box_id = box_id;
			this.tracking_out_form.post(this.route("packages.ship-package"));
		},
	},
};
</script>
