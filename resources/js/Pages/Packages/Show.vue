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
			//
		};
	},
	props: {
		record: Object,
		payments: Object,
		package_files: Object,
	},
	mounted() {
		//
	},
};
</script>
