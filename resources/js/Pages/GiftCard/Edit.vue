<template>
	<MainLayout>
		<div class="card mt-4 pr-2">
			<div class="p-2">
				<h1 class="text-danger font-20">
					Note: Any gift card purschases will be final sale, no return or
					exchange. All gift card $ (Dollar) value are checked prior sending to
					the customer.
				</h1>
			</div>
			<div class="card-header"><b>Gift Card</b></div>
			<div class="card-body">
				<form @submit.prevent="submit" enctype="multipart/form-data">
					<breeze-validation-errors class="mb-4" />
					<div class="row">
						<div class="form-group col-md-6">
							<label for="title">Title</label>
							<input
								type="text"
								class="form-control"
								name="title"
								id="title"
								v-model="form.title"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
							/>
						</div>
						<div class="form-group col-md-6">
							<label for="type">Type</label>
							<select
								class="form-control"
								name="type"
								v-model="form.type"
								id="type"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
								@change="calculate_amount"
							>
								<option value="" selected>--Select Type--</option>
								<option value="PHYSICAL">Physical</option>
								<option value="ELECTRONIC">Electronic</option>
							</select>
							<small>
								1) Physical: Card Amount + 5% Service Charges + 25$ Pickup
								Charges <br />
								2) Electronic: Card Amount + 5% Service Charges
							</small>
						</div>
						<div class="form-group col-md-3">
							<label for="amount">Amount</label>
							<input
								type="number"
								step="0.01"
								class="form-control"
								name="amount"
								id="amount"
								v-model="form.amount"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
								@keyup="calculate_amount"
							/>
						</div>
						<div class="form-group col-md-3">
							<label for="qty">Quantity</label>
							<input
								type="number"
								class="form-control"
								name="qty"
								id="qty"
								v-model="form.qty"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
								@keyup="calculate_amount"
							/>
						</div>
						<div class="form-group col-md-6">
							<label for="gc_total">Total</label>
							<input
								type="number"
								class="form-control"
								name="gc_total"
								id="gc_total"
								v-model="form.gc_total"
								disabled
							/>
						</div>
						<div class="form-group col-md-10"></div>
						<div class="form-group col-md-2">
							<label for="net_total"
								><b
									>Service Charges <small>(5%)</small>:
									{{ service_charges }}$</b
								>
							</label>
							<br />
							<div v-if="form.type == 'PHYSICAL'">
								<label for="net_total"><b>Pickup Charges: 25$</b></label>
								<br />
							</div>
							<br />
							<label for="net_total"><b>Total Amount:</b></label>
							<input
								type="number"
								class="form-control"
								name="net_total"
								id="net_total"
								v-model="form.net_total"
								disabled
							/>
						</div>

						<div class="form-group col-md-6">
							<label for="notes">Notes</label>
							<textarea
								class="form-control"
								name="notes"
								id="notes"
								v-model="form.notes"
								cols="10"
								rows="5"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
							></textarea>
						</div>

						<div
							class="form-group col-md-6"
							v-if="$page.props.auth.user.type == 'admin'"
						>
							<label for="status">Status</label>
							<select
								class="form-control"
								name="status"
								v-model="form.status"
								id="status"
								:disabled="
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
							>
								<option value="" selected>--Select Status--</option>
								<option value="Accepted">Accepted</option>
								<option value="Rejected">Rejected</option>
							</select>
						</div>

						<section v-if="$page.props.auth.user.type == 'admin'">
							<h1
								class="font-semibold text-xl text-gray-800 leading-tight form-title"
							>
								Gift Card Files
							</h1>

							<h1
								class="text-danger font-20 mb-4"
								v-if="gift_card.payment_status != 'Paid'"
							>
								The Payment of this request is pending. You can only attach gift
								card images if customer paid the amount of gift card.
							</h1>

							<section v-if="gift_card.payment_status == 'Paid'">
								<div class="row">
									<div class="col-md-12">
										<a
											v-on:click="addImage"
											class="btn btn-primary"
											style="float: right"
										>
											<span>Add Image</span>
										</a>
									</div>
								</div>

								<div
									class="row"
									v-for="(image, index) in form.images"
									:key="image.id"
								>
									<div class="col-md-12">
										<div class="form-group">
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
									</div>
									<div class="col-md-2" v-show="index != 0">
										<div class="form-group">
											<a
												v-on:click="removeImage(index)"
												class="btn btn-primary btn-sm"
											>
												<span>Remove</span>
											</a>
										</div>
									</div>
								</div>
							</section>
						</section>

						<div
							class="row col-md-12"
							v-show="gift_card.payment_status == 'Paid'"
						>
							<table class="table table-striped table-bordered table-sm mb-4">
								<thead>
									<tr class="bg-primary text-white">
										<td colspan="3" class="text-center">GIFT CARD IMAGES</td>
									</tr>
									<tr>
										<th scope="col">Sr #</th>
										<th scope="col">Image</th>
										<th
											scope="col"
											v-if="$page.props.auth.user.type == 'admin'"
										>
											Action
										</th>
									</tr>
								</thead>
								<tbody>
									<tr
										v-for="(image, index) in gift_card.files"
										:key="image.id"
										v-show="gift_card.files"
									>
										<th scope="row">00{{ ++index }}</th>
										<td>
											<template
												v-if="image.file_name.split('.').pop() == 'pdf'"
											>
												<i class="fas fa-file-pdf mr-1 text-lg"></i>
											</template>
											<template v-else>
												<img
													style="width: 100px; height: auto"
													class="img-thumbnail"
													:src="imgURL(image.file_name)"
													@click="viewImage($event)"
												/>
											</template>
											<a :href="imgURL(image.file_name)" class="m-1" download>{{
												image.file_name
											}}</a>
										</td>
										<td>
											<a :href="imgURL(image.file_name)" class="m-1" download
												><i class="fa fa-download mr-1"></i>Download</a
											>
											<template v-if="$page.props.auth.user.type == 'admin'">
												<a
													href="void(0);"
													class="m-1"
													@click="deleteImage($event, index, image.id)"
													><i class="fa fa-trash mr-1"></i>Delete</a
												>
											</template>
										</td>
									</tr>
									<!-- <tr>
										<td colspan="3" class="text-center">NO FILES ATTACHED</td>
									</tr> -->
								</tbody>
							</table>
						</div>

						<div class="form-group col-12">
							<button
								type="submit"
								name="submit"
								class="btn btn-success"
								v-if="
									gift_card.admin_approved_at == null ||
									$page.props.auth.user.type == 'admin'
								"
							>
								Save & Update
							</button>
							<button
								v-show="
									$page.props.auth.user.type != 'admin' &&
									form.amount != NULL &&
									gift_card.payment_status != 'Paid' &&
									gift_card.admin_approved_at != null &&
									$page.props.auth.user.type == 'customer'
								"
								type="button"
								name="approve"
								class="btn btn-primary ml-2"
								@click="approve()"
							>
								Accept & Checkout
							</button>
						</div>
					</div>
				</form>

				<fieldset class="border p-4 mb-4 col-12 mt-2">
					<legend class="w-auto">
						<small><b>Comments</b></small>
					</legend>
					<div class="row">
						<div class="form-group col-12">
							<input
								class="form-control"
								v-model="commentForm.message"
								name="message"
								id="cmessage"
								placeholder="Add new comment"
							/>
						</div>
						<div class="col-12">
							<label
								class="btn btn-primary btn-sm float-right"
								@click="saveComment()"
								>Add Comment</label
							>
						</div>
					</div>
					<div
						v-for="comment in comments"
						:key="comment.id"
						class="card"
						style="margin: 10px 0px"
					>
						<div class="card-body" style="padding: 5px 10px; font-size: 16px">
							{{ comment.message }}
						</div>
						<div class="card-footer" style="padding: 5px 10px; font-size: 12px">
							<p class="float-right">
								{{ comment.user.name }} commented at {{ comment.created_at }}
							</p>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="card-footer">
				<h1 class="font-20">Gift Card Samples</h1>
				<div class="row mb-4">
					<div class="col-md-3">
						<h1>Electronic Gift Card - Sample</h1>
						<img :src="sample_electronic_gc" class="custom-image-preview" />
					</div>
					<div class="col-md-3">
						<h1>Physical Gift Card - Sample</h1>
						<img :src="sample_physical_gc" class="custom-image-preview" />
					</div>
				</div>
			</div>
		</div>
	</MainLayout>
	<ImageViewer></ImageViewer>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";
	import ImageViewer from "@/Components/ImageViewer";
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
				sample_electronic_gc: "/images/sample-electronic-gc.jpeg",
				sample_physical_gc: "/images/sample-physical-gc.jpeg",
				service_charges: 0,
				form: this.$inertia.form({
					title: this.gift_card.title,
					type: this.gift_card.type,
					amount: this.gift_card.amount,
					qty: this.gift_card.qty,
					notes: this.gift_card.notes,
					approve: 0,
					status: this.gift_card.status || "",
					gc_total: 0,
					net_total: 0,
					images: [
						{
							image: "",
						},
					],
				}),
				commentForm: this.$inertia.form({
					message: "",
				}),
				comments: this.comments,
			};
		},
		props: {
			gift_card: Object,
			comments: Object,
		},
		methods: {
			submit() {
				this.form.post(this.route("gift-card.edit", this.gift_card.id));
			},
			approve() {
				this.form.approve = 1;
				this.form.post(this.route("gift-card.edit", this.gift_card.id));
			},
			loadComments() {},
			saveComment() {
				if (this.commentForm.message == "") {
					return false;
				}
				this.commentForm.post(
					this.route("gift-card.storeComment", this.gift_card.id)
				);
				this.commentForm.reset();
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
					let r = confirm(
						"Are you sure you want to delete this file? This action cannot be undone."
					);
					if (r) {
						this.form.post(this.route("gift-card.removeImage", { id: id }));
						// axios
						// 	.post(this.route("gift-card.removeImage"), { id: id })
						// 	.then(({ data }) => {
						// 		// this.gift_card.images.splice(index, 1);
						// 	});
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
			calculate_amount() {
				var percentage_amount = 0;
				var final_amount = 0;

				this.form.gc_total = this.form.amount * this.form.qty;
				if (this.form.gc_total > 5) {
					percentage_amount = (this.form.gc_total * 5) / 100;
					this.service_charges = percentage_amount;
					final_amount = this.form.gc_total + percentage_amount;
				} else {
					final_amount = this.form.gc_total + 5;
				}

				if (this.form.type == "PHYSICAL") {
					final_amount = final_amount + 25;
				}

				this.form.net_total = final_amount;
			},
		},
		created() {
			this.calculate_amount();
		},
	};
</script>

<style scoped>
	.custom-image-preview {
		width: 300px;
		height: 300px;
		border-radius: 10px;
	}
	.font-20 {
		font-size: 20px;
	}
	.text-danger {
		color: red;
	}
</style>
