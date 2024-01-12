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
			<div class="card-header">Gift Card</div>
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
							/>
						</div>
						<div class="form-group col-md-6">
							<label for="type">Type</label>
							<select
								class="form-control"
								name="type"
								v-model="form.type"
								id="type"
								required
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
								required
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
								min="1"
								v-model="form.qty"
								@keyup="calculate_amount"
								required
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
						<div class="form-group col-md-12">
							<label for="notes">Notes</label>
							<textarea
								class="form-control"
								name="notes"
								id="notes"
								v-model="form.notes"
								cols="10"
								rows="5"
								required
							></textarea>
						</div>
						<div class="form-group col-12">
							<button type="submit" class="btn btn-primary">
								Send Request
							</button>
						</div>
					</div>
				</form>
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
				sample_electronic_gc: "/images/sample-electronic-gc.jpeg",
				sample_physical_gc: "/images/sample-physical-gc.jpeg",
				service_charges: 0,
				form: this.$inertia.form({
					title: "",
					type: "",
					amount: "",
					qty: 1,
					notes: "",
					gc_total: 0,
					net_total: 0,
				}),
			};
		},
		props: {
			//
		},
		methods: {
			submit() {
				this.form.post(this.route("gift-card.create"));
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
