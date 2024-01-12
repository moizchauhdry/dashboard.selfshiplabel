<template>
	<MainLayout>
		<div class="card mb-2" v-if="order.tracking_number_in">
			<div class="card-header font-bold">Order Invoice & Tracking Number</div>
			<div class="card-body">
				<div class="col-md-4">
					<table class="table table-striped table-bordered">
						<tr>
							<th>Tracking Number</th>
							<td>{{ order.tracking_number_in }}</td>
						</tr>
						<tr>
							<th>Order Invoice</th>
							<td>
								<a :href="imgURL(order.receipt_url)" class="m-1" download>
									<i class="fa fa-print mr-1"></i>Download Invoice
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="card mb-2">
			<div class="card-header font-bold">Order Detail</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<td>Customer</td>
									<td>{{ order.customer.name }}</td>
								</tr>
								<tr>
									<td>Warehouse</td>
									<td>{{ order.warehouse.name }}</td>
								</tr>

								<tr v-if="order.order_type == 'pickup'">
									<td>Mall name</td>
									<td>{{ order.store.name }}</td>
								</tr>

								<tr v-if="order.order_type == 'pickup'">
									<td>Store name</td>
									<td>{{ order.store_name }}</td>
								</tr>

								<tr v-if="order.order_type == 'pickup'">
									<td>Pickup Date</td>
									<td>{{ order.pickup_date }}</td>
								</tr>

								<tr v-if="order.order_type == 'shopping'">
									<td>Site Name</td>
									<td>{{ order.site_name }}</td>
								</tr>

								<tr v-if="order.order_type == 'shopping'">
									<td>Site URL</td>
									<td>
										<a :href="order.site_url">{{ order.site_url }}</a>
									</td>
								</tr>

								<tr>
									<td>Notes</td>
									<td>{{ order.notes }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-2">
			<div class="card-header font-bold">Order Items</div>
			<div class="card-body">
				<div class="row">
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Name</th>
									<th scope="col">Description</th>
									<th scope="col" v-if="order.pickup_type != 'pickup_only'">
										Price
									</th>
									<th scope="col" v-if="order.pickup_type != 'pickup_only'">
										Price with Tax
									</th>
									<th scope="col">Quantity</th>
									<th scope="col" v-if="order.pickup_type != 'pickup_only'">
										Total
									</th>
									<th scope="col" v-if="order.order_type == 'shopping'">URL</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="item in order.items" :key="item.id">
									<td>{{ item.id }}</td>
									<td>{{ item.name }}</td>
									<td>{{ item.description }}</td>
									<td v-if="order.pickup_type != 'pickup_only'">
										{{ item.unit_price }}
									</td>
									<td v-if="order.pickup_type != 'pickup_only'">
										{{ item.price_with_tax }}
									</td>
									<td>{{ item.quantity }}</td>
									<td v-if="order.pickup_type != 'pickup_only'">
										{{ item.sub_total }}
									</td>
									<td v-if="order.order_type == 'shopping'">
										<a :href="item.url">{{ item.url }}</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-4 offset-md-8">
						<table class="table table-responsive table-borderless">
							<tbody>
								<tr
									v-if="
										order.order_type == 'shopping' ||
										order.pickup_type == 'shipping_xps_purchase'
									"
								>
									<th style="text-align: end">Sub Total</th>
									<td>${{ order.sub_total }}</td>
								</tr>
								<tr>
									<th style="text-align: end">Shipping Charges</th>
									<td>${{ order.shipping_from_shop }}</td>
								</tr>
								<tr v-if="order.order_type == 'pickup'">
									<th style="text-align: end">Pickup Charges</th>
									<td>{{ order.pickup_charges }}</td>
								</tr>
								<tr
									v-if="
										order.order_type == 'shopping' ||
										order.pickup_type == 'shipping_xps_purchase'
									"
								>
									<th style="text-align: end">
										Service Charges <br />
										<small>minimum $5 or 5% of subtotal</small>
									</th>
									<td>${{ order.service_charges }}</td>
								</tr>
								<tr
									v-if="
										order.order_type == 'pickup' &&
										order.shipping_charges != null
									"
								>
									<th style="text-align: end">Shipping Charges</th>
									<td>${{ order.shipping_charges }}</td>
								</tr>
								<tr
									v-if="
										order.order_type == 'pickup' &&
										additional_pickup_charges != null
									"
								>
									<th style="text-align: end">Box Price</th>
									<td>{{ additional_pickup_charges }}</td>
								</tr>
								<tr>
									<th style="text-align: end">Grand Total</th>
									<td>${{ order.grand_total }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="card mb-2">
			<div class="card-header font-bold">Order Images</div>
			<div class="card-body">
				<div v-for="image in order.images" :key="image.id" class="col-md-3">
					<div class="text-center">
						<img
							style="width: 100px; height: auto"
							class="img-thumbnail"
							@click="viewImage($event)"
							:src="imgURL(image.image)"
						/>
					</div>
				</div>
			</div>
		</div> -->
	</MainLayout>

	<ImageViewer> </ImageViewer>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import ImageViewer from "@/Components/ImageViewer";
	import $ from "jquery";
	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			ImageViewer,
		},

		props: {
			auth: Object,
			order: Object,
			order_details: Object,
			additional_pickup_charges: Number,
		},
		methods: {
			imgURL(url) {
				return "/public/uploads/" + url;
			},
			viewImage(event) {
				console.log(event.target.src);
				var modal = document.getElementById("imageViewer");
				var imageSRC = document.querySelector("#imageViewer img");
				imageSRC.src = event.target.src;
				modal.classList.add("show");
				$("#imageViewer").show();
			},
		},
	};
</script>
