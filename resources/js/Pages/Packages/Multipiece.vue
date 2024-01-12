<template>
	<MainLayout>
		<div class="card mt-4">
			<div class="card-body">
				<div class="row">
					<h6
						class="font-semibold text-xl text-gray-800 leading-tight form-title mb-2 text-center"
					>
						Multipiece Package
					</h6>
				</div>
				<form @submit.prevent="submit">
					<div class="row">
						<div class="col-md-2 offset-md-5">
							<select
								class="form-control"
								name="warehouse_id"
								id="warehouse_id"
								v-model="form.warehouse_id"
								@change="filterPackages()"
							>
								<option value="" selected>--Select Warehouse--</option>
								<template v-for="warehouse in warehouses" :key="warehouse.id">
									<option :value="warehouse.id">{{ warehouse.name }}</option>
								</template>
							</select>
						</div>
						<div class="col-md">
							<button
								type="submit"
								class="btn btn-primary float-right mb-2"
								v-if="form.warehouse_id && form.multipiece_package.length >= 2"
							>
								Save & Next
							</button>
						</div>
					</div>

					<div class="table-responsive" v-if="form.warehouse_id">
						<table
							class="table table-striped table-bordered text-center text-sm table-sm mt-4"
						>
							<thead>
								<tr>
									<th scope="col">Package</th>
									<th scope="col">Warehouse</th>
									<th scope="col">Status</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="pkg in pkgs" :key="pkg.id">
									<td>
										<a :href="route('packages.show', pkg.id)"
											><span class="badge badge-primary text-sm"
												>PKG #{{ pkg.id }}</span
											></a
										>
									</td>
									<td>
										{{ pkg?.warehouse?.name }}
									</td>
									<td>
										<span v-bind:class="getLabelClass(pkg.status)">{{
											pkg.status
										}}</span>
									</td>
									<td>
										<input
											type="checkbox"
											name="multipiece_package"
											:value="pkg.id"
											v-model="form.multipiece_package"
										/>
									</td>
								</tr>
								<tr v-if="pkgs.length == 0">
									<td class="text-primary text-center" colspan="9">
										There are no packages found.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import { Inertia } from "@inertiajs/inertia";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
		},
		props: {
			auth: Object,
			pkgs: Object,
			warehouses: Object,
		},
		data() {
			return {
				processing: false,
				form: {
					multipiece_package: [],
					warehouse_id: "",
				},
			};
		},
		methods: {
			getLabelClass(status) {
				switch (status) {
					case "pending":
						return "text-uppercase badge badge-warning p-2 text-white";
						break;
					case "open":
						return "text-uppercase badge badge-info p-2 text-white";
						break;
					case "filled":
						return "text-uppercase badge badge-info p-2 text-white";
						break;
					case "open":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "labeled":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "shipped":
						return "text-uppercase badge badge-primary p-2";
						break;
					case "delivered":
						return "text-uppercase badge badge-success p-2 text-white";
						break;
					case "multipiece":
						return "text-uppercase badge badge-danger p-2 text-white";
						break;
					case "served":
						return "label bg-success";
						break;
					case "rejected":
						return "label bg-danger";
						break;
					default:
						return "text-uppercase badge badge-primary p-2 text-white";
				}
			},
			siuteNum(user_id) {
				return 4000 + user_id;
			},

			submit() {
				Inertia.post(route("packages.multipiece.store"), this.form, {});
			},

			filterPackages() {
				this.form.multipiece_package = [];
				Inertia.post(route("packages.multipiece"), this.form);
			},
		},
		watch: {
			params: {
				handler() {
					this.$inertia.get(this.route("packages.multipiece"), this.params, {
						replace: true,
						preserveState: true,
					});
				},
			},
		},
	};
</script>
