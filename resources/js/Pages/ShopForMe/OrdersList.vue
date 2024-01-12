<template>
	<MainLayout>
		<div class="card mb-5">
			<div class="card-header"><b>Manage Shopping List</b></div>
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-md-12">
						<form @submit.prevent="submit">
							<div class="d-flex search">
								<div class="form-group">
									<label for="">Order ID</label>
									<input type="search" name="number" v-model="form.order_id" class="form-control"/>
								</div>
								<div class="form-group">
									<label for="">Suite Number</label>
									<input type="search" name="number" v-model="form.user_id" class="form-control"/>
								</div>
								<div class="form-group">
									<label for="">Order Status</label>
									<select class="form-control custom-select" v-model="form.order_status" >
										<option value="" selected>All</option>
										<option value="pending">Pending</option>
										<option value="approved">Approved</option>
									</select>
								</div>
								<div class="form-group">
									<label for="">Payment Status</label>
									<select class="form-control custom-select" v-model="form.payment_status" >
										<option value="" selected>All</option>
										<option value="paid">Paid</option>
										<option value="pending">Unpaid</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary mr-1">Search</button>
									<button type="button" class="btn btn-info" @click="clear()">Clear</button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-12">
						<template v-if="$page.props.auth.user.type == 'customer'">
							<inertia-link :href="route('shop-for-me.create')" class="btn btn-success float-right">
								<i class="fa fa-plus mr-1"></i>Add Order</inertia-link>
						</template>
					</div>
					
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead class="text-center">
							<tr>
								<th scope="col">Order. #</th>
								<th scope="col">Warehouse</th>
								<th scope="col">Customer</th>
								<th scope="col">Type</th>
								<th scope="col">Status</th>
								<th scope="col">Website</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody class="text-center">
							<tr v-for="order in orders.data" :key="order.id">
								<td>{{ order.id }}</td>
								<td>{{ order.warehouse_name }}</td>
								<td>
									<inertia-link :href="route('customers.show', order.user_id)" class="btn btn-link">
										{{ order.user_name }} - {{ siuteNum(order.user_id) }}
									</inertia-link>
								</td>
								<td class="capitalize">{{ order.order_type }}</td>
								<td class="capitalize">
									<span v-bind:class="getLabelClass(order.order_status)" class="mr-1">
										{{ order.order_status }}
									</span>
									<span v-if="order.payment_status == 'Paid'" class="label badge badge-success text-white font-bold">
										{{ order.payment_status }}
									</span>
								</td>
								<td>
									<template v-if="order.site_url">
										<a :href="order.site_url" target="_blank" class="link-primary">
											{{ order.site_name }}
										</a>
									</template>
								</td>
								<td style="min-width: 70px">
									<inertia-link class="btn btn-primary btn-xs mr-1 mb-1" :href="route('shop-for-me.show', order.id)">
										<span><i class="fa fa-list mr-1"></i></span>Detail
									</inertia-link>

									<template v-if="order.status == 'pending' || order.payment_status == 'Pending' || $page.props.auth.user.type == 'admin'">
										<inertia-link class="btn btn-success btn-xs mr-1 mb-1" :href="route('shop-for-me.edit', order.id)">
											<span><i class="fa fa-pencil-alt"></i></span> Edit & Continue
										</inertia-link>
									</template>

									<template v-if="order.status == 'labeled' && order.order_type == 'package'">
										<a :href="route('packages.pdf', order.id)" target="_blank" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice">
											<i class="fa fa-file"></i> Print Invoice
										</a>
									</template>
								</td>
							</tr>
							<tr v-if="orders.data.length == 0">
								<td colspan="7">No record found.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer">
				<pagination :links="orders.links"></pagination>
			</div>
		</div>
	</MainLayout>
</template>
<style scoped>
	.label {
		padding: 5px;
	}
</style>
<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import Pagination from "@/Components/Pagination.vue";
	import { Inertia } from "@inertiajs/inertia";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			Pagination,
		},
		props: {
			auth: Object,
			orders: Object,
			filters: Object,
		},
		data() {
			return {
				form: {
					order_id: this.filters.order_id,
					user_id: this.filters.user_id,
					order_status: this.filters.order_status,
					payment_status: this.filters.payment_status,
				},
			};
		},
		methods: {
			getLabelClass(status) {
				switch (status) {
					case "pending":
						return "label badge badge-warning text-white font-bold";
						break;
					case "arrived":
						return "label badge badge-primary text-white font-bold";
						break;
					case "labeled":
						return "label badge badge-info text-white font-bold";
						break;
					case "shipped":
						return "label badge badge-warning text-white font-bold";
						break;
					case "delivered":
						return "label badge badge-success text-white font-bold";
						break;
					case "rejected":
						return "label badge badge-danger text-white font-bold";
						break;
					case "approved":
						return "label badge badge-success text-white font-bold";
						break;
					default:
						return "label badge badge-warning text-white font-bold";
				}
			},
			siuteNum(user_id) {
				return 4000 + user_id;
			},
			submit() {
				const queryParams = new URLSearchParams(this.form);
				const url = `${route("shop-for-me.index")}?${queryParams.toString()}`;
				Inertia.visit(url, { preserveState: true });
			},
			clear() {
				this.form.order_id = "";
				this.form.user_id = "";
				this.form.order_status = "";
				this.form.payment_status = "";
				this.submit();
			},
		},
	};
</script>

<style>
	.dp__input {
		background-color: var(--dp-background-color);
		border-radius: 0px;
		font-family: -apple-system,blinkmacsystemfont,"Segoe UI",roboto,oxygen,ubuntu,cantarell,"Open Sans","Helvetica Neue",sans-serif;
		border: 1px solid var(--dp-border-color);
		outline: none;
		transition: border-color .2s cubic-bezier(0.645, 0.045, 0.355, 1);
		width: 100%;
		font-size: 1rem;
		line-height: 1.5rem;
		padding: 4px 33px;
		color: var(--dp-text-color);
		box-sizing: border-box;
	}
	.search .form-group {
		margin-left:1px
	}
</style>
