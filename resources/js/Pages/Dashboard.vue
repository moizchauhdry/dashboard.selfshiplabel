<template>
	<MainLayout>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div
						class="col-md-6"
						v-for="address in addresses"
						v-bind:key="address.id"
					>
						<div class="card">
							<div class="card-header">{{ address.name }} Mailing Address</div>
							<div class="card-body">
								<div class="address-card mb-4">
									<p>
										<strong>Full Name:</strong
										><span
											class="copy-item text-lg"
											@click="copy(siuteNum + '-' + $page.props.auth.user.name)"
											>{{ siuteNum }} – {{ $page.props.auth.user.name }}</span
										>
									</p>

									<p>
										<strong>Address:</strong
										><span
											class="copy-item text-lg"
											@click="copy(address.address)"
											>{{ address.address }}</span
										>
									</p>

									<p>
										<strong>Suite No:</strong
										><span class="copy-item text-lg" @click="copy(siuteNum)">
											{{ siuteNum }}
										</span>
									</p>

									<p>
										<strong>City:</strong
										><span
											class="copy-item text-lg"
											@click="copy(address.city)"
											>{{ address.city }}</span
										>
									</p>

									<p>
										<strong>State:</strong
										><span
											class="copy-item text-lg"
											@click="copy(address.state)"
											>{{ address.state }}
										</span>
									</p>

									<p>
										<strong>Zip:</strong
										><span class="copy-item text-lg" @click="copy(address.zip)"
											>{{ address.zip }}
										</span>
									</p>

									<p>
										<strong>Phone:</strong
										><span
											class="copy-item text-lg"
											@click="copy(address.phone)"
											>{{ address.phone }}</span
										>
									</p>
								</div>
								<a
									href="javascript:void(0)"
									class="btn btn-primary"
									@click="
										copy(
											address.address +
												' ' +
												address.city +
												' ' +
												address.state +
												' ' +
												address.zip
										)
									"
								>
									<span><i class="fa fa-copy mr-1"></i>Click To Copy</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12" v-show="false">
				<div class="card">
					<div class="card-header">
						<h5>Accounts Balance</h5>
					</div>
					<div class="card-body">
						<div class="card-address">
							<p><strong>Total :</strong> $500</p>
							<p><strong>Used:</strong>$300</p>
							<p><strong>Available:</strong>$200</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" style="margin-top: 20px">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h5>Packages History</h5>
					</div>
					<div class="card-body">
						<package-list-component v-bind="$props"></package-list-component>
					</div>
				</div>
			</div>
		</div>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import { Inertia } from "@inertiajs/inertia";
	import useClipboard from "vue-clipboard3";
	import PackageListComponent from "./Packages/Components/PackageListComponent.vue";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			PackageListComponent,
		},
		data() {
			return {
				addresses: {},
				active: "packages",
				order_form: {
					status: this.filter.status,
					processing: false,
				},
			};
		},
		props: {
			auth: Object,
			errors: Object,
			pkgs: Object,
			filter: Object,
		},
		computed: {
			siuteNum() {
				return 4000 + this.$page.props.auth.user.id;
			},
		},
		methods: {
			getMailingAddress() {
				axios.get(this.route("getMailingAddress")).then((response) => {
					this.addresses = response.data.data;
					console.log(this.addresses);
				});
			},
			searchOrder(status) {
				this.active = status;
				this.order_form.status = status;
				this.order_form.processing = true;
				Inertia.post(route("dashboard"), this.order_form, {
					preserveScroll: true,
					onStart: () => {
						this.order_form.processing = true;
					},
					onFinish: () => {
						this.order_form.processing = false;
					},
				});
			},
		},
		created() {
			this.getMailingAddress();
		},
		setup() {
			const { toClipboard } = useClipboard();
			const copy = async (text) => {
				try {
					await toClipboard(text);
					console.log("Copied to clipboard");
				} catch (e) {
					console.error(e);
				}
			};
			return { copy };
		},
	};
</script>

<style>
	.card {
		margin-top: 25px;
	}
	.card-body {
		padding: 0.8rem;
	}
	.card-body p strong {
		color: #212529;
		margin-right: 12px;
	}
	.address-card {
		height: 190px;
	}
	.copy-item:hover {
		cursor: pointer;
		border: 3px dotted green;
		padding: 3px;
	}
	button.active.btn.btn-light.w-100 {
		background-color: red !important;
		color: white;
	}
</style>
