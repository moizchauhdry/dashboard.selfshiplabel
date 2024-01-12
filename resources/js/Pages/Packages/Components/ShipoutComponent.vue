<template>
	<div
		class="row"
		v-show="tracking_edit"
		v-if="
			packag.payment_status == 'Paid' &&
			($page.props.auth.user.type == 'admin' ||
				$page.props.auth.user.type == 'manager')
		"
	>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h3>Ship out Package</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<form @submit.prevent="submitShipOutForm">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<breeze-label
												for="package_weight"
												value="Tracking # Out "
											/>
											<input
												v-model="form_ship.tracking_number_out"
												name="tracking_number_out"
												id="tracking_number_out"
												type="text"
												class="form-control"
												placeholder="Tracking # Out"
												required
											/>
										</div>
									</div>
									<div class="col-md-6" style="margin-top: 20px">
										<input
											type="submit"
											value="Ship Out"
											class="btn btn-success float-left"
										/>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "Shipout Component",
		props: {
			packag: Object,
		},
		data() {
			return {
				//
			};
		},
		methods: {
			//
		},
	};
</script>
