<template>
	<div class="row">
		<div class="col-md-10">
			<h1 class="font-semibold text-xl text-gray-800 leading-tight form-title">
				Package #{{ packag.id }}
			</h1>
		</div>
		<div class="col-md-2">
			<span v-bind:class="getLabelClass(packag.status)" class="text-sm m-1">
				{{ packag.status }}
			</span>
			<span class="text-uppercase badge badge-success p-2 text-white text-sm m-1"
				v-if="packag.payment_status == 'Paid'">Paid
			</span>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card mt-2">
				<div class="card-header">
					<h3 class="float-left">Packages/Orders Included</h3>
					<h3 class="float-right">
						<inertia-link :href="route('customers.show', packag?.customer?.id)" class="btn btn-link">
							# {{ siuteNum(packag?.customer?.id) }} -
							{{ packag?.customer?.name }}
						</inertia-link>
					</h3>
				</div>
				<div class="card-body">
					<table class="table table-sm table-striped table-bordered text-center">
						<thead>
							<tr>
								<th>Package #</th>
								<th>Dimension</th>
								<th>Weight</th>
								<th>Warehouse</th>
								<th>Tracking In</th>
								<th>Images</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<template v-for="child_pkg in child_package_orders" :key="child_pkg.id">
								<tr>
									<td>
										<span class="badge badge-primary text-sm">PKG #{{ child_pkg.pkg_id }}</span>
									</td>
									<td>
										{{ child_pkg.length }}
										{{ child_pkg.dim_unit }} x
										{{ child_pkg.width }}
										{{ child_pkg.dim_unit }} x
										{{ child_pkg.height }}
										{{ child_pkg.dim_unit }}
									</td>
									<td>
										{{ child_pkg.weight }}
										{{ child_pkg.weight_unit }}
									</td>
									<td>{{ child_pkg.warehouse }}</td>
									<td>{{ child_pkg.tracking_in }}</td>
									<td>
										<div v-for="image in child_pkg.images" :key="image.id">
											<div class="m-1 p-1">
												<img style="width: 50px; height: 50px" @click="viewImage($event)"
													:src="imgURL(image.image)" />
											</div>
										</div>
										<div class="text-xs text-danger" v-if="child_pkg.images.length == 0">
											Not uploaded yet.
										</div>
									</td>
									<td>
										<inertia-link v-if="$page.props.auth.user.type == 'admin' &&
											child_pkg.status == 'open'
											" :href="route('order.edit', child_pkg.order_id)" class="btn btn-primary btn-sm m-1"><i
												class="fa fa-edit mr-1"></i>Edit</inertia-link>
									</td>
								</tr>
							</template>
						</tbody>
					</table>

					<template v-if="packag.status != 'open'">
						<a class="btn btn-warning btn-sm m-1" :href="route('packages.pdf', packag.id)" target="_blank">
							<i class="fa fa-print mr-1"></i>Print Commercial Invoice</a>
					</template>

					<template v-if="packag.payment_status == 'Paid' && $page.props.auth.user.type == 'admin'">
						<a class="btn btn-info btn-sm m-1" @click="generateLabel">
							<i class="fas fa-wrench mr-1"></i>Generate Label</a>

						<a :href="labelURL(packag.label_url)" target="_blank" v-if="packag.label_generated_at"
							class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Print
							Label</a>
					</template>

					<template
						v-if="packag.payment_status == 'Pending' && packag.address_book_id != 0 && packag.address_type == 'international'">

						<inertia-link class="btn btn-primary btn-sm m-1" v-if="packag.custom_form_status == 1"
							:href="route('packages.custom', { id: packag.id, mode: 'edit' })">
							<i class="fa fa-edit mr-1"></i>Custom Form
						</inertia-link>

						<inertia-link class="btn btn-primary btn-sm m-1" :href="route('packages.custom', packag.id)"
							v-if="packag.custom_form_status == 0">
							<i class="fa fa-copy mr-1"></i>Customs Form
						</inertia-link>
					</template>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import $ from "jquery";
export default {
	name: "Child Package Component",
	props: {
		packag: Object,
		child_package_orders: Object,
	},
	data() {
		return {
			label_form: this.$inertia.form({
				package_id: this.packag.id,
			}),
		};
	},
	methods: {
		siuteNum(user_id) {
			return 4000 + user_id;
		},
		imgURL(url) {
			return "/public/uploads/" + url;
		},
		labelURL(url) {
			return "/" + url;
		},
		viewImage(event) {
			console.log(event.target.src);
			var modal = document.getElementById("imageViewer");
			var imageSRC = document.querySelector("#imageViewer img");
			imageSRC.src = event.target.src;
			modal.classList.add("show");
			$("#imageViewer").show();
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
