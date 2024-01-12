<template>
	<MainLayout>
		<div class="container">
			<div class="card" style="margin-top: 0px">
				<div class="card-header">Create Coupon</div>
				<div class="card-body stock-subscription-form">
					<form @submit.prevent="submit" enctype="multipart/form-data">
						<div class="order-form">
							<breeze-validation-errors class="mb-4" />
							<flash-messages class="mb-4" />
							<div class="container">
								<div class="row">
									<div class="col-md-6 form-group">
										<breeze-label for="name" value="Title" />
										<input v-model="form.name" name="name" id="name" type="text" class="form-control"
											required />
									</div>
									<div class="col-md-3 form-group">
										<breeze-label for="discount" value="Discount Amount" />
										<input v-model="form.discount" name="discount" id="discount" type="number" step="0.01"
											class="form-control" required />
									</div>
									<div class="col-md-3 form-group">
										<breeze-label for="Code" value="Code" />
										<input v-model="form.code" name="code" @input="preventSpace($event)" id="code"
											type="text" class="form-control" required />
									</div>
									<!-- <div class="col-md-4 form-group">
										<breeze-label for="" value="Expiry Date" />
										<Datepicker v-model="form.expiry_date" range></Datepicker>
									</div> -->
									<div class="col-12">
										<button class="btn btn-primary float-right" type="submit" v-on:submit="submit">
											Submit
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
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
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		BreezeValidationErrors,
		Datepicker,
	},
	data() {
		return {
			form: this.$inertia.form({
				name: "",
				discount: "",
				code: "",
				expiry_date: "",
			}),
		};
	},
	props: {
		coupons: Object,
	},
	methods: {
		submit() {
			this.form.post(this.route("coupon.store"));
		},
		preventSpace(event) {
			if (event.keyCode == 32 && event.target == document.body) {
				event.preventDefault();
			}
		},
	},
};
</script>

<style scoped></style>
