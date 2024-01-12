<template>
	<MainLayout>
		<div>
			<section>
				<div class="container">
					<div class="stock-subscription-form">
						<form @submit.prevent="submit" enctype="multipart/form-data">
							<div class="order-form">
								<breeze-validation-errors class="mb-4" />
								<flash-messages class="mb-4" />

								<h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
									Create Package
								</h2>
								<fieldset class="border p-3 mt-2 mb-2">
									<div class="row">
										<div class="col-md-4 form-group">
											<breeze-label for="customer_id" value="Customer" />
											<select name="customer_id" class="form-control" v-model="form.customer_id"
												:disabled="selected_customer != 0" required>
												<option value="">--Select Customer--</option>
												<option v-for="customer in customers" :value="customer.id"
													:key="customer.id">
													{{ customer.id + 4000 }} - {{ customer.name }}
												</option>
											</select>
										</div>

										<div class="col-md-4 form-group">
											<breeze-label for="warehouse_id" value="Received At" />
											<select name="warehouse_id" class="form-control" v-model="form.warehouse_id"
												required @change="onChangeWareHouse()">
												<option value="">--Select Warehouse--</option>
												<option v-for="warehouse in warehouses" :value="warehouse.id"
													:key="warehouse.id">
													{{ warehouse.name }}
												</option>
											</select>
										</div>

										<div class="col-md-4 form-group">
											<breeze-label for="tracking_number_in" value="Tracking #" />
											<input v-model="form.tracking_number_in" name="tracking_number_in"
												id="tracking_number_in" type="text" class="form-control"
												placeholder="Tracking Number" required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="warehouse_id" value="Weight Unit" />
											<select class="form-control" required :disabled="1">
												<option value="lb">Lb</option>
											</select>
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="dim_unit" value="Dimention Unit" />
											<select class="form-control" :disabled="1">
												<option value="in">Inch</option>
											</select>
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_weight" value="Package Weight" />
											<input v-model="form.package_weight" name="package_weight" id="package_weight"
												type="number" step="0.01" min="0" class="form-control"
												placeholder="Package Weight" required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_length" value="Package Length" />
											<input v-model="form.package_length" name="package_length" id="package_length"
												type="number" step="0.01" class="form-control" placeholder="Package Length"
												required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_height" value="Package Height" />
											<input v-model="form.package_height" name="package_height" id="package_height"
												type="number" step="0.01" class="form-control" placeholder="Package Height"
												required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_weight" value="Package Width" />
											<input v-model="form.package_width" name="package_width" id="package_width"
												type="number" step="0.01" class="form-control" placeholder="Package Width"
												required />
										</div>
									</div>
								</fieldset>

								<h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
									Package Images
								</h2>
								<fieldset class="border p-3 mt-2 mb-2">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-4">
													<a v-on:click="addImage" class="btn btn-primary" style="float: right">
														<span><i class="fa fa-plus mr-1"></i>Image </span>
													</a>
												</div>
											</div>

											<div v-for="(image, index) in form.images" :key="image.id" class="row">
												<div class="col-md-3 form-group">
													<div>
														<input type="file" @input="image.image = $event.target.files[0]" />
														<progress v-if="form.progress" :value="form.progress.percentage"
															max="100">
															{{ form.progress.percentage }}%
														</progress>
													</div>
													<div v-show="index != 0">
														<button v-on:click="removeImage(index)"
															class="bg-danger text-white p-1 mt-2">
															<span><i class="fas fa-trash mr-1 text-sm"></i>Remove</span>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>

								<div class="order-button">
									<input type="submit" value="Save & Submit" class="btn btn-success" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
		</div>
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
				customer_id: this.selected_customer,
				// customer_id: "",
				warehouse_id: "",
				received_from: "",
				tracking_number_in: "",
				notes: "",
				weight_unit: "lb",
				dim_unit: "in",
				package_weight: "",
				package_length: "",
				package_width: "",
				package_height: "",
				sale_tax: 0,
				images: [
					{
						image: "",
					},
				],
				items: [
					{
						name: "",
						description: "",
						qty: "",
					},
				],
			}),
		};
	},
	props: {
		auth: Object,
		customers: Object,
		selected_customer: Number,
		warehouses: Object,
	},
	methods: {
		submit() {
			this.form.post(this.route("order.store"));
		},
		addItem() {
			this.form.items.push({
				name: "",
				description: "",
				qty: "",
			});
		},
		onChangeWareHouse() {
			this.$refs.price.click();
		},
		removeItem(index) {
			this.form.items.splice(index, 1);
		},
		addImage() {
			this.form.images.push({
				name: "",
			});
		},
		removeImage(index) {
			this.form.images.splice(index, 1);
		},
		storePhoto() {
			if (this.$refs.photo) {
				this.form.post_image = this.$refs.photo.files[0];
			}
			this.form.post(route("photo.store"), {
				preserveScroll: true,
			});
		},
		addTax(event) {
			console.log("triggered...");
			var mainParent = event.target.parentNode.parentNode.parentNode;
			var row = document.getElementById(mainParent.id);
			var index = row.dataset.id;
			var quantity = this.form.items[index].qty;
			var price = this.form.items[index].price;
			var sale_tax = 0;
			for (var i = 0; i < this.warehouses.length; i++) {
				if (this.warehouses[i]["id"] == this.form.warehouse_id) {
					sale_tax = this.warehouses[i]["sale_tax"];
				}
			}
			var gross_total = price * (sale_tax / 100);
			var net_total = (this.form.items[index].price_with_tax = (
				parseFloat(gross_total) + parseFloat(price)
			).toFixed(2));
			this.form.items[index].sub_total = net_total * quantity;

			this.getGrandTotal();
		},
		getGrandTotal() {
			var sum = 0;
			this.form.items.forEach(function (n) {
				sum += n["sub_total"];
			});
			this.form.grand_total = sum;
		},
		changeDimention(event) {
			this.form.dim_unit = event.target.value == "kg" ? "cm" : "in";
		},
	},
};
</script>
