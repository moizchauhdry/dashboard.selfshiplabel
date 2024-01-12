<template>
	<MainLayout>
		<div class="container">
			<div class="card" style="margin-top: 0px">
				<BreezeValidationErrors />
				<div class="card-header">PayPal Payment</div>
				<div class="card-body">
					<div class="container">
						<div class="row">
							<div
								class="bg-dark border-3 border-warning container mb-3 text-center text-white"
							>
								<h2 style="color: #fff; font-weight: bold; font-size: 25px">
									USD {{ form.amount }}
								</h2>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 offset-md-3">
								<a
									href="javascript:void(0)"
									id="paymentSuccess"
									@click="paymentSuccess"
									class="hidden"
									>pay Success</a
								>
								<div id="smart-button-container">
									<div style="text-align: center">
										<div id="paypal-button-container"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input
			type="text"
			ref="transaction_id"
			id="transaction_id"
			class="hidden"
			@change="getValues()"
		/>
		<textarea
			name="payment_detail"
			id="payment_detail"
			cols="30"
			rows="10"
			class="hidden"
		></textarea>
	</MainLayout>

	<div v-show="overlay === true" class="overlay">
		<div class="overlay__inner">
			<div class="overlay__content"><span class="spinner"></span></div>
		</div>
	</div>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";
	import $ from "jquery";
	import LoaderComponent from "@/Components/LoaderComponent.vue";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
			BreezeValidationErrors,
			LoaderComponent,
		},
		data() {
			return {
				isHidden: true,
				info: "",
				overlay: false,
				response: "",
				response_message: null,
				coupon_status: 2,
				coupon_message: "",
				shipping_address_id: null,
				hasPackage: this.hasPackage,
				form: this.$inertia.form({
					payment_module_type: this.dataResponse.payment_module_type,
					payment_module_id: this.dataResponse.payment_module_id,
					amount: this.dataResponse.amount,
					first_name: this.dataResponse.first_name,
					last_name: this.dataResponse.last_name,
					country: this.dataResponse.country,
					city: this.dataResponse.city,
					state: this.dataResponse.state,
					zip: this.dataResponse.zip,
					address: this.dataResponse.address,
					phone_no: this.dataResponse.phone_no,
					email: this.dataResponse.email,
					card_no: this.dataResponse.card_no,
					month: this.dataResponse.month,
					year: this.dataResponse.year,
					cvv: this.dataResponse.cvv,
					coupon_code: this.dataResponse.coupon_code,
					coupon_code_id: this.dataResponse.coupon_code_id,
					discount: this.dataResponse.discount,
					shipping_address_id: this.dataResponse.shipping_address_id,
				}),
			};
		},
		props: {
			dataResponse: Object,
			status: Object,
			hasPackage: Object,
			shippingAddress: Object,
			processingFeePayPal: String,
		},
		watch: {
			form: {
				handler(val) {
					console.log(val);
				},
				deep: true,
			},
		},
		mounted() {
			var formdata = this.form;
			this.coupon_message = "";
			this.coupon_status = 2;
			this.initPayPalButton(
				this.form.amount,
				this.route("payment.payPalSuccess"),
				this.form,
				this.axios
			);
		},
		methods: {
			responseFromSubmit() {
				let data = this.response.data;
				if (data.status === 0) {
					this.response_message = data.message;
					this.overlay = false;
				} else {
					location.href = this.route(
						"payments.PaymentSuccess",
						data.payment_id
					);
				}
			},
			initPayPalButton(amount, route, formData, axios) {
				paypal
					.Buttons({
						style: {
							shape: "rect",
							color: "gold",
							layout: "vertical",
							label: "paypal",
						},

						createOrder: function (data, actions) {
							return actions.order.create({
								purchase_units: [
									{ amount: { currency_code: "USD", value: amount } },
								],
							});
						},

						onApprove: function (data, actions) {
							return actions.order.capture().then(function (orderData) {
								// Full available details
								console.log(
									"Capture result",
									orderData,
									JSON.stringify(orderData, null, 2)
								);
								document.getElementById("transaction_id").value = orderData.id;
								document.getElementById("payment_detail").value =
									JSON.stringify(orderData, null, 2);
								document.getElementById("paymentSuccess").click();
								// Show a success message within this page, e.g.
								const element = document.getElementById(
									"paypal-button-container"
								);
								element.innerHTML = "";
								element.innerHTML = "<h3>Thank you for your payment!</h3>";

								// Or go to another URL:  actions.redirect('thank_you.html');
							});
						},

						onError: function (err) {
							console.log(err);
						},

						onSuccess: function (order) {
							axios
								.post(this.route("payment.payPalSuccess"), formData)
								.then((response) => (this.response = response))
								.finally(() => this.responseFromSubmit());
						},
					})
					.render("#paypal-button-container");
			},
			paymentSuccess() {
				this.overlay = true;
				this.form.transaction_id = $("#transaction_id").val();
				this.form.payment_detail = $("#payment_detail").val();
				axios
					.post(this.route("payment.payPalSuccess"), this.form)
					.then((response) => (this.response = response))
					.finally(() => this.responseFromSubmit());
			},
			getValues() {
				// console.log(document.getElementById('transaction_id'));
			},
		},
	};
</script>

<style scoped>
	.overlay {
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		position: fixed;
		background: rgba(0, 0, 0, 0.5);
	}

	.overlay__inner {
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		position: absolute;
	}

	.overlay__content {
		left: 50%;
		position: absolute;
		top: 50%;
		transform: translate(-50%, -50%);
	}

	.spinner {
		width: 75px;
		height: 75px;
		display: inline-block;
		border-width: 2px;
		border-color: rgba(255, 255, 255, 0.05);
		border-top-color: #fff;
		animation: spin 1s infinite linear;
		border-radius: 100%;
		border-style: solid;
	}

	@keyframes spin {
		100% {
			transform: rotate(360deg);
		}
	}
</style>
