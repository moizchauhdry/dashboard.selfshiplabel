<template>
	<MainLayout>
		<div>
			<section>
				<div class="container">
					<div class="stock-subscription-form">
						<form @submit.prevent="submit" enctype="multipart/form-data">
							<div class="order-form">
								<breeze-validation-errors class="mb-4 mt-4" />
								<flash-messages class="mb-4" />

								<h2 class="font-semibold text-xl text-gray-800 leading-tight">
									Edit Package
								</h2>
								<fieldset class="border p-3 mt-2 mb-2">
									<div class="row">
										<div class="col-md-4 form-group">
											<breeze-label for="customer_id" value="Customer" />
											<select name="customer_id" class="form-select" disabled>
												<option v-for="customer in customers" :value="customer.id"
													:key="customer.id">
													{{ customer.id + 4000 }} - {{ customer.name }}
												</option>
											</select>
										</div>

										<div class="col-md-4 form-group">
											<breeze-label for="warehouse_id" value="Received At" />
											<select name="warehouse_id" class="form-select" v-model="form.warehouse_id"
												required>
												<option value="" selected>--Select Warehouse--</option>
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
												type="number" class="form-control" placeholder="Package Weight" min="0"
												step="0.01" required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_length" value="Package Length" />
											<input v-model="form.package_length" name="package_length" id="package_length"
												type="number" class="form-control" placeholder="Package Length" min="1"
												step="0.01" required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_height" value="Package Height" />
											<input v-model="form.package_height" name="package_height" id="package_height"
												type="number" class="form-control" placeholder="Package Height" min="1"
												step="0.01" required />
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="package_weight" value="Package Width" />
											<input v-model="form.package_width" name="package_width" id="package_width"
												type="number" class="form-control" placeholder="Package Width" min="1"
												step="0.01" required />
										</div>
									</div>
								</fieldset>

								<h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

								<template v-if="order.images.length > 0">
									<h2 class="font-semibold text-xl text-gray-800 leading-tight">
										Uploaded Images
									</h2>
									<fieldset class="border p-3 mt-2 mb-2">
										<div class="row">
											<div v-for="(image, index) in order.images" :key="image.id" class="col-md-2">
												<div>
													<img style="width: 100px; height: auto" class="img-thumbnail"
														:src="imgURL(image.image)" @click="viewImage($event)" />
												</div>
												<div>
													<button class="bg-danger text-white p-1 mt-2"
														@click="deleteImage($event, index, image.id)">
														<i class="fa fa-trash"></i> Delete
													</button>
												</div>
											</div>
										</div>
									</fieldset>
								</template>

								<div class="order-button">
									<input type="submit" value="Save & Update" class="btn btn-success" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
		</div>
	</MainLayout>
	<ImageViewer> </ImageViewer>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import ImageViewer from "@/Components/ImageViewer";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import $ from "jquery";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		BreezeValidationErrors,
		ImageViewer,
	},
	data() {
		return {
			form: this.$inertia.form({
				id: this.order.id,
				status: this.order.status,
				customer_id: this.order.customer_id,
				warehouse_id: this.order.warehouse_id,
				tracking_number_in: this.order.tracking_number_in,
				shipping_total: this.order.shipping_total,
				package_weight: this.order.package_weight,
				package_length: this.order.package_length,
				package_width: this.order.package_width,
				package_height: this.order.package_height,
				weight_unit: this.order.weight_unit,
				dim_unit: this.order.dim_unit,
				notes: this.order.notes,
				received_from: this.order.received_from,
				address: this.order.address,
				post_image: "",
				items: this.order.items,
				images: [
					{
						image: "",
					},
				],
			}),
		};
	},
	props: {
		auth: Object,
		customers: Object,
		order: Object,
		status_list: Object,
		warehouses: Object,
	},
	methods: {
		submit() {
			this.form.post(this.route("orders.update"));
		},
		addItem() {
			this.form.items.push({
				name: "",
				description: "",
				quantity: "",
				/*price: "",
		price_with_tax: "",
		sub_total: "",*/
			});
		},
		removeItem(index, item_id) {
			//when deleting already saved item, remove from server also.
			if (typeof item_id !== "undefined") {
				axios
					.post(this.route("orders.removeItem"), { item_id: item_id })
					.then(({ data }) => {
						this.form.items.splice(index, 1);
					});
			} else {
				this.form.items.splice(index, 1);
			}
		},
		addImage() {
			this.form.images.push({
				name: "",
			});
		},
		removeImage(index) {
			this.form.images.splice(index, 1);
		},
		deleteImage(e, index, id) {
			e.preventDefault();
			if (typeof id !== "undefined") {
				let r = confirm("Are you sure you want to delete this image?");
				if (r) {
					axios
						.post(this.route("orders.removeImage"), { id: id })
						.then(({ data }) => {
							this.order.images.splice(index, 1);
						});
				}
			}
		},
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
		changeDimention(event) {
			console.log(event.target.value);

			this.form.dim_unit = event.target.value == "kg" ? "cm" : "in";
		},
	},
};
</script>
