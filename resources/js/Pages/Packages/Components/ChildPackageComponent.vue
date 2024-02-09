<template>
	<div class="row">
		<div class="col-md-10">
			<h1 class="font-semibold text-xl text-gray-800 leading-tight form-title">
				Package #{{ record.pkg_id }}
			</h1>
		</div>
		<!-- <div class="col-md-2">
			<span v-bind:class="getLabelClass(record.status)" class="text-sm m-1">
				{{ record.status }}
			</span>
			<span class="text-uppercase badge badge-success p-2 text-white text-sm m-1"
				v-if="record.payment_status == 'Paid'">Paid
			</span>
		</div> -->
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card mt-2">
				<div class="card-header">
					<h3 class="float-left">Package Detail</h3>
					<h3 class="float-right">
						<inertia-link :href="route('customers.show', record.pkg_customer_id)" class="btn btn-link">
							{{ record.u_name }} - {{ record.pkg_customer_id }}
						</inertia-link>
					</h3>
				</div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered">
						<thead>
							<tr>
								<th>Package ID</th>
								<th>Dimension</th>
								<th>Weight</th>
								<th>Ship From</th>
								<th>Ship To</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<span class="badge badge-primary text-sm">PKG #{{ record.pkg_id }}</span>
								</td>
								<td>
									{{ record.pb_length }}
									{{ record.pb_dim_unit }} x
									{{ record.pb_width }}
									{{ record.pb_dim_unit }} x
									{{ record.pb_height }}
									{{ record.pb_dim_unit }}
								</td>
								<td>
									{{ record.pb_weight }}
									{{ record.pb_weight_unit }}
								</td>
								<td>
									<p>Name: {{ record.from_name }}</p>
									<p>Company: {{ record.from_company }}</p>
									<p>
										Address: {{ record.from_address }} {{ record.from_address_2 }}
										{{ record.from_address_3 }}
									</p>
									<p>Zip Code:{{ record.from_zip_code }}</p>
									<p>City: {{ record.from_city }}</p>
									<p>State: {{ record.from_state }}</p>
									<p>Country: {{ record.from_country_code }}</p>
									<p>Phone: {{ record.from_phone }}</p>
									<p>Email: {{ record.from_email }}</p>
								</td>
								<td>
									<p>Name: {{ record.to_name }}</p>
									<p>Company: {{ record.to_company }}</p>
									<p>
										Address: {{ record.to_address }} {{ record.to_address_2 }}
										{{ record.to_address_3 }}
									</p>
									<p>Zip Code:{{ record.to_zip_code }}</p>
									<p>City: {{ record.to_city }}</p>
									<p>State: {{ record.to_state }}</p>
									<p>Country: {{ record.to_country_code }}</p>
									<p>Phone: {{ record.to_phone }}</p>
									<p>Email: {{ record.to_email }}</p>
								</td>
								<!-- <td>{{ box.tracking_in }}</td> -->
							</tr>
						</tbody>
					</table>
					<!-- 
					<template v-if="record.status != 'open'">
						<a class="btn btn-warning btn-sm m-1" :href="route('packages.pdf', record.id)" target="_blank">
							<i class="fa fa-print mr-1"></i>Print Commercial Invoice</a>
					</template>

					<template v-if="record.payment_status == 'Paid' && $page.props.auth.user.type == 'admin'">
						<a class="btn btn-info btn-sm m-1" @click="generateLabel">
							<i class="fas fa-wrench mr-1"></i>Generate Label</a>

						<a :href="labelURL(record.label_url)" target="_blank" v-if="record.label_generated_at"
							class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Print
							Label</a>
					</template> -->
					<!-- 
					<template
						v-if="record.payment_status == 'Pending' && record.address_book_id != 0 && record.address_type == 'international'">

						<inertia-link class="btn btn-primary btn-sm m-1" v-if="record.custom_form_status == 1"
							:href="route('packages.custom', { id: record.id, mode: 'edit' })">
							<i class="fa fa-edit mr-1"></i>Custom Form
						</inertia-link>

						<inertia-link class="btn btn-primary btn-sm m-1" :href="route('packages.custom', record.id)"
							v-if="record.custom_form_status == 0">
							<i class="fa fa-copy mr-1"></i>Customs Form
						</inertia-link>
					</template> -->
				</div>
			</div>
		</div>
	</div>
</template>
<script>
// import $ from "jquery";
export default {
	name: "Child Package Component",
	props: {
		record: Object,
	},
	data() {
		return {
			// 
		};
	},
	methods: {
		// siuteNum(user_id) {
		// 	return 4000 + user_id;
		// },
		// imgURL(url) {
		// 	return "/public/uploads/" + url;
		// },
		// labelURL(url) {
		// 	return "/" + url;
		// },
		// viewImage(event) {
		// 	console.log(event.target.src);
		// 	var modal = document.getElementById("imageViewer");
		// 	var imageSRC = document.querySelector("#imageViewer img");
		// 	imageSRC.src = event.target.src;
		// 	modal.classList.add("show");
		// 	$("#imageViewer").show();
		// },
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
		generateLabel() {
			this.label_form.post(this.route("packages.generate-label"))
				.then(response => {
					// 
				})
				.catch(error => {
					alert('err');
				})
				.finally(() => {
					//
				});
		},
	},
};
</script>
