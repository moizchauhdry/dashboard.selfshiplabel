<template>
	<MainLayout>
		<div class="container mb-5">
			<div class="row">
				<div class="col-md-12">

					<child-package-component v-bind="$props"></child-package-component>
					<SelfServiceComponent v-bind="$props"></SelfServiceComponent>
					
					<!-- <notification-component v-bind="$props"></notification-component> -->
					<!-- <template v-if="packag.project_id == 1">
						<return-component v-bind="$props"></return-component>
						<shipping-address-component v-bind="$props"></shipping-address-component>
						<package-box-component v-bind="$props"></package-box-component>
						<shipping-rate-component v-bind="$props"></shipping-rate-component>
						<consolidation-component v-bind="$props"></consolidation-component>
						<service-component v-bind="$props"></service-component>
						<storage-component v-bind="$props"></storage-component>
						<mailout-component v-bind="$props"></mailout-component>
						<package-image-component v-bind="$props"></package-image-component>
						<payment-component v-bind="$props"></payment-component>
						<package-delete-component v-bind="$props"></package-delete-component>
					</template> -->

					<div v-show="overlay === true" class="overlay">
						<div class="overlay__inner">
							<div class="overlay__content"><span class="spinner"></span></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</MainLayout>

	<ImageViewer></ImageViewer>
</template>

<script>
import $ from "jquery";
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import ImageViewer from "@/Components/ImageViewer";
import ConsolidationComponent from "./Components/ConsolidationComponent.vue";
import ChildPackageComponent from "./Components/ChildPackageComponent.vue";
import ShippingAddressComponent from "./Components/ShippingAddressComponent.vue";
import PackageBoxComponent from "./Components/PackageBoxComponent.vue";
import ShippingRateComponent from "./Components/ShippingRateComponent.vue";
import PackageImageComponent from "./Components/PackageImageComponent.vue";
import ServiceComponent from "./Components/ServiceComponent.vue";
import MailoutComponent from "./Components/MailoutComponent.vue";
import ShipoutComponent from "./Components/ShipoutComponent.vue";
import NotificationComponent from "./Components/NotificationComponent.vue";
import PaymentComponent from "./Components/PaymentComponent.vue";
import PackageDeleteComponent from "./Components/PackageDeleteComponent.vue";
import ReturnComponent from "./Components/ReturnComponent.vue";
import StorageComponent from "./Components/StorageComponent.vue";
import SelfServiceComponent from "./Components/SelfServiceComponent.vue";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		ImageViewer,
		ConsolidationComponent,
		ChildPackageComponent,
		ShippingAddressComponent,
		PackageBoxComponent,
		ShippingRateComponent,
		PackageImageComponent,
		ServiceComponent,
		MailoutComponent,
		ShipoutComponent,
		NotificationComponent,
		PaymentComponent,
		PackageDeleteComponent,
		ReturnComponent,
		StorageComponent,
		SelfServiceComponent
	},
	data() {
		return {
			// address_form: this.$inertia.form({
			// 	package_id: this.packag.id,
			// 	address_book_id: this.packag.address_book_id,
			// }),
			// tabs: {
			// 	tab1: true,
			// 	tab2: false,
			// 	tab3: false,
			// 	tab4: false,
			// },
			// storage_fee: 0,
			// overlay: false,
			// displayNoteShipping: true,
		};
	},
	props: {
		// auth: Object,
		record: Object,
		// child_package_orders: Object,
		// services: Object,
		// service_requests: Object,
		// images: Object,
		// shipping_services: Object,
		// package_service_requests: Object,
		// shipping_address: Object,
		// package_boxes: Object,
		// countries: Object,
		// service_requests_service_ids: Object,
		// service_request_pending_count: Object,
		// total: Object,
		// order_charges: Object,
		// mailout_fee: Number,
		// eei_charges: Number,
		// label_charges: Number,
		// payments: Array,
		// package_files: Array,
	},
	computed: {
		// siuteNum() {
		// 	return 4000 + this.$page.props.auth.user.id;
		// },
	},
	mounted() {
		//
	},
	// methods: {
	// 	formatNumber(num) {
	// 		return parseFloat(num).toFixed(2);
	// 	},
	// 	submitShippingAddressForm() {
	// 		this.address_form.post(this.route("packages.address.update"));
	// 		Inertia.reload();
	// 	},
	// 	submitShipOutForm() {
	// 		this.form_ship.post(this.route("packages.ship-package"));
	// 		this.tracking_edit = false;
	// 	},
	// 	getServiceSubTotal() {
	// 		let request_total = 0;
	// 		this.service_requests.forEach(function (item) {
	// 			if (item.status == "served") {
	// 				request_total += item.price;
	// 			}
	// 		});

	// 		var consolidation_total = 0;
	// 		console.log(this.hasConsolidationServed);
	// 		if (this.hasConsolidationServed) {
	// 			consolidation_total = this.packag.orders.length * 1.5;
	// 		} else {
	// 			consolidation_total = 0;
	// 		}

	// 		return this.formatNumber(request_total + consolidation_total);
	// 	},
	// 	getGrandTotal() {
	// 		var r1 = this.formatNumber(
	// 			this.packag.shipping_total + this.mailout_fee + this.storage_fee
	// 		);
	// 		var r2 = this.getServiceSubTotal();

	// 		return this.formatNumber(parseFloat(r1) + parseFloat(r2));
	// 	},
	// 	setActiveTabAB(tab) {
	// 		for (var key in this.tabs) {
	// 			if (key === tab) {
	// 				this.tabs[key] = true;
	// 			} else {
	// 				this.tabs[key] = false;
	// 			}
	// 		}
	// 	},
	// 	getTabClass(tab) {
	// 		if (this.tabs[tab] === true) {
	// 			return "nav-link active";
	// 		} else {
	// 			return "nav-link";
	// 		}
	// 	},
	// 	getTabPaneClass(tab) {
	// 		if (this.tabs[tab] === true) {
	// 			return "tab-pane show active";
	// 		} else {
	// 			return "tab-pane fade";
	// 		}
	// 	},
	// 	imgURL(url) {
	// 		return "/public/uploads/" + url;
	// 	},
	// 	viewImage(event) {
	// 		console.log(event.target.src);
	// 		var modal = document.getElementById("imageViewer");
	// 		var imageSRC = document.querySelector("#imageViewer img");
	// 		imageSRC.src = event.target.src;
	// 		modal.classList.add("show");
	// 		$("#imageViewer").show();
	// 	},
	// 	makePackageUrl(order_id) {
	// 		return route("package.create") + "?order_id=" + order_id;
	// 	},
	// },
};
</script>
