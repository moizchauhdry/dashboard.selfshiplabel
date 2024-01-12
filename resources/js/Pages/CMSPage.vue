<template>
	<header-component></header-component>
	<div role="main" class="main">
		<div
			class="container mt-5 mb-5 font-size-20"
			v-html="pageContext.description"
		></div>
	</div>
	<PricingContentComponent />

	<FooterComponent />
</template>

<style scoped></style>

<script>
	import HeaderComponent from "../Components/Header.vue";
	import FooterComponent from "../Components/Footer.vue";
	import PricingContentComponent from "../Components/PricingContent.vue";

	export default {
		components: {
			"header-component": HeaderComponent,
			FooterComponent,
			PricingContentComponent,
		},
		data() {},
		props: {
			auth: Object,
			canLogin: Boolean,
			canRegister: Boolean,
			errors: Object,
			laravelVersion: String,
			phpVersion: String,
			pageContext: Object,
		},
		methods: {},
		mounted() {},
		created() {
			//
		},
	};
</script>

<style scoped>
	.font-size-20 {
		font-size: 20px;
	}
</style>
