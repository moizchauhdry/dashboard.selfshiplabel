<template>
	<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
		<div class="text-center">
			<a href="https://selfshiplabel.com">
				<img alt="" width="237" height="55" src="/images/logo.png" />
				<!-- <h1 class="text-lg mt-2">Admin Login</h1> -->
			</a>
		</div>

		<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
			<slot />
		</div>

		<!-- <div><PricingContentComponent /></div> -->
	</div>
</template>

<script>
import BreezeApplicationLogo from "@/Components/ApplicationLogo";
import PricingContentComponent from "../Components/PricingContent.vue";

export default {
	components: {
		BreezeApplicationLogo,
		PricingContentComponent,
	},
};
</script>
