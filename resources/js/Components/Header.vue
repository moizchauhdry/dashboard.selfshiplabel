<template>
	<header id="header" class="header-effect-shrink"
		data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 120, 'stickyHeaderContainerHeight': 85}">
		<div class="header-body border-top-0">
			<div class="container">
				<div class="header-row">
					<div class="header-column justify-content-between">
						<div class="header-row">
							<div class="d-flex align-items-center w-100"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="header-container container">
				<div class="header-row">
					<div class="header-column">
						<div class="header-row">
							<div class="header-logo">
								<a href="http://shippingxps.com/">
									<img alt="shippingxps" width="162" height="33" src="/theme/img/logo.png" />
								</a>
							</div>
						</div>
					</div>
					<div class="header-column justify-content-end w-100">
						<div class="header-row">
							<div class="header-nav header-nav-links order-2 order-lg-1">
								<div
									class="header-nav-main header-nav-main-square header-nav-main-text-capitalize header-nav-main-effect-1 header-nav-main-sub-effect-1">
									<nav class="collapse">
										<ul class="nav nav-pills" id="mainNav">
											<li>
												<inertia-link class="nav-link dropdown-toggle" href="/auctions">Auctions</inertia-link>
											</li>
											<li v-if="$page.props.auth.user" class="nav-link">
												<a class="nav-link" href="/dashboard">Dashboard</a>
											</li>
											<li v-else class="nav-link">
												<a class="nav-link" href="/register">Register</a>
											</li>
										</ul>
									</nav>
								</div>
							</div>
						</div>
					</div>

					<div
						class="header-column header-column-search justify-content-end align-items-center d-flex w-auto flex-row">
						<inertia-link v-if="!$page.props.auth.user" :href="route('login')"
							class="btn btn-dark custom-btn-style-1 font-weight-semibold text-3-5 btn-px-3 py-2 ws-nowrap ms-4 d-none d-lg-block"
							data-cursor-effect-hover="plus" data-cursor-effect-hover-color="light">
							<span>Login</span>
						</inertia-link>

						<inertia-link v-else
							class="btn btn-dark custom-btn-style-1 font-weight-semibold text-3-5 btn-px-3 py-2 ws-nowrap ms-4 d-none d-lg-block"
							data-cursor-effect-hover="plus" data-cursor-effect-hover-color="light" :href="route('logout')"
							method="post" as="button">
							<span>Logout</span>
						</inertia-link>

						<button class="btn header-btn-collapse-nav" @click="toogleCollapse()" data-bs-toggle="collapse"
							data-bs-target=".header-nav-main nav">
							<i class="fas fa-bars"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</header>
</template>
<style scoped>
.small-top p {
	color: #fff;
	font-weight: 700;
	padding: 0px 25px;
	flex: 60%;
}

.small-top p a {
	color: #fff;
	font-weight: 700;
	background-color: #ee5050;
	border-radius: 10px;
	padding: 4px 25px;
	text-align: center;
}

.w-40 a {
	width: 50px;
	color: #fff;
	font-weight: 700;
}
</style>
<script>
export default {
	name: "Header",
	props: ["href", "active"],
	data() {
		return {
			form: this.$inertia.form({
				search: "",
			}),
		};
	},
	created() {
		window.addEventListener("resize", this.myEventHandler);
	},
	destroyed() {
		window.removeEventListener("resize", this.myEventHandler);
	},
	methods: {
		myEventHandler(e) {
			if (window.innerWidth > 991) {
				document
					.querySelector(".header-nav-main nav")
					.classList.add("collapse");
			}
		},
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
		toogleCollapse() {
			var nav = document.querySelector(".header-nav-main nav");
			if (nav.classList.contains("collapse")) {
				nav.classList.remove("collapse");
			} else {
				nav.classList.add("collapse");
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
