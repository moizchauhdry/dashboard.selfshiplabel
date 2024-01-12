<template>
	<div
		class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
	>
		<div>
			<!-- <inertia-link href="/">
                <img alt="shippingxps" width="237" height="55" src="/theme/img/logo.png">
            </inertia-link> -->
			<a href="http://shippingxps.com">
				<img
					alt="shippingxps"
					width="237"
					height="55"
					src="/theme/img/logo.png"
				/>
			</a>
		</div>

		<div
			class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
		>
			<slot />
		</div>

		<div>
			<PricingContentComponent />
		</div>
	</div>
</template>

<script>
	var Tawk_API = Tawk_API || {},
		Tawk_LoadStart = new Date();
	(function () {
		console.log("Tawk To Loaded");
		var s1 = document.createElement("script"),
			s0 = document.getElementsByTagName("script")[0];
		s1.async = true;
		s1.src = "https://embed.tawk.to/617d793f86aee40a573920c6/1fj9238e6";
		s1.charset = "UTF-8";
		s1.setAttribute("crossorigin", "*");
		s0.parentNode.insertBefore(s1, s0);
	})();

	import BreezeApplicationLogo from "@/Components/ApplicationLogo";
	import PricingContentComponent from "../Components/PricingContent.vue";

	export default {
		components: {
			BreezeApplicationLogo,
			PricingContentComponent,
		},
	};
</script>
