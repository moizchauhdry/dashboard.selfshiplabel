<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-2">
				<div class="card-header">
					<h3>Services</h3>
				</div>
				<div class="card-body">
					<div class="row">
						<template
							v-if="
								$page.props.auth.user.type == 'customer' &&
								(packag.status == 'open' || packag.status == 'filled')
							"
						>
							<div class="col-md-7">
								<table class="table table-striped">
									<thead>
										<tr>
											<th scope="col">Service</th>
											<th scope="col">Details</th>
											<th scope="col">Fees</th>
											<th scope="col"></th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="service in services" :key="service.id">
											<template v-if="packag.pkg_type == 'single'">
												<td>{{ service.title }}</td>
												<td>{{ service.description }}</td>
												<td>$ {{ service.price }}</td>
												<td>
													<button
														type="button"
														v-on:click="setActiveService(service)"
														class="btn btn-primary btn-sm"
														v-if="
															!this.service_requests_service_ids.includes(
																service.id
															)
														"
													>
														Request
													</button>
													<span class="badge badge-success" v-else>
														Request Sent
													</span>
												</td>
											</template>
											<template v-else>
												<template v-if="service.title == 'Trash'">
													<td>{{ service.title }}</td>
													<td>{{ service.description }}</td>
													<td>$ {{ service.price }}</td>
													<td>
														<button
															type="button"
															v-on:click="setActiveService(service)"
															class="btn btn-primary btn-sm"
															v-if="
																!this.service_requests_service_ids.includes(
																	service.id
																)
															"
														>
															Request
														</button>
														<span class="badge badge-success" v-else>
															Request Sent
														</span>
													</td>
												</template>
											</template>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-5">
								<template v-if="form.service_id != null">
									<form @submit.prevent="submit">
										<div class="form-group">
											<breeze-label for="notes" value="Message for Admin" />
											<textarea
												v-model="form.customer_message"
												name="notes"
												id="notes"
												class="form-control"
												placeholder="Message for Admin"
												rows="2"
												style="resize: none"
												required
											>
											</textarea>
										</div>
										<p style="color: red">
											Are you sure you want to use service? Add your message for
											admin and continue
										</p>
										<p style="color: red">
											Every service request is charged separately, so if you
											have already requested any service wait for system
											response.
										</p>
										<p>
											Service : <strong>{{ form.service.title }}</strong>
										</p>
										<p>
											Charges : <strong>${{ form.service.price }}</strong>
										</p>
										<div class="order-button">
											<a
												class="btn btn-danger"
												v-on:click="cancelServiceForm()"
											>
												Cancel</a
											>
											<input
												type="submit"
												value="Make Request"
												class="btn btn-success float-right"
											/>
										</div>
									</form>
								</template>
							</div>
						</template>
						<div
							v-bind:class="{
								'col-md-8':
									$page.props.auth.user.type == 'admin' ||
									$page.props.auth.user.type == 'manager',
								'col-md-12': $page.props.auth.user.type == 'customer',
							}"
							v-if="service_requests.length > 0"
						>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Service</th>
										<template v-if="$page.props.auth.user.type == 'customer'">
											<th scope="col">Your Message</th>
										</template>
										<template
											v-if="
												$page.props.auth.user.type == 'admin' ||
												$page.props.auth.user.type == 'manager'
											"
										>
											<th scope="col">Customer Message</th>
										</template>
										<th scope="col">Admin Response</th>
										<th scope="col">Status</th>
										<th scope="col">Charges</th>
										<template
											v-if="
												$page.props.auth.user.type == 'admin' ||
												$page.props.auth.user.type == 'manager'
											"
										>
											<th scope="col"></th>
										</template>
									</tr>
								</thead>
								<tbody>
									<tr v-for="request in service_requests" :key="request.id">
										<td>
											{{ request.service_title }}
										</td>
										<td>
											{{ request.customer_message }}
										</td>
										<td>
											{{ request.admin_message }}
										</td>
										<td>
											<span
												v-bind:class="getLabelClass(request.status)"
												style="padding: 5px"
											>
												{{ request.status }}
											</span>
										</td>
										<td>$ {{ request.price }}</td>
										<td>
											<template
												v-if="
													($page.props.auth.user.type == 'admin' ||
														$page.props.auth.user.type == 'manager') &&
													request.status == 'pending'
												"
											>
												<a
													v-on:click="setServiceResponse(request)"
													class="link-primary"
													>Respond</a
												>
											</template>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div v-if="service_requests == 0">
							<span>There are no services requests added.</span>
						</div>
						<div
							v-if="
								$page.props.auth.user.type == 'admin' ||
								$page.props.auth.user.type == 'manager'
							"
							class="col-md-4"
						>
							<template v-if="form_respond.request != null">
								<h3>Handle Service Request</h3>
								<form @submit.prevent="submitRespondForm">
									<p style="">
										Service :
										<strong>{{ form_respond.request.service_title }}</strong>
									</p>
									<div class="form-group">
										<p>Message for Customer</p>
										<textarea
											v-model="form_respond.admin_message"
											name="admin_message"
											id="admin_message"
											class="form-control"
											placeholder="Message for Customer"
											rows="4"
											style="resize: none"
											required
										>
										</textarea>
									</div>
									<p style="">
										Charges:
										<strong>${{ form_respond.request.price }}</strong>
									</p>
									<div class="form-group">
										<p>Edit Charges</p>
										<input type="text" v-model="form_respond.request.price" />
									</div>
									<div class="order-button">
										<input
											style="display: none"
											type="submit"
											value="Update Request"
											class="btn btn-danger"
										/>

										<a
											v-on:click="requestComplete()"
											class="btn btn-success float-left"
										>
											<span>Complete Request</span>
										</a>

										<a
											v-on:click="requestReject()"
											class="btn btn-danger float-right"
											style="margin-right: 5px"
										>
											<span>Reject Request</span>
										</a>
									</div>
								</form>
							</template>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "Service Component",
		props: {
			packag: Object,
			service_requests: Object,
			service_requests_service_ids: Object,
			services: Object,
		},
		data() {
			return {
				form: this.$inertia.form({
					package_id: this.packag.id,
					service_id: null,
					customer_message: "",
					service: null,
				}),
				form_respond: this.$inertia.form({
					admin_message: "",
					status: "pending",
					request: null,
				}),
			};
		},
		methods: {
			setActiveService(service) {
				this.form.service_id = service.id;
				this.form.service = service;
			},
			cancelServiceForm() {
				this.form.service_id = null;
			},
			submitRespondForm() {
				this.form_respond.post(this.route("packages.service-handle"));
				this.form_respond.reset();
				// Inertia.reload({only: ['service_requests']});
			},
			setServiceResponse(request) {
				this.form_respond.request_id = request.id;
				this.form_respond.request = request;
			},
			requestComplete() {
				this.form_respond.status = "served";
				this.submitRespondForm();
			},
			requestReject() {
				this.form_respond.status = "rejected";
				this.submitRespondForm();
			},
			submit() {
				this.form.post(this.route("packages.service-request"));
				this.form.reset();
				Inertia.reload({ only: ["service_requests"] });
			},
			getLabelClass(status) {
				switch (status) {
					case "pending":
						return "text-uppercase badge badge-warning p-2 text-white";
						break;
					case "open":
						return "text-uppercase badge badge-info p-2 text-white";
						break;
					case "filled":
						return "text-uppercase badge badge-info p-2 text-white";
						break;
					case "open":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "labeled":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "shipped":
						return "text-uppercase badge badge-primary p-2";
						break;
					case "delivered":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "consolidation":
						return "text-uppercase badge badge-danger p-2 text-white";
						break;
					case "served":
						return "label bg-success";
						break;
					case "rejected":
						return "label bg-danger";
						break;
					default:
						return "text-uppercase badge badge-primary p-2 text-white";
				}
			},
		},
	};
</script>
