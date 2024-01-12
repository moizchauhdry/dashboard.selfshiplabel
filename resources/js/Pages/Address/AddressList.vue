<template>
	<MainLayout>
		<div class="card mt-4">
			<div class="card-header">Shipping Address
				<inertia-link class="btn btn-primary float-right" :href="route('address.create')">
					<i class="fa fa-plus mr-1"></i><span>Add New</span>
				</inertia-link>
			</div>
			<div class="card-body">

				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Sr. #</th>
							<th scope="col">Full Name</th>
							<th scope="col">Country</th>
							<th scope="col">State</th>
							<th scope="col">City</th>
							<th scope="col">Zip Code</th>
							<th scope="col">Phone</th>
							<th scope="col">Address</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(address, index) in addresses" :key="address.id">
							<td scope="col">{{ ++index }}</td>
							<td>{{ address.fullname }}</td>
							<td>{{ address.country.nicename }}</td>
							<td>{{ address.state ?? '-' }}</td>
							<td>{{ address.city }}</td>
							<td>{{ address.zip_code }}</td>
							<td>{{ address.phone }}</td>
							<td>{{ address.address }}</td>
							<td>
								<inertia-link class="nav-link" :href="route('address.edit', address.id)">
									<i class="fas fa-external-link-alt mr-1"></i><span>Edit</span>
								</inertia-link>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
	},
	props: {
		auth: Object,
		addresses: Object,
	},
	methods: {
		destroy(address_id) {
			if (confirm("Are you sure you want to delete this address?")) {
				this.$inertia.delete(this.route("address.destroy", address_id));
			}
		},
	},
};
</script>
