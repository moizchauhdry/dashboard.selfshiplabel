<template>
	<section
		class="section section-height-3 hero-img section-with-shape-divider position-relative border-0 m-0"
	>
		<img
			src="/theme/img/home-immg.png"
			class="img-fluid position-absolute top-0 right-16 top-10 d-none d-md-block"
			alt=""
		/>
		<div class="container pb-5 pb-xl-0 mt-md-3 mb-5 hero-title">
			<div class="row">
				<div class="col-sm-12 col-md-6 order-2 order-md-1">
					<h1
						style="color: #2a007c"
						class="clearfix custom-ws-mobile-wrap line-height-1 font-weight-bold line-height-1 mb-3 pb-2 text-9 wrap"
					>
						{{ promotionalMessage }}
					</h1>
					<h2 class="main-info-badge mb-3" style="font-size: 2em">
						Shop All Over the World
					</h2>
					<p class="line-height-1 mb-3 text-dark" style="font-size: 2em">
						We Will Shop and Ship to Your Doorstep
					</p>
					<p class="mb-2 text-dark" style="font-size: 2em">
						You Can Also Shop for Gift Cards
					</p>
					<div class="row pb-5 mb-5">
						<div class="col-md-7 col-xl-5 pb-5 pb-xl-0 mb-5">
							<strong
								class="d-block font-weight-semibold line-height-4 mb-3 mt-4 pb-1 text-7 text-color-dark ws-nowrap"
								>Shop & Ship From the USA</strong
							>
							<p class="mb-4 mt-3 text-6 ws-nowrap" style="font-size: 20px">
								We Ship Worldwide!
							</p>
							<a
								:href="route('register')"
								class="btn btn-dark custom-btn-style-1 font-weight-semibold text-3-5 mb-4 btn-px-3 py-2 ws-nowrap ms-4 d-inline-block"
								><span>Get Started – It’s Free</span></a
							>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 order-1 order-md-2"></div>
			</div>
		</div>
		<div class="shape-divider shape-divider-bottom" style="height: 212px">
			<div
				class="shape-divider-horizontal-animation shape-divider-horizontal-animation-to-left"
			>
				<svg
					version="1.1"
					xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink"
					x="0px"
					y="0px"
					viewBox="0 0 1920 212"
					preserveAspectRatio="xMinYMin"
				>
					<polygon
						fill="#F3F3F3"
						points="0,75 479,161 1357,28 1701,56 1920,26 1920,213 0,212 "
					/>
					<polygon
						fill="#FFFFFF"
						points="0,91 481,177 1358,44 1702,72 1920,42 1920,212 0,212 "
					/>
					<rect y="200" width="100%" height="800" style="fill: #fff" />
				</svg>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<h1 style="font-size: 25px; font-weight: 800" class="text-center">
				PLACE ORDER ON AMAZON, USA MALL
			</h1>
			<div class="row mt-3 mb-3">
				<div class="col-sm-12 col-md-4">
					<div class="row">
						<div class="col-12 text-center pb-4 pt-2">
							<img
								style="height: 120px; margin: 0 auto"
								src="svg/shipping.svg"
								alt=""
							/>
						</div>
						<div class="col-12 text-center">
							ENTER YOUR SHIPPING XPS MAILING ADDRESS
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="row">
						<div class="col-12 text-center pb-4 pt-2">
							<img
								style="height: 120px; margin: 0 auto"
								src="svg/package.svg"
								alt=""
							/>
						</div>
						<div class="col-12 text-center">
							WE WILL RECIEVE YOUR ORDER AND WILL SHIP TO YOU
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="row">
						<div class="col-12 text-center pb-4 pt-2">
							<img
								style="height: 120px; margin: 0 auto"
								src="svg/ship-truck.svg"
								alt=""
							/>
						</div>
						<div class="col-12 text-center">
							YOUR ORDER DELIVERED TO YOUR DOOR STEP
						</div>
					</div>
				</div>
			</div>
			<div class="container p-md-2">
				<div class="row mt-4" style="background-color: #dcdcdc">
					<div class="col-sm-12 col-md-4 p-0">
						<img
							style="height: 300px"
							class="img-fluid text-center"
							src="/theme/img/posters/poster-1.jpeg"
							alt=""
						/>
					</div>
					<div class="col-sm-12 col-md-8 p-3 p-md-3" style="align-self: center">
						<h2 style="font-size: 35px; font-weight: 800; line-height: 2">
							GET YOUR USA ADDRESS
						</h2>
						<p style="line-height: 1.5; font-size: 22px">
							Sign up with ShippingXPS and receive your free USA address
							immediately. Once you register with ShippingXPS, you will have
							your own suite in our warehouse.
						</p>
					</div>
				</div>
				<div class="row mt-4" style="background-color: #dcdcdc">
					<div
						class="col-sm-12 col-md-8 p-3 p-md-3 order-2 order-md-1"
						style="align-self: center"
					>
						<h2 style="font-size: 35px; font-weight: 800; line-height: 2">
							START SHOPPING ONLINE FROM the USA
						</h2>
						<p style="line-height: 1.5; font-size: 22px">
							Shop online from multiple online retailers and take advantage of
							great deals from top USA brands. Use your ShippingXPS address when
							you checkout. We will notify you when we have received your
							packages at our warehouse. If a store doesn’t accept your credit
							card, use our ShopForMe service.
						</p>
					</div>
					<div class="col-sm-12 col-md-4 p-0 order-1 order-md-2">
						<img
							style="height: 300px"
							class="img-fluid text-sm-center float-right"
							src="/theme/img/posters/poster-2.jpeg"
							alt=""
						/>
					</div>
				</div>
				<div class="row mt-4" style="background-color: #dcdcdc">
					<div class="col-sm-12 col-md-4 p-0">
						<img
							style="height: 300px"
							class="img-fluid text-center"
							src="/theme/img/posters/poster-3.jpeg"
							alt=""
						/>
					</div>
					<div class="col-sm-12 col-md-8 p-3 p-md-3" style="align-self: center">
						<h2 style="font-size: 35px; font-weight: 800; line-height: 2">
							RECEIVING YOUR PACKAGES
						</h2>
						<p style="line-height: 1.5; font-size: 22px">
							Once we receive your packages, we will consolidate your packages
							into one box and ship them to your home address. Avoid unnecessary
							dimensional weight with our repacking service and save even more
							with ShippingXPS.
						</p>
					</div>
				</div>
				<div class="row mt-4" style="background-color: #dcdcdc">
					<div
						class="col-sm-12 col-md-8 p-3 p-md-3 order-2 order-md-1"
						style="align-self: center"
					>
						<h2 style="font-size: 35px; font-weight: 800; line-height: 2">
							SHIPPING YOUR PACKAGES HOME
						</h2>
						<p style="line-height: 1.5; font-size: 22px">
							We will send your packages to your location with the carrier method you choose from a variety
							of our trustworthy partners. Once your packages leave ShippingXPS, packages will be
							delivered as little as 2-4 days worldwide.
						</p>
					</div>
					<div class="col-sm-12 col-md-4 p-0 order-1 order-md-2">
						<img
							style="height: 300px"
							class="img-fluid text-sm-center float-right"
							src="/theme/img/posters/poster-4.jpeg"
							alt=""
						/>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="container-fluid mb-4">
		<div class="row" style="margin-top: 20px">
			<div class="col">
				<h3
					class="text-5 text-center text-lg-5 text-xl-5 line-height-3 text-transform-none font-weight-semibold mb-4 mb-lg-5"
				>
					Via Trusted Shipping Partners
				</h3>
				<div class="row">
					<!--					<div class="col text-center">
						<img class="d-inline-block img-fluid" src="/theme/img/demos/business-consulting-3/partner-01.png" alt="" />
						<p class="text-0 font-weight-medium text-center mt-2">Postal Service</p>
					</div>-->
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
					<div class="col text-center">
						<img
							class="d-inline-block img-fluid"
							src="/theme/img/demos/business-consulting-3/partner-05.png"
							alt=""
						/>
						<p class="text-0 font-weight-medium text-center mt-2">Aramex</p>
					</div>
					<!--					<div class="col text-center">
						<img class="d-inline-block img-fluid" src="/theme/img/demos/business-consulting-3/partner-01.png" alt="" />
						<p class="text-0 font-weight-medium text-center mt-2">Ship7 Delivery</p>
					</div>-->
				</div>
				<p class="font-weight-medium text-center mt-3">
					* * Service offerings may change depending on the destination country.
					Not all carriers offer services to all countries.
				</p>
			</div>
		</div>
	</div>
</template>
<style scoped></style>
<script>
	export default {
		props: ["href", "active", "promotionalMessage"],
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
