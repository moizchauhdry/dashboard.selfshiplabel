<template>
	<MainLayout>
		<form @submit.prevent="submit" enctype="multipart/form-data">
			<div class="card mb-5">
				<div class="card-header">
					<span class="font-bold">Online Order</span>
				</div>
				<div class="card-body">
					<breeze-validation-errors class="mb-4 text-lg" />
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<breeze-label for="warehouse_id" value="Warehouse" />
								<select
									name="warehouse_id"
									class="form-control custom-select"
									v-model="form.warehouse_id"
									@change="changeWarehouse()"
								>
									<option value="">--Select Warehouse-</option>
									<option
										v-for="warehouse in warehouses"
										:value="warehouse.id"
										:key="warehouse.id"
									>
										{{ warehouse.name }}
									</option>
								</select>
							</div>
							<div class="form-group">
								<breeze-label for="package_weight" value="Site Name" />
								<input
									v-model="form.site_name"
									class="form-control"
									type="text"
									placeholder="e.g Amazon, Alibaba etc."
								/>
							</div>
							<div class="form-group">
								<breeze-label for="" value="Shop URL" />
								<input
									v-model="form.shop_url"
									type="url"
									class="form-control"
									placeholder="https://www.amazon.com"
								/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<breeze-label for="notes" value="Notes" />
								<textarea
									v-model="form.notes"
									name="notes"
									id="notes"
									class="form-control"
									placeholder="Notes"
									rows="7"
								>
								</textarea>
							</div>
						</div>
					</div>

					<button
						type="button"
						@click="addItem"
						class="btn btn-success float-right mb-2"
					>
						<i class="fas fa-plus"></i> Add Item
					</button>

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th class="bg-primary text-white text-center" colspan="8">
										Order Items
									</th>
								</tr>
								<tr>
									<th>Name</th>
									<th>Desc</th>
									<th>URL</th>
									<th>Price</th>
									<th>Price - Tax</th>
									<th>Quantity</th>
									<th>Line Total</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<template v-for="(item, index) in form.items">
									<tr>
										<td>
											<input
												v-model="item.name"
												type="text"
												class="form-control item-name"
												placeholder="Name"
											/>
										</td>
										<td>
											<input
												v-model="item.option"
												type="text"
												class="form-control item-desc"
												placeholder="Description"
											/>
										</td>
										<td class="item_url">
											<input
												v-model="item.url"
												type="url"
												class="form-control item-url"
												placeholder="URL"
											/>
										</td>
										<td>
											<input
												v-model="item.price"
												@keyup="getLineTotal(index)"
												@click="getLineTotal(index)"
												ref="price_online"
												type="number"
												min="0"
												step="0.01"
												class="form-control item-price"
											/>
										</td>
										<td>
											<input
												v-model="item.price_with_tax"
												type="number"
												readonly
												class="form-control item-tax"
											/>
										</td>
										<td class="item_qty">
											<input
												v-model="item.qty"
												@keyup="getLineTotal(index)"
												type="number"
												min="1"
												class="form-control item-qty"
											/>
										</td>
										<td>
											<input
												v-model="item.sub_total"
												type="number"
												readonly
												class="form-control item-subtotal"
											/>
										</td>
										<td>
											<button
												type="button"
												@click="removeItem(index)"
												class="btn btn-link"
												:disabled="index == 0"
											>
												<i class="fas fa-times"></i>
											</button>
										</td>
									</tr>
								</template>
								<tr>
									<td colspan="4"></td>
									<th colspan="2">Subtotal</th>
									<td>
										<input
											v-model="form.sub_total"
											type="number"
											class="form-control"
											readonly
										/>
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<th colspan="2">Shipping Charges</th>
									<td>
										<input
											v-model="form.shipping_from_shop"
											type="number"
											step="0.01"
											class="form-control"
											@keyup="getGrandTotal()"
										/>
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<th colspan="2">Service Charges</th>
									<td>
										<input
											v-model="form.service_charges"
											type="number"
											class="form-control"
											readonly
										/>
									</td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<th colspan="2">Grand Total</th>
									<td>
										<input
											v-model="form.grand_total"
											type="number"
											class="form-control"
											readonly
										/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="order-button">
						<input
							type="submit"
							value="Create Order"
							class="btn btn-danger float-right"
						/>
					</div>
				</div>
			</div>
		</form>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
			BreezeValidationErrors,
		},
		data() {
			return {
				form: this.$inertia.form({
					form_type: "shopping",
					warehouse_id: "",
					site_name: "",
					shop_url: "",
					notes: "",
					shipping_from_shop: "",
					sales_tax: "",
					pickup_type: "",
					pickup_date: "",
					items: [
						{
							name: "",
							option: "",
							url: "",
							price: 0,
							price_with_tax: 0,
							qty: 1,
							sub_total: 0,
						},
					],
					pickup_charges: 0,
					grand_total: 0,
					discount: 0,
					sub_total: 0,
					service_charges: 0,
					discount_percentage: 0,
				}),
				tabs: {
					tab1: true,
					tab2: false,
				},
				stores: [],
			};
		},
		props: {
			auth: Object,
			warehouses: Object,
		},
		methods: {
			submit() {
				this.form.post(this.route("shop-for-me.store"));
			},
			addItem() {
				this.form.items.push({
					name: "",
					option: "",
					url: "",
					price: 0,
					price_with_tax: 0,
					qty: 1,
					sub_total: 0,
				});
			},
			removeItem(index) {
				this.form.items.splice(index, 1);
				this.getGrandTotal();
			},
			changeWarehouse() {
				this.$refs.price_online[0].click();
			},
			getGrandTotal() {
				var sum = 0;
				var shipping_charges = 0;
				this.form.grand_total = 0;

				this.form.items.forEach(function (n) {
					sum += parseFloat(n["sub_total"]);
				});

				this.form.sub_total = parseFloat(sum).toFixed(2);

				if (this.form.shipping_from_shop > 0) {
					shipping_charges = parseFloat(this.form.shipping_from_shop);
				}

				sum = sum + shipping_charges;

				var servivceCharges = sum * 0.05;

				if (servivceCharges <= 5 && sum > 0) {
					this.form.service_charges = parseFloat(5).toFixed(2);
				} else {
					this.form.service_charges = parseFloat(servivceCharges).toFixed(2);
				}

				var total = parseFloat(sum) + parseFloat(this.form.service_charges);
				this.form.grand_total = parseFloat(total).toFixed(2);
			},
			getLineTotal(index) {
				var sale_tax = 0;
				var line_total = 0;

				for (var i = 0; i < this.warehouses.length; i++) {
					if (this.warehouses[i]["id"] == this.form.warehouse_id) {
						sale_tax = this.warehouses[i]["sale_tax"];
					}
				}

				const item = this.form.items[index];

				var gross_total = item.price * (sale_tax / 100);
				var net_total = (item.price_with_tax = (
					parseFloat(gross_total) + parseFloat(item.price)
				).toFixed(2));

				line_total = net_total * item.qty;
				item.sub_total = line_total.toFixed(2);

				this.getGrandTotal();
			},
		},
	};
</script>

<style>
	.table td,
	.table th {
		padding: 5px;
	}

	.item-name {
		min-width: 15em;
	}

	.item-desc {
		min-width: 25em;
	}

	.item-url {
		min-width: 15em;
	}

	.item-price {
		min-width: 6em;
	}

	.item-tax {
		min-width: 6em;
	}

	.item-qty {
		min-width: 5em;
	}

	.item-subtotal {
		min-width: 6em;
	}
</style>
