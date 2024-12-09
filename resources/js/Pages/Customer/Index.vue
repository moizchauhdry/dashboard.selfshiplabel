<template>
	<MainLayout>
		<div class="container">
			<flash-messages></flash-messages>
			<div class="card">
				<div class="card-header">Manage Customers</div>
				<div class="card-body">
					<form @submit.prevent="submit">
						<div class="row">
							<div class="form-group col-md-4 col-sm-12 mb-3">
								<label for="search">Search</label>
								<input type="search" name="search" v-model="form.search" class="form-control"
									id="search" placeholder="Name, Suite # etc." />
							</div>

							<div class="form-group col-md-2 col-sm-12 mb-3">
								<label for="accountType">Account Type</label>
								<select v-model="form.account_type" class="form-control" id="accountType">
									<option value="">All</option>
									<option value="1">Individual</option>
									<option value="2">Business</option>
								</select>
							</div>

							<div class="form-group col-md-1 col-sm-12 mb-3 d-flex align-items-end">
								<button type="submit" class="btn btn-primary w-100">Search</button>
							</div>
						</div>
					</form>
					<div class="table-responsive">
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
									<th class="px-3 py-2">Account Type</th>
									<th class="px-3 py-2">Action</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(customer, index) in customers.data" :key="customer.id">
									<td>{{ (customers.current_page - 1) * customers.per_page + (index + 1) }}</td>
									<td>{{ suiteNum(customer.id) }}</td>
									<td>{{ customer.name }}</td>
									<td>{{ customer.email }}</td>
									<td>{{ customer.phone }}</td>
									<td>{{ customer.created_at }}</td>
									<td>{{ customer.status == 1 ? 'Active' : 'Inactive' }}</td>
									<td>
										<span class="badge cursor-pointer"
											:class="customer.account_type == 2 ? 'badge-success' : 'badge-primary'"
											@click="editAccountType(customer.id, customer.account_type)">
											{{ customer.account_type == 2 ? "Business" : "Individual" }}
											<i class="fas fa-edit ml-1" style="font-size: 14px"></i>
										</span>

									</td>
									<td>
										<template
											v-if="$page.props.auth.user.type == 'admin' || $page.props.auth.user.type == 'manager'">

											<inertia-link :href="route('customers.edit', customer.id)"
												class="btn btn-primary btn-sm mr-1"><i
													class="fas fa-edit"></i></inertia-link>

											<!-- <inertia-link :href="route('customers.show', customer.id)"
											class="btn btn-info btn-sm mr-1"><i class="fa fa-list"></i></inertia-link> -->

											<inertia-link
												:href="route('customers.markup', { customer_id: customer.id })"
												class="btn btn-warning btn-sm mr-1" v-if="customer.account_type == 2"><i
													class="fas fa-dollar"></i></inertia-link>
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
				</div>
				<div class="card-footer">
					<pagination class="mt-6" :links="customers.links"></pagination>
				</div>
			</div>
		</div>

		<div v-if="show_modal" class="modal fade show" tabindex="-1" role="dialog" style="display: block;"
			aria-labelledby="editAccountTypeModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form @submit.prevent="updateAccountType()">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editAccountTypeModalLabel">Edit Account Type</h5>
							<button type="button" class="close" @click="closeModal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">

								<div class="col-md-12 form-group mb-2">
									<select v-model="account_form.account_type" class="form-control">
										<option value="1">Individual</option>
										<option value="2">Business</option>
									</select>
								</div>

								<div class="col-md-12">
									<p style="font-size:12px">
										<b>Important Note:</b> When changing a user account type from "Individual" to
										"Business," you can set a custom markup percentage for the user. However, if you
										switch the account type from "Business" back to "Individual," all rates will
										reset to their default values.
									</p>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-secondary" @click="closeModal">Close</button>
							<button type="submit" class="btn btn-sm btn-primary">Save changes</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div v-if="show_modal" class="modal-backdrop fade show"></div>
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
				search: "",
				account_type: "",
			}),
			account_form: useForm({
				customer_id: "",
				account_type: "",
			}),
			show_modal: false,
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
		suiteNum(user_id) {
			return 4000 + user_id;
		},
		createOrderLink(id) {
			return this.route("orders.create") + "?customer_id=" + id;
		},
		submit() {
			this.form.post(route("customers.index"));
		},
		closeModal() {
			this.show_modal = false;
		},
		editAccountType(id, type) {
			this.show_modal = true;
			this.account_form.customer_id = id;
			this.account_form.account_type = type;
		},
		updateAccountType() {
			this.account_form.post(this.route('customers.account-type.update'))
			this.closeModal();
		},
	},
};
</script>
