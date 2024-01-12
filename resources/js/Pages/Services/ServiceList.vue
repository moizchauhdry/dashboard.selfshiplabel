<template>
	<MainLayout>
		<div class="card mt-4">
			<div class="card-body">
				<h2
					class="font-semibold text-xl text-gray-800 leading-tight form-title"
				>
					Services
				</h2>
				<inertia-link
					class="btn btn-primary"
					style="float: right"
					:href="route('services.create')"
				>
					<i class="fas fa-external-link-alt"></i><span>Add New</span>
				</inertia-link>
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Title</th>
							<th scope="col">Description</th>
							<th scope="col">Price</th>
							<th scope="col">status</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="service in services.data" :key="service.id">
							<th scope="col">#</th>
							<td>{{ service.title }}</td>
							<td>{{ service.description }}</td>
							<td>${{ service.price }}</td>
							<td>
								<span v-bind:class="getLabelClass(service.status)">{{
									service.status ? "Active" : "In-Active"
								}}</span>
							</td>
							<td>
								<inertia-link
									class="link-primary"
									:href="route('services.edit', service.id)"
								>
									<span>Edit</span>
								</inertia-link>
							</td>
						</tr>
					</tbody>
				</table>
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

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
		},
		props: {
			auth: Object,
			services: Object,
		},
		methods: {
			destroy(address_id) {
				if (confirm("Are you sure you want to delete this service?")) {
					this.$inertia.delete(this.route("address.destroy", address_id));
				}
			},
			getLabelClass(status) {
				if (status) {
					return "label bg-success";
				}
				return "label bg-danger";
			},
		},
	};
</script>
