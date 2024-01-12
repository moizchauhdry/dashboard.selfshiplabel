<template>
	<footer
		id="footer"
		class="border-top-0 m-0"
		style="
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
		"
	>
		<div class="container pt-3">
			<div class="row pt-3">
				<div class="col-lg-4 mb-4 mb-lg-0">
					<a href="/" class="text-decoration-none">
						<img
							src="/theme/img/logo.png"
							class="img-fluid mb-4"
							width="123"
							height="33"
							alt=""
						/>
					</a>
					<ul class="list list-icons list-icons-lg">
						<li style="font-size: 15px" class="pt-1 pb-1 d-flex mb-0 px-0">
							<a
								href="tel:+16572017881"
								class="p-0 mr-0 text-color-light font-weight-semibold"
								>+1-657-210-1801</a
							>
						</li>
						<li style="font-size: 15px" class="pt-1 pb-1 d-flex mb-0 px-0">
							<a
								href="mailto:info@shippingxps.com"
								class="p-0 mr-0 text-color-light font-weight-semibold"
								>info@shippingxps.com</a
							>
						</li>
						<li
							style="font-size: 15px"
							class="pt-1 pb-1 d-flex mb-0 font-weight-semibold text-color-light px-0"
						>
							15102 Eden st Westminster California 92683
						</li>
					</ul>
					<ul
						class="social-icons social-icons-clean social-icons-clean-with-border social-icons-medium social-icons-icon-light mb-3"
					>
						<li class="social-icons-facebook">
							<a
								href="https://www.facebook.com/Shippingxps"
								target="_blank"
								title="Facebook"
								data-cursor-effect-hover="fit"
								><i class="fab fa-facebook-f"></i
							></a>
						</li>
						<li class="social-icons-twitter mx-2">
							<a
								href="http://www.twitter.com/"
								target="_blank"
								title="Twitter"
								data-cursor-effect-hover="fit"
								><i class="fab fa-twitter"></i
							></a>
						</li>
						<li class="social-icons-instagram">
							<a
								href="https://instagram.com/shippingxps"
								target="_blank"
								title="instagram"
								data-cursor-effect-hover="fit"
								><i class="fab fa-instagram"></i
							></a>
						</li>
						<li class="social-icons-youtube">
							<a
								href="http://www.youtube.com/"
								target="_blank"
								title="youtube"
								data-cursor-effect-hover="fit"
								><i class="fab fa-youtube"></i
							></a>
						</li>
					</ul>
				</div>
				<div class="col-lg-3 offset-md-2">
					<h4 class="font-weight-bold text-4 mb-2">COMPANY</h4>
					<ul class="list list-icons list-icons-sm">
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'about_us')"
								class="link-hover-style-1 ms-1"
								>About Us</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'contact_us')"
								class="link-hover-style-1 ms-1"
								>Contact Us</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'terms_and_conditions')"
								class="link-hover-style-1 ms-1"
								>Terms & Conditions</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('shipping-calculator')"
								class="link-hover-style-1 ms-1"
								>Shipping Calculator</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('pricing')"
								class="link-hover-style-1 ms-1"
								>Services & Pricing</inertia-link
							>
						</li>
					</ul>
				</div>
				<div class="col-lg-3">
					<h4 class="font-weight-bold text-4 mb-2">SERVICES</h4>
					<ul class="list list-icons list-icons-sm">
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'shop-for-me')"
								class="link-hover-style-1 ms-1"
								>Shop For Me</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'for-pick-up')"
								class="link-hover-style-1 ms-1"
								>For Pick up</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'additional-requests')"
								class="link-hover-style-1 ms-1"
								>Additional Requests</inertia-link
							>
						</li>
						<li>
							<i class="fas fa-angle-right text-color-default"></i>
							<inertia-link
								:href="route('page-show', 'insurance')"
								class="link-hover-style-1 ms-1"
								>Insurance</inertia-link
							>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
</template>
<style scoped>
	#footer ul li a {
		font-size: 14.5px;
		font-weight: 600;
	}
	.destinations h5 {
		font-size: 16px;
		font-weight: 500;
	}
	#footer .badge {
		font-size: 14.5px;
	}
	.margin-right-20 {
		margin-right: 4px;
	}
	.social-icons li {
		padding: 10px 10px !important;
	}
</style>
<script>
	export default {
		props: ["showTicker"],
		created() {
			console.log(this.showTicker);
		},
		mounted() {
			this.initTawkTo();
		},
		methods: {
			initTawkTo() {
				axios.get("/checkAuth-user").then(({ data }) => {
					if (data.init) {
						var Tawk_API = Tawk_API || {},
							Tawk_LoadStart = new Date();
						(function () {
							var s1 = document.createElement("script"),
								s0 = document.getElementsByTagName("script")[0];
							s1.async = true;
							s1.src =
								"https://embed.tawk.to/617e40def7c0440a5920c3c5/1fjaiqq44";
							s1.charset = "UTF-8";
							s1.setAttribute("crossorigin", "*");
							s0.parentNode.insertBefore(s1, s0);
						})();
					}
				});
			},
		},
	};
</script>
