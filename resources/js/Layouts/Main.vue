<template>
	<header class="navbar navbar-light sticky-top bg-dark flex-md-nowrap p-0 shadow bg-light" style="" id="header2">
		<a href="http://shippingxps.com" style="padding: 5px 10px" target="_blank">
			<img alt="Porto" width="100" height="35" src="/theme/img/logo.png" />
		</a>
		<nav class="navbar navbar-expand-lg navbar-light bg-light" style="display: none">
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="#">Arrived <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Labeled </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Shipped</a>
					</li>
				</ul>
			</div>
		</nav>
		<ul class="flex-md-row inline-flex navbar-nav px-3">
			<inertia-link :href="route('notifications')"
				class="notifications mb-2 mr-1 inline-flex i+tems-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
				Notifications
				<span class="badge badge-danger ml-1" v-if="notification_count > 0">{{ notification_count }}</span>
			</inertia-link>
			<breeze-dropdown align="right">
				<template #trigger>
					<span class="inline-flex rounded-md">
						<button type="button"
							class="mb-2 inline-flex i+tems-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
							{{ $page.props.auth.user.name }}
							<svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
								fill="currentColor">
								<path fill-rule="evenodd"
									d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
									clip-rule="evenodd" />
							</svg>
						</button>
					</span>
				</template>
				<template #content>
					<breeze-dropdown-link :href="route('notifications')" as="button">
						Notifications<span class="badge badge-danger ml-1" v-if="notification_count > 0">
							{{ notification_count }}</span>
					</breeze-dropdown-link>
					<breeze-dropdown-link v-if="$page.props.auth.user.type == 'customer'" :href="route('profile')"
						as="button">My Profile
					</breeze-dropdown-link>
					<breeze-dropdown-link :href="route('update-password')" as="button">
						Update Password
					</breeze-dropdown-link>
					<breeze-dropdown-link :href="route('logout')" method="post" as="button">
						Logout
					</breeze-dropdown-link>
				</template>
			</breeze-dropdown>
		</ul>
	</header>
	<div class="row">
		<div class="container-fluid">
			<!-- SIDEBAR -->
			<div class="sidebar" id="main_sidebar" v-if="main_sidebar == 1">
				<div class="sidebar-menu mt-sm-5 mt-md-0">
					<inertia-link class="nav-link" :href="route('dashboard')"
						:class="{ active: route().current('dashboard') }" :active="route().current('dashboard')">
						<i class="fas fa-home"></i><span>Dashboard</span>
					</inertia-link>
					<button v-if="$page.props.auth.user.type == 'admin' || $page.props.auth.user.type == 'manager'"
						class="accordion">Users</button>
					<div v-if="$page.props.auth.user.type == 'admin' || $page.props.auth.user.type == 'manager'"
						class="accordion-content">
						<inertia-link
							v-if="$page.props.auth.user.type == 'admin' && $page.props.auth.user.type != 'manager'"
							class="nav-link" :href="route('manage-users')"
							:class="{ active: route().current('manage-users') || route().current('create-users') }"
							:active="route().current('manage-users')">
							<i class="fas fa-user-tie"></i><span>Manage Users</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type != 'customer'" class="nav-link"
							:href="route('customers.index')" :class="{
								active: route().current('customers.index') || route().current('customers.edit') || route().current('customers.detail')
							}" :active="route().current('customers.index')">
							<i class="fas fa-users"></i><span>Manage Customers</span>
						</inertia-link>
					</div>

					<template v-if="$page.props.auth.user.type == 'customer'">
						<button class="accordion">Address</button>
						<div class="accordion-content">
							<!-- <inertia-link
							class="nav-link"
							:href="route('address.suite')"
							:class="{ active: route().current('address.suite') }"
							:active="route().current('address.suite')"
						>
							<i class="fas fa-address-book"></i>
							<span>Mailing Addresses</span>
						</inertia-link> -->
							<inertia-link class="nav-link" :href="route('addresses')" :class="{
								active:
									route().current('addresses') ||
									route().current('address.create') ||
									route().current('address.edit'),
							}" :active="route().current('addresses')">
								<i class="fas fa-address-book"></i>
								<span>Shipping Address</span>
							</inertia-link>
						</div>
					</template>

					<button class="accordion">Packages</button>
					<div class="accordion-content">
						<inertia-link class="nav-link" v-if="$page.props.auth.user.type == 'admin'"
							:href="route('shop-for-me.index')" :class="{
								active:
									route().current('shop-for-me.index') ||
									route().current('shop-for-me.edit') ||
									route().current('shop-for-me.show'),
							}" :active="route().current('shop-for-me.index')">
							<i class="fas fa-shopping-cart"></i>
							<span>Shopping</span>
						</inertia-link>

						<inertia-link class="nav-link" :href="route('packages.index')" :class="{
							active:
								route().current('packages.index') ||
								route().current('packages.show') ||
								route().current('packages.custom'),
						}" :active="route().current('packages.index')">
							<i class="fas fa-box"></i>
							<span>Packages</span>
						</inertia-link>

						<inertia-link class="nav-link" :href="route('custom-form.index')"
							v-if="$page.props.auth.user.type == 'admin'"
							:class="{ active: route().current('custom-form.index') || route().current('custom-form.create') }"
							:active="route().current('custom-form.index')"><i class="fas fa-print"></i><span>Custom
								Form</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type != 'manager'" class="nav-link"
							:href="route('payments.getPayments')"
							:class="{ active: route().current('payments.getPayments') }"
							:active="route().current('payments.getPayments')">
							<i class="fas fa-money-check-alt"></i>
							<span>Payments</span>
						</inertia-link>

						<inertia-link class="nav-link" v-if="$page.props.auth.user.type == 'customer'"
							:href="route('shop-for-me.index')" :class="{
								active:
									route().current('shop-for-me.index') ||
									route().current('shop-for-me.edit') ||
									route().current('shop-for-me.show'),
							}" :active="route().current('shop-for-me.index')">
							<i class="fas fa-list"></i>
							<span>My Shopping List</span>
						</inertia-link>

						<inertia-link class="nav-link" v-if="$page.props.auth.user.type == 'customer'"
							:href="route('shop-for-me.create')" :class="{ active: route().current('shop-for-me.create') }"
							:active="route().current('shop-for-me.create')">
							<i class="fas fa-shopping-cart"></i>
							<span>Shop for me</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link"
							:href="route('coupon.index')" :class="{
								active:
									route().current('coupon.index') ||
									route().current('coupon.create') ||
									route().current('coupon.edit'),
							}" :active="route().current('coupon.index')">
							<i class="fas fa-tag"></i>
							<span>Coupons</span>
						</inertia-link>
					</div>

					<button class="accordion">Services</button>
					<div class="accordion-content">
						<inertia-link v-if="$page.props.auth.user.type != 'manager'" class="nav-link"
							:href="route('insurance.index')" :class="{
								active:
									route().current('insurance.index') ||
									route().current('insurance.show') ||
									route().current('insurance.create'),
							}" :active="route().current('insurance.index')">
							<i class="fas fa-boxes"></i>
							<span>Insurance</span>
						</inertia-link>

						<inertia-link class="nav-link" v-if="$page.props.auth.user.type != 'manager'"
							:href="route('additional-request.index')" :class="{
								active:
									route().current('additional-request.index') ||
									route().current('additional-request.create') ||
									route().current('additional-request.edit'),
							}" :active="route().current('additional-request')">
							<i class="fas fa-truck"></i>
							<span>Additional Request</span>
						</inertia-link>

						<inertia-link class="nav-link" v-if="$page.props.auth.user.type != 'manager'"
							:href="route('gift-card.index')" :class="{
								active:
									route().current('gift-card.index') ||
									route().current('gift-card.create') ||
									route().current('gift-card.edit'),
							}" :active="route().current('gift-card')">
							<i class="fas fa-gift"></i>
							<span>Gift Card</span>
						</inertia-link>

						<inertia-link class="nav-link" :href="route('dashboard.shipping-calculator.index')" :class="{
							active: route().current('dashboard.shipping-calculator.index'),
						}" :active="route().current('dashboard.shipping-calculator.index')">
							<i class="fas fa-calculator"></i>
							<span>Shipping Calculator</span>
						</inertia-link>
						<inertia-link class="nav-link" :href="route('auctions.listing')" :class="{
							active: route().current('auctions.listing'),
						}" :active="route().current('auctions.listing')">
							<i class="fas fa-gavel"></i>
							<span>Auctions</span>
						</inertia-link>
					</div>

					<button v-if="$page.props.auth.user.type == 'admin' ||
						$page.props.auth.user.type == 'manager'
						" class="accordion">
						Manage
					</button>

					<div v-if="$page.props.auth.user.type == 'admin' ||
						$page.props.auth.user.type == 'manager'
						" class="accordion-content">
						<inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link"
							:href="route('services')" :class="{
								active:
									route().current('services') ||
									route().current('services.create') ||
									route().current('services.edit'),
							}" :active="route().current('services')">
							<i class="fas fa-shipping-fast"></i>
							<span>Services</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type == 'admin' &&
							$page.props.auth.user.type != 'manager'
							" class="nav-link" :href="route('warehouses')" :class="{
		active:
			route().current('warehouses') ||
			route().current('warehouses.create') ||
			route().current('warehouses.edit'),
	}" :active="route().current('warehouses')">
							<i class="fas fa-warehouse"></i>
							<span>Warehouses</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link"
							:href="route('store.index')" :class="{
								active:
									route().current('store.index') ||
									route().current('store.create') ||
									route().current('store.edit'),
							}" :active="route().current('store.index')">
							<i class="fas fa-store-alt"></i>
							<span>Stores</span>
						</inertia-link>

						<inertia-link v-if="$page.props.auth.user.type == 'admin'" class="nav-link"
							:href="route('settings')" :class="{ active: route().current('settings') }"
							:active="route().current('settings')">
							<i class="fas fa-file-invoice"></i>
							<span>Fee & Charges</span>
						</inertia-link>
					</div>
				</div>
			</div>
			<div class="toggle-side" @click="toggleSideBar()">
				<i class="fa fa-bars"></i>
			</div>
			<!-- SIDEBAR -->

			<div role="main" class="main-section">
				<div class="d-flex justify-content-center">
					<FlashMessages />
				</div>
				<div class="col-md-12">
					<Announcement></Announcement>
					<slot />
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
.table {
	overflow-y: scroll;
}

@media (max-width: 1299px) {
	.main-section {
		margin-left: 10px;
		margin-top: 25px;
		padding-left: 10px;
	}

	.toggle-side {
		margin-top: 0px;
		padding: 10px 15px;
		background: #272c33 !important;
		color: #fff;
		position: absolute;
		z-index: 1;
		font-size: 17px;
		right: 0px;
		/*left: 12px;*/
	}

	.sidebar {
		/* display: none; */
		z-index: 1;
	}
}

@media (min-width: 1300px) {
	.main-section {
		margin-left: 240px;
		margin-top: 25px;
	}

	.toggle-side {
		display: none;
	}

	.sidebar {
		/* display: block !important; */
		z-index: 0;
	}
}

@media (max-width: 768px) {
	.sidebar-menu {
		margin-top: 20px !important;
	}
}

.badge-danger {
	border-radius: 50px !important;
}

.navbar {
	background: var(--white);
	box-shadow: var(--box-shadow);
}

.sidebar-toggle .fas {
	cursor: pointer;
}

.navbar .logo {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}

.navbar-brand {
	padding-top: 0;
	font-size: initial;
}

.navbar-nav .nav-link span {
	color: var(--text);
	font-size: 14px;
	font-weight: 700;
	margin-right: 5px;
}

.dropdown-toggle::after {
	border: none;
	margin: 0;
}

.dropdown-menu.show::before {
	content: "";
	width: 19px;
	height: 19px;
	border-bottom: 9px solid #fff;
	border-top: 9px solid transparent;
	border-left: 9px solid transparent;
	border-right: 9px solid transparent;
	position: fixed;
	top: -17px;
	left: 112px;
	z-index: 3;
}

.dropdown-menu[data-bs-popper] {
	left: -52px;
	margin-top: 1.6rem;
}

.dropdown-menu {
	border: 1px solid #e5e5e5;
	padding: 5px;
}

.dropdown-menu.show {
	box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.1);
}

.dropdown-item.active,
.dropdown-item:active,
.dropdown-menu .dropdown-item:hover {
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14),
		0 7px 10px -5px rgb(66, 136, 204, 0.81);
	background-color: #4288cc;
	color: var(--white);
	border-radius: 3px;
}

/* SIDEBAR STYLE */
.sidebar {
	background-color: #272c33 !important;
	padding: 80px 12px 0;
	position: fixed;
	top: 0;
	width: 220px;
	height: 100%;
	box-shadow: var(--box-shadow);
	transition: 0.3s;
	transition-property: left;
	overflow: overlay;
}

.sidebar .sidebar-menu a {
	color: var(--text);
	display: block;
	width: 100%;
	line-height: 32px;
	text-decoration: none;
	transition: 0.3s;
	margin-bottom: 6px;
	transition-property: background;
}

.sidebar .sidebar-menu .nav-link.active,
.sidebar .sidebar-menu .nav-link:hover,
.sidebar .sidebar-menu .nav-link:focus {
	color: #fff;
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14),
		0 7px 10px -5px rgb(66, 136, 204, 0.81);
	background-color: #4288cc;
	border-radius: 3px;
	border: 2px solid #4288cc;
}

.sidebar .sidebar-menu span {
	font-size: 14px;
	font-weight: 500;
	/*text-transform: uppercase;*/
}

.sidebar-menu i {
	padding-right: 10px;
}

.collapse:not(.show) {
	display: unset;
}

.sidebar.collapse {
	width: 80px;
	padding: 80px 0 0;
	transition: 0.3s;
}

.sidebar.collapse a span {
	display: none;
}

.sidebar.collapse .sidebar-menu i {
	font-size: 20px;
}

.sidebar.collapse .sidebar-menu a {
	padding-left: 28px;
	border-radius: unset;
}

/* MAIN CONTENT STYLE */
.main-content {
	margin-top: 100px;
	margin-left: 250px;
	padding: 15px;
	transition: 0.3s;
}

.form-title h1 {
	text-transform: uppercase;
	letter-spacing: 0.2em;
	font-weight: 400;
	font-size: 1.5rem;
}

.card {
	border: none;
	box-shadow: var(--box-shadow);
	max-width: 600px;
	margin: auto;
}

.card-body {
	padding: 2rem 2rem !important;
}

.form-control:focus,
.btn-check:focus+.btn-danger,
.btn-danger:focus {
	box-shadow: none;
}

form-control:focus,
.btn-check:focus+.btn-danger,
.btn-danger:focus {
	box-shadow: none;
}

.stock-subscription-form .form-group .form-control,
.stock-subscription-form .form-group .form-select {
	border-color: var(--border);
	border-radius: 10px;
	height: 49px;
	padding-left: 15px;
	padding-right: 15px;
}

.form-control:not(.form-control-sm):not(.form-control-lg) {
	font-size: 13.6px;
	font-size: 0.85rem;
	line-height: 1.85;
}

.stock-subscription-form .form-group .form-control,
.stock-subscription-form .form-group .form-select {
	border-color: var(--border);
	border-radius: 10px;
	height: 49px;
	padding-left: 15px;
	padding-right: 15px;
}

.form-control:not(.form-control-lg) {
	font-size: 12px;
	font-size: 0.75rem;
	line-height: 1.3;
}

input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"],
textarea {
	-webkit-appearance: none;
}

[type="text"],
[type="email"],
[type="url"],
[type="password"],
[type="number"],
[type="date"],
[type="datetime-local"],
[type="month"],
[type="search"],
[type="tel"],
[type="time"],
[type="week"],
[multiple],
textarea,
select {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	background-color: #fff;
	border-color: #6b7280;
	border-width: 1px;
	border-radius: 0px;
	padding-top: 0.5rem;
	padding-right: 0.75rem;
	padding-bottom: 0.5rem;
	padding-left: 0.75rem;
	font-size: 1rem;
	line-height: 1.5rem;
}

.form-control {
	border-color: rgba(0, 0, 0, 0.09);
}

.form-control {
	display: block;
	width: 100%;
	height: calc(1.5em + 0.75rem + 2px);
	padding: 0.375rem 0.75rem;
	font-size: 1rem;
	font-weight: 400;
	line-height: 1.5;
	color: #495057;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.nav-link {
	padding: 0 0.4rem;
}

button.accordion {
	width: 100%;
	border: none;
	outline: none;
	text-align: left;
	padding: 15px 10px 15px 0px;
	font-size: 14px;
	text-transform: capitalize;
	color: #ffffff;
	cursor: pointer;
	transition: background-color 0.2s linear;
}

button.accordion.is-open {
	background-color: rgba(93, 42, 42, 0.03);
}

.accordion-content {
	padding: 0 20 0 0;
	/*max-height: 0;*/
	/*overflow: hidden;*/
	/*transition: max-height 0.5s ease-in-out;*/
}
</style>

<style>
.form-control {
	border-color: #ced4da !important;
	color: #495057 !important;
}
</style>

<script>
// import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeApplicationLogo from "@/Components/ApplicationLogo";
import BreezeDropdown from "@/Components/Dropdown";
import BreezeDropdownLink from "@/Components/DropdownLink";
import BreezeNavLink from "@/Components/NavLink";
import FlashMessages from "@/Components/FlashMessages";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { cornsilk } from "color-name";
import packageLockJson from "../../../package-lock.json";
import Announcement from "@/Components/Announcement.vue";

export default {
	// components: {
	//     BreezeAuthenticatedLayout,
	// },
	components: {
		BreezeApplicationLogo,
		BreezeDropdown,
		BreezeDropdownLink,
		FlashMessages,
		BreezeNavLink,
		BreezeResponsiveNavLink,
		Announcement
	},
	data() {
		return {
			usertype: this.$auth,
			main_sidebar: 0,
			version: packageLockJson.version,
		};
	},
	props: {
		auth: Object,
		errors: Object,
		notification_count: String,
	},

	mounted() {
		this.initTawkTo();
		this.sidebar();
	},
	created() {
		//
	},
	destroyed() {
		//
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
		toggleSideBar() {
			if (window.innerWidth < 1300) {
				if (this.main_sidebar == 0) {
					this.main_sidebar = 1;
				} else {
					this.main_sidebar = 0;
				}
			}
		},
		sidebar() {
			if (window.innerWidth > 1300) {
				this.main_sidebar = 1;
			} else {
				this.main_sidebar = 0;
			}
		},
		collapseToggle(event) {
			console.log(event);
			if (event.target.classList.contains("is-open")) {
				event.target.classList.remove("is-open");
			} else {
				event.target.classList.add("is-open");
			}
			var content = event.target.nextElementSibling;
			if (content.style.maxHeight) {
				content.style.maxHeight = null;
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
			}
		},
	},
};
</script>
