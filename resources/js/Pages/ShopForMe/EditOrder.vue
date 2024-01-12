<template>
	<MainLayout>
		<div v-if="form.updated_by_admin == '1' && $page.props.auth.user.type == 'customer'"
			class="alert alert-warning font-bold text-center">
			Please be advised that ShippingXPS has made updates or changes to your
			order. Kindly review and approve the changes as necessary. Thank you.
		</div>

		<div class="alert alert-danger font-bold text-center" v-if="order.status == 'rejected'">
			This order has been rejected by ShippingXPS.
		</div>

		<div class="row">
			<div class="col-md-12 mb-2">
				<PaymentComponent v-bind="$props"></PaymentComponent>
			</div>
		</div>

		<!-- Invoice Section -->
		<div class="card mb-3" v-if="$page.props.auth.user.type == 'admin' && order.payment_status == 'Paid'">
			<div class="card-header font-bold">Invoice & Tracking Number</div>
			<div class="card-body">
				<div class="row">
					<table class="table table-bordered table-striped">
						<tr>
							<th>Receipts,invoice,docs etc.</th>
							<td>
								<input type="file" class="form-control" name="receipt_url" id="receipt_url"
									accept=".png,.jpg,.jpeg,.pdf,.docx,.xls,.xlsx"
									@input="invoice_form.receipt_url = $event.target.files[0]" />
								<progress v-if="invoice_form.progress" :value="invoice_form.progress.percentage" max="100">
									{{ invoice_form.progress.percentage }}%
								</progress>

								<a :href="imgURL(order.receipt_url)" class="m-1" download v-if="order.receipt_url">
									<i class="fa fa-print mr-1"></i>Download Invoice
								</a>
							</td>
							<th>Tracking Number</th>
							<td>
								<input v-model="invoice_form.tracking_number_in" type="text" class="form-control" />
							</td>
							<td>
								<button @click="updateInvoice()" type="button" class="btn btn-primary">
									Update
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<!-- Order Section -->
		<form @submit.prevent="submit" enctype="multipart/form-data">
			<div class="card mb-3">
				<div class="card-header"><b>Online Order</b></div>
				<div class="card-body">
					<fieldset :disabled="order.status != 'pending'">
						<breeze-validation-errors class="mb-4 text-lg" />
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<breeze-label for="warehouse_id" value="Warehouse" />
									<select name="warehouse_id" class="form-control custom-select"
										v-model="form.warehouse_id" disabled>
										<option v-for="warehouse in warehouses" :value="warehouse.id" :key="warehouse.id">
											{{ warehouse.name }}
										</option>
									</select>
								</div>
								<div class="form-group">
									<breeze-label for="package_weight" value="Site Name" />
									<input v-model="form.site_name" name="site_name" id="site_name" type="text"
										class="form-control" placeholder="Site Name" />
								</div>
								<div class="form-group">
									<breeze-label for="package_length" value="Shop URL" />
									<input v-model="form.shop_url" name="shop_url" id="shop_url" type="url"
										class="form-control" placeholder="Shop URL" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<breeze-label for="notes" value="Notes" />
									<textarea v-model="form.notes" class="form-control" rows="7">
									</textarea>
								</div>
							</div>
						</div>

						<button type="button" @click="addItem()" class="btn btn-success float-right mb-2">
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
										<th>Description</th>
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
												<input v-model="item.name" type="text" class="form-control item-name"
													placeholder="Name" />
											</td>
											<td>
												<input v-model="item.description" type="text" class="form-control item-desc"
													placeholder="Description" />
											</td>
											<td>
												<input v-model="item.url" type="url" class="form-control item-url"
													placeholder="URL" />
											</td>
											<td>
												<input v-model="item.unit_price" @keyup="getLineTotal(index)"
													@click="getLineTotal(index)" ref="price" type="number" min="0"
													step="0.01" class="form-control item-price" />
											</td>
											<td>
												<input v-model="item.price_with_tax" type="number" readonly
													class="form-control item-tax" />
											</td>
											<td>
												<input v-model="item.quantity" @keyup="getLineTotal(index)" type="number"
													min="1" class="form-control item-qty" />
											</td>
											<td>
												<input v-model="item.sub_total" type="number" readonly
													class="form-control item-subtotal" />
											</td>
											<td>
												<button type="button" @click="removeItem(index)" class="btn btn-link"
													:disabled="index == 0">
													<i class="fas fa-times"></i>
												</button>
											</td>
										</tr>
									</template>
									<tr>
										<td colspan="4"></td>
										<th colspan="2">Subtotal</th>
										<td>
											<input v-model="form.sub_total" type="number" class="form-control" readonly />
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<th colspan="2">Shipping Charges</th>
										<td>
											<input v-model="form.shipping_from_shop" type="number" class="form-control"
												step="0.01" @keyup="getGrandTotal()" />
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<th colspan="2">Service Charges</th>
										<td>
											<input v-model="form.service_charges" type="number" class="form-control"
												readonly />
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
										<th colspan="2">Grand Total</th>
										<td>
											<input v-model="form.grand_total" type="number" class="form-control" readonly />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</fieldset>
				</div>
				<div class="card-footer">
					<template v-if="$page.props.auth.user.type == 'customer' &&
						order.payment_status != 'Paid'
						">
						<button v-if="order.status == 'pending'" type="button" @click="updateChanges()"
							class="btn btn-success float-right">
							Update Order
						</button>

						<button class="btn btn-primary" @click="approveChanges()" v-if="order.status == 'approved'">
							Accept & Checkout
						</button>
					</template>

					<template v-if="$page.props.auth.user.type == 'admin' ||
						$page.props.auth.user.type == 'manager'
						">
						<button v-if="order.payment_status != 'Paid'" type="submit" value="Update Order"
							class="btn btn-success float-right">
							Update Order
						</button>

						<a v-if="order.status != 'approved'" v-on:click="changeAdminStatus(order.id, 'approved')"
							class="ml-2 me-2 btn mt-2 btn-success">Approve</a>
						<a v-if="order.status != 'rejected'" v-on:click="changeAdminStatus(order.id, 'rejected')"
							class="ml-2 me-2 btn mt-2 btn-danger">Reject</a>
					</template>
				</div>
			</div>
		</form>

		<!-- Comments Section -->
		<div class="card mb-3">
			<div class="card-header font-bold">Comments</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-12">
						<textarea class="form-control" v-model="commentForm.message" rows="1"></textarea>
					</div>
					<div class="col-md-12">
						<label class="btn btn-primary float-right" @click="saveComment()">Save Comment</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div v-for="comment in comments" :key="comment.id">
							<div class="card">
								<div class="card-header">
									<b>{{ comment.user.name }}</b>
									<span class="float-right">
										{{ comment.created_at }}
									</span>
								</div>
								<div class="card-body">
									{{ comment.message }}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import PaymentComponent from "./PaymentComponent.vue";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		BreezeValidationErrors,
		PaymentComponent
	},
	data() {
		return {
			form: this.$inertia.form({
				form_type:
					this.order.order_type == "shopping" ? "shopping" : "pickup",
				id: this.order.id,
				warehouse_id: this.order.warehouse_id,
				store_id: this.order.store_id,
				store_charges: this.order.store_charges,
				store_tax: this.order.store_tax,
				site_name: this.order.site_name,
				shop_url: this.order.site_url,
				status: this.order.status,
				notes: this.order.notes,
				order_origin: this.order.order_origin,
				items: this.order.items,
				pickup_type: this.order.pickup_type,
				pickup_charges:
					this.order.pickup_charges == null ? 0 : this.order.pickup_charges,
				only_pickup:
					this.order.order_type == "shopping" ? "" : this.order.only_pickup,
				shipping_xps:
					this.order.order_type == "shopping" ? "" : this.order.shipping_xps,
				pickup_date:
					this.order.order_type == "shopping" ? "" : this.order.pickup_date,
				is_complete_shopping: 0,
				is_changed: this.order.is_changed,
				updated_by_admin: this.order.updated_by_admin,
				changes_approved: this.order.changes_approved,
				shipping_from_shop:
					this.order.shipping_from_shop != null
						? this.order.shipping_from_shop
						: 0,
				sales_tax: this.order.sales_tax,
				discount: this.order.discount != null ? this.order.discount : 0,
				service_charges: this.order.service_charges,
				shipping_charges: this.order.shipping_charges,
				grand_total: this.order.grand_total,
				sub_total: this.order.sub_total,
				sale_tax: 0,
				receipt_url: this.order.receipt_url,
				is_service_charges: this.order.is_service_charges,
			}),
			invoice_form: this.$inertia.form({
				order_id: this.order.id,
				receipt_url: this.order.receipt_url,
				tracking_number_in: this.order.tracking_number_in,
			}),
			commentForm: this.$inertia.form({
				message: "",
			}),
			comments: this.comments,
			tabs: {
				tab1: this.order.order_type == "shopping" ? true : false,
				tab2: this.order.order_type == "pickup" ? true : false,
			},
			stores: [],
			order: this.order,
			statusForm: this.$inertia.form({
				id: null,
				status: null,
			}),
		};
	},
	props: {
		errors: Object,
		auth: Object,
		warehouses: Object,
		order: Object,
		salePrice: Object,
		additional_pickup_charges: Number,
		comments: Object,
	},
	methods: {
		submit() {
			this.form.post(this.route("shop-for-me.update"));
		},
		adminServicesCharges() {
			if (this.$page.props.auth.user.type === "admin") {
				console.log("adminServicesCharges() triggered...");
				console.log(this.form.is_service_charges);
				this.form.is_service_charges = 1;
				console.log(this.form.is_service_charges);
				this.getGrandTotal(0);
			}
		},
		updateChanges() {
			this.form.changes_approved = 0;
			this.submit();
		},
		changeToCompleteShopping() {
			this.form.is_complete_shopping = 1;
			this.submit();
		},
		approveChanges() {
			this.form.changes_approved = 1;
			this.submit();
		},
		changeAdminStatus(id, status) {
			this.statusForm.id = id;
			this.statusForm.status = status;
			this.statusForm.post(this.route("shop-for-me.changeStatus"));
		},
		addItem() {
			this.form.items.push({
				name: "",
				description: "",
				url: "",
				unit_price: 0,
				price_with_tax: 0,
				quantity: 1,
				sub_total: 0,
			});
		},
		removeItem(index) {
			this.form.items.splice(index, 1);
			this.getGrandTotal();
		},
		wareHouseChangeOnline() {
			this.$refs.price[0].click();
		},
		imgURL(url) {
			return "/public/uploads/" + url;
		},
		loadComments() { },
		saveComment() {
			if (this.commentForm.message == "") {
				return false;
			}
			this.commentForm.post(
				this.route("shop-for-me.storeComment", this.order.id)
			);
			this.commentForm.reset();
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

			var gross_total = item.unit_price * (sale_tax / 100);
			var net_total = (item.price_with_tax = (
				parseFloat(gross_total) + parseFloat(item.unit_price)
			).toFixed(2));

			line_total = net_total * item.quantity;
			item.sub_total = line_total.toFixed(2);

			this.getGrandTotal();
		},
		updateInvoice() {
			this.invoice_form.post(this.route("shop-for-me.update-invoice"));
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
