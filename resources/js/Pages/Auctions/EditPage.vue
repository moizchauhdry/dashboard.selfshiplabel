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

								<h2
									class="font-semibold text-xl text-gray-800 leading-tight form-title"
								>
									Create Auction
								</h2>
								<fieldset class="border p-3 mt-2 mb-2">
									<div class="row">
										<!-- <div class="col-md-4 form-group">
											<breeze-label for="customer_id" value="Customer" />
											<select
												name="customer_id"
												class="form-control"
												v-model="form.customer_id"
												:disabled="selected_customer != 0"
												required
											>
												<option value="">--Select Customer--</option>
												<option
													v-for="customer in customers"
													:value="customer.id"
													:key="customer.id"
												>
													{{ customer.id + 4000 }} - {{ customer.name }}
												</option>
											</select>
										</div>

										<div class="col-md-4 form-group">
											<breeze-label for="warehouse_id" value="Received At" />
											<select
												name="warehouse_id"
												class="form-control"
												v-model="form.warehouse_id"
												required
												@change="onChangeWareHouse()"
											>
												<option value="">--Select Warehouse--</option>
												<option
													v-for="warehouse in warehouses"
													:value="warehouse.id"
													:key="warehouse.id"
												>
													{{ warehouse.name }}
												</option>
											</select>
										</div> -->

										<div class="col-md-8 form-group">
											<breeze-label
												for="name"
												value="Name *"
											/>
											<input
												v-model="form.name"
												name="name"
												id="name"
												type="text"
												class="form-control"
												placeholder="Product Name"
												required
											/>
										</div>

										<div class="col-md-4 form-group">
											<breeze-label for="auction_category_id" value="Category*" />
											<select
												name="auction_category_id"
												class="form-control"
												v-model="form.auction_category_id"
												required
											>
												<option value="">--Select Category--</option>
												<option
													v-for="category in categories"
													:value="category.id"
													:key="category.id"
												>
													{{ category.name }}
												</option>
											</select>
										</div>

                                        <div class="col-md-12 form-group">
											<breeze-label
												for="description"
												value="Description *"
											/>
											<textarea
												v-model="form.description"
												name="description"
												id="description"
												type="text"
												class="form-control"
												placeholder="Product Name"
												required
											></textarea>
										</div>

										<div class="col-md-2 form-group">
											<breeze-label for="warehouse_id" value="Weight Unit*" />
											<select class="form-control" required :disabled="1">
												<option value="lb">Lb</option>
											</select>
										</div>

<!--										<div class="col-md-2 form-group">
											<breeze-label for="dim_unit" value="Dimention Unit*" />
											<select class="form-control" :disabled="1">
												<option value="in">Inch</option>
											</select>
										</div>-->

										<div class="col-md-2 form-group">
											<breeze-label
												for="package_weight"
												value="Package Weight*"
											/>
											<input
												v-model="form.package_weight"
												name="package_weight"
												id="package_weight"
												type="number"
												step="0.01"
												min="0"
												class="form-control"
												placeholder="Package Weight"
												required
											/>
										</div>

<!--										<div class="col-md-2 form-group">
											<breeze-label
												for="package_length"
												value="Package Length*"
											/>
											<input
												v-model="form.package_length"
												name="package_length"
												id="package_length"
												type="number"
												class="form-control"
												placeholder="Package Length"
												required
											/>
										</div>

										<div class="col-md-2 form-group">
											<breeze-label
												for="package_height"
												value="Package Height*"
											/>
											<input
												v-model="form.package_height"
												name="package_height"
												id="package_height"
												type="number"
												class="form-control"
												placeholder="Package Height"
												required
											/>
										</div>

										<div class="col-md-2 form-group">
											<breeze-label
												for="package_weight"
												value="Package Width*"
											/>
											<input
												v-model="form.package_width"
												name="package_width"
												id="package_width"
												type="number"
												class="form-control"
												placeholder="Package Width"
												required
											/>
										</div>-->

                                        <div class="col-md-6 form-group">
											<breeze-label
												for="starting_price"
												value="Starting Price*"
											/>
											<input
												v-model="form.starting_price"
												name="starting_price"
												id="starting_price"
												type="number"
                                                min="0"
												step="0.01"
												class="form-control"
												placeholder="Starting Price"
												required
											/>
										</div>

                    <div class="col-md-6 form-group">
                      <breeze-label
                          for="buy_price"
                          value="Buy Price"
                      />
                      <input
                          v-model="form.buy_price"
                          name="buy_price"
                          id="buy_price"
                          type="number"
                          min="0"
                          step="0.01"
                          class="form-control"
                          placeholder="Buy Price"

                      />
                    </div>
                                        <div class="col-md-6 form-group">
											<breeze-label
												for="ending_at"
												value="Ending At*"
											/>
											<Datepicker v-model="date"  :format="format" :enableTimePicker="false"></Datepicker>
										</div>
									</div>
								</fieldset>

								<h2
									class="font-semibold text-xl text-gray-800 leading-tight form-title"
								>
									Product Images
								</h2>
								<fieldset class="border p-3 mt-2 mb-2">
									<div class="row">
										<div class="col-md-12">
											<label class="d-block mb-2">Feature Image</label>
											<input
															type="file"
															@input="form.thumbnail = $event.target.files[0]"
														/>
														<div>
													<img
														style="width: 100px; height: auto"
														class="img-thumbnail"
														:src="'/'+auction.thumbnail"
														@click="viewImage($event)"
													/>
												</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												
												<div class="col-md-4">
													<label class="d-block mb-2 mt-5">Gallery Images</label>
													<a
														v-on:click="addImage"
														class="btn btn-primary"
														style="float: right"
													>
														<span><i class="fa fa-plus mr-1"></i>Image </span>
													</a>
												</div>
											</div>

											<div
												v-for="(image, index) in form.images"
												:key="image.id"
												class="row"
											>

												<div class="col-md-3 form-group">
													<div>
														<input
															type="file"
															@input="image.image = $event.target.files[0]"
														/>
														<progress
															v-if="form.progress"
															:value="form.progress.percentage"
															max="100"
														>
															{{ form.progress.percentage }}%
														</progress>
													</div>
													<div v-show="index != 0">
														<button
															v-on:click="removeImage(index)"
															class="bg-danger text-white p-1 mt-2"
														>
															<span
																><i class="fas fa-trash mr-1 text-sm"></i
																>Remove</span
															>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>

								<template v-if="auction_images.length > 0">
									<h2 class="font-semibold text-xl text-gray-800 leading-tight">
										Uploaded Images
									</h2>
									<fieldset class="border p-3 mt-2 mb-2">
										<div class="row">
											<div
												v-for="(image, index) in auction_images"
												:key="image.id"
												class="col-md-2"
											>
												<div>
													<img
														style="width: 100px; height: auto"
														class="img-thumbnail"
														:src="imgURL(image.image)"
														@click="viewImage($event)"
													/>
												</div>
												<div>
													<button
														class="bg-danger text-white p-1 mt-2"
														@click="deleteImage($event, index, image.id)"
													>
														<i class="fa fa-trash"></i> Delete
													</button>
												</div>
											</div>
										</div>
									</fieldset>
								</template>

								<div class="order-button">
									<input
										type="submit"
										value="Save & Submit"
										class="btn btn-success"
									/>
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
    import Datepicker from "vue3-date-time-picker";
	import "vue3-date-time-picker/dist/main.css";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
			BreezeValidationErrors,
            Datepicker
		},
		data() {
			return {
                date: this.auction.ending_at,
				form: this.$inertia.form({
					name:  this.auction.name,
					auction_category_id:  this.auction.auction_category_id,
					warehouse_id: "",
					received_from: "",
					description:  this.auction.description,
					weight_unit:  "lb",
					dim_unit:  "in",
					package_weight:  this.auction.weight,
					package_length:  this.auction.length,
					package_width:  this.auction.width,
					package_height:  this.auction.height,
					starting_price:  this.auction.starting_price,
					buy_price:  this.auction.buy_price,
					ending_at:  this.auction.ending_at,
					thumbnail:  null,
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
			auction: Object,
			categories: Object,
			auction_images: Object,
			selected_customer: Number,
			warehouses: Object,
		},
		methods: {
			submit() {
				this.form.post(this.route("auctions.update",this.auction.id));
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
			deleteImage(e, index, id) {
				e.preventDefault();
				if (typeof id !== "undefined") {
					let r = confirm("Are you sure you want to delete this image?");
					if (r) {
						axios
							.post(this.route("auctions.delete-image"), { id: id })
							.then(({ data }) => {
								this.auction_images.splice(index, 1);
							});
					}
				}
			},
			imgURL(url) {
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
			changeDimention(event) {
				console.log(event.target.value);

				this.form.dim_unit = event.target.value == "kg" ? "cm" : "in";
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
            format() {
				var start = new Date(this.date);
				var startDay = start.getDate();
				var startMonth = start.getMonth() + 1;
				var startYear = start.getFullYear();

				this.form.ending_at = `${startYear}/${startMonth}/${startDay}`;
					return `${startDay}/${startMonth}/${startYear}`;
			},
		},
	};
</script>

<style scoped>

</style>