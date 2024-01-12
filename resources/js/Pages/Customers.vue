<template>
	<MainLayout>
		<div class="card mt-4">
			<div class="card-body stock-subscription-form">
				<h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
					Manage Customers
				</h2>
				<form @submit.prevent="submit">
					<div class="row">
						<div class="form-group col-md-4">
							<input type="search" name="search" v-model="form.suite_no" class="form-control"
								placeholder="Search By Suite #" />
						</div>
						<div class="form-group col-md-4">
							<button type="submit" class="btn btn-primary">Search</button>
						</div>
					</div>
				</form>
				<flash-messages></flash-messages>
				<div class="table-responsive mt-3">
					<table class="table table-striped table-sm text-sm text-center table-bordered">
						<thead>
							<tr>
								<th class="px-3 py-2">SR #</th>
								<th class="px-3 py-2">Suite #</th>
								<th class="px-3 py-2">Name</th>
								<th class="px-3 py-2">Email</th>
								<th class="px-3 py-2">Phone</th>
								<th class="px-3 py-2">Register Date</th>
								<th class="px-3 py-2">Status</th>
								<th class="px-3 py-2" colspan="2"></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(customer, index) in customers.data" :key="customer.id">
								<td>{{ ++index }}</td>
								<td>{{ siuteNum(customer.id) }}</td>
								<td>{{ customer.name }}</td>
								<td>{{ customer.email }}</td>
								<td>{{ customer.phone }}</td>
								<td>{{ customer.created_at }}</td>
								<td>{{ customer.status == 1 ? 'Active' : 'Inactive' }}</td>
								<td>
									<template v-if="$page.props.auth.user.type == 'admin' ||
										$page.props.auth.user.type == 'manager'
										">
										<inertia-link class="btn btn-success btn-xs m-1"
											:href="createOrderLink(customer.id)">
											<i class="fa fa-plus mr-1"></i>Create
											Package</inertia-link>
										<inertia-link :href="route('customers.edit', customer.id)"
											class="btn btn-primary btn-xs m-1">
											<i class="fa fa-pencil-alt mr-1"></i>Edit</inertia-link>
										<inertia-link :href="route('customers.show', customer.id)"
											class="btn btn-info btn-xs m-1">
											<i class="fa fa-list mr-1"></i>Detail</inertia-link>
									</template>
								</td>
							</tr>
							<tr v-if="customers.data.length == 0">
								<td class="text-center text-primary" colspan="9">
									There are no customers added yet.
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<pagination class="mt-6" :links="customers.links"></pagination>
			</div>
		</div>
	</MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";

export default {
	data() {
		return {
			form: useForm({
				suite_no: "",
			}),
		};
	},
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		Pagination,
	},
	props: {
		customers: Object,
	},
	watch: {
		params: {
			handler() {
				this.$inertia.get(this.route('customers.index'), this.params, { replace: true, preserveState: true });
			}
		}
	},
	methods: {
		siuteNum(user_id) {
			return 4000 + user_id;
		},
		// destroy(id) {
		// 	if (confirm("Are you sure you want to delete this Customer?")) {
		// 		this.$inertia.delete(this.route("delete-customer", id));
		// 	}
		// },
		createOrderLink(id) {
			return this.route("orders.create") + "?customer_id=" + id;
		},
		submit() {
			this.form.post(route("customers.index"));
		},
	},
};
</script>
