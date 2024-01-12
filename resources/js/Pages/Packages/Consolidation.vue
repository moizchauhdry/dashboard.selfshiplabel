<template>
	<MainLayout>
		<div class="card">
			<div class="card-header"> Package Consolidation</div>
			<div class="card-body">
				<form @submit.prevent="submit">
					<div class="row">
						<div class="col-md-2 offset-md-5">
							<select class="form-control" name="warehouse_id" id="warehouse_id" v-model="form.warehouse_id"
								@change="filterPackages()">
								<option value="" selected>--Select Warehouse--</option>
								<template v-for="warehouse in warehouses" :key="warehouse.id">
									<option :value="warehouse.id">{{ warehouse.name }}</option>
								</template>
							</select>
						</div>
						<div class="col-md">
							<button type="submit" class="btn btn-primary float-right mb-2"
								v-if="form.warehouse_id && form.package_consolidation.length >= 2">
								Save & Next
							</button>
						</div>
					</div>

					<div class="table-responsive" v-if="form.warehouse_id">
						<table class="table table-striped table-bordered text-center text-sm table-sm mt-4">
							<thead>
								<tr>
									<th scope="col">Package</th>
									<th scope="col">Warehouse</th>
									<th scope="col">Tracking Number</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="pkg in pkgs" :key="pkg.id">
									<td>
										<a :href="route('packages.show', pkg.id)">
											<span class="badge badge-primary text-sm">PKG #{{ pkg.id }}</span></a>
									</td>
									<td>
										{{ pkg?.warehouse?.name }}
									</td>
									<td>
										{{ pkg.tracking_number_in ?? '-' }}
									</td>
									<td>
										<input type="checkbox" name="package_consolidation" :value="pkg.id"
											v-model="form.package_consolidation" />
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
<style scoped>
.label {
	padding: 5px;
}
</style>
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
			active: "open",
			form: {
				package_consolidation: [],
				warehouse_id: "",
			},
		};
	},
	methods: {
		siuteNum(user_id) {
			return 4000 + user_id;
		},

		submit() {
			Inertia.post(route("packages.consolidation.store"), this.form, {});
		},

		filterPackages() {
			this.form.package_consolidation = [];
			Inertia.post(route("packages.consolidation"), this.form);
		},
	},
	watch: {
		params: {
			handler() {
				this.$inertia.get(this.route("packages.consolidation"), this.params, {
					replace: true,
					preserveState: true,
				});
			},
		},
	},
};
</script>
