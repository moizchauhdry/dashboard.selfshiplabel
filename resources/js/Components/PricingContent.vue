<template>
	<div class="container mb-4 mt-6">
		<div class="row">
			<div class="col">
				<h3
					class="text-5 text-center text-lg-5 text-xl-5 line-height-3 text-transform-none font-weight-semibold mb-4 mb-lg-5"
				>
					Via Trusted Shipping Partners
				</h3>
				<div class="row col-md-8 offset-md-2">
					<div class="col text-center">
						<img
							class="d-inline-block img-fluid"
							src="/theme/img/demos/business-consulting-3/partner-02.png"
							alt=""
						/>
						<p class="text-0 font-weight-medium text-center mt-2">
							DHL Express
						</p>
					</div>
					<div class="col text-center">
						<img
							class="d-inline-block img-fluid"
							src="/theme/img/demos/business-consulting-3/partner-03.png"
							alt=""
						/>
						<p class="text-0 font-weight-medium text-center mt-2">
							FedEx International
						</p>
					</div>
					<div class="col text-center">
						<img
							class="d-inline-block img-fluid"
							src="/theme/img/demos/business-consulting-3/partner-04.png"
							alt=""
						/>
						<p class="text-0 font-weight-medium text-center mt-2">
							UPS International
						</p>
					</div>
					<!-- <div class="col text-center">
						<img
							class="d-inline-block img-fluid"
							src="/theme/img/demos/business-consulting-3/partner-05.png"
							alt=""
						/>
						<p class="text-0 font-weight-medium text-center mt-2">Aramex</p>
					</div> -->
				</div>
				<p class="font-weight-medium text-center mt-3">
					* Service offerings may change depending on the destination country.
					Not all carriers offer services to all countries
				</p>
			</div>
		</div>
	</div>

	<!-- <section class="shiping-rates mt-5 mb-4">
		<div class="col">
			<div class="shiping-rates-box bg-dark text-center p-5">
				<div class="container">
					<h3
						class="text-5 text-white text-center text-lg-5 text-xl-5 line-height-3 text-transform-none font-weight-semibold mb-4 mb-lg-5 appear-animation"
						data-appear-animation="fadeInUpShorter"
						data-appear-animation-delay="250"
					>
						Special Services Pricing And Fees
					</h3>
					<div class="shiping-rates-content">
						<p class="text-4 font-weight-medium text-white">
							Save big with discounted shipping rates from the USA & UK to more
							than 120 destinations worldwide. Get your shipping address for
							free and shop online! Sign up to enjoy a smooth international
							shipping experience.
						</p>
						<a
							href="/register"
							class="btn btn-primary custom-btn-style-1 font-weight-medium text-5-5 btn-px-3 mt-4 py-2 ws-nowrap ms-4 text-uppercase d-inline-block"
							data-cursor-effect-hover="plus"
							data-cursor-effect-hover-color="light"
							><span>Sign up</span></a
						>
					</div>
				</div>
			</div>
		</div>
	</section> -->
</template>
<style scoped></style>
<script>
	export default {
		props: ["href", "active"],
		data() {
			return {
				form: this.$inertia.form({
					search: "",
				}),
			};
		},
		methods: {
			submit() {
				if (this.form.search == "") {
					document.getElementById("headerSearch").focus();
					return;
				} else {
					this.$inertia.get(
						this.route("search-stock-company", { keyword: this.form.search })
					);
				}
			},
		},
		computed: {
			classes() {
				return this.active
					? "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
					: "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
			},
		},
	};
</script>
