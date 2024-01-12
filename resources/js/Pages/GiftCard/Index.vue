<template>
	<MainLayout>
		<div class="card mt-4 pr-2">
			<div class="card-header">
				Gift Card
				<div class="float-right">
					<inertia-link
						:href="route('gift-card.create')"
						v-if="$page.props.auth.user.type == 'customer'"
						class="btn btn-primary float-right"
						><i class="fa fa-plus mr-1"></i>Add Request</inertia-link
					>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-responsive table-striped table-bordered">
						<thead class="text-center">
							<tr>
								<th>ID</th>
								<th>Customer</th>
								<th>Gift Card</th>
								<th>Audit</th>
								<th></th>
							</tr>
						</thead>
						<tbody class="">
							<tr v-for="card in gift_cards" :key="card.id">
								<td>{{ card.id }}</td>
								<td class="text-center">
									<inertia-link :href="route('customers.show', card.user.id)" class="btn btn-link">
									# {{ siuteNum(card.user.id) }} - {{ card.user.name }}
									</inertia-link>
								</td>
								<td>
									<b>Title:</b> {{ card.title }} <br>
									<b>Type:</b> {{ card.type }} <br>
									<b>Amount:</b> ${{ card.amount }} <br>
									<b>Quantity:</b> {{ card.qty }} <br>
									<b>Notes:</b> {{ card.notes }} <br>
								</td> 
								<td>
									<span v-if="card.payment_status">
										<b>Payment:</b> 
										<span class="badge m-1" :class="card.payment_status == 'Paid' ? 'badge-success' : 'badge-primary'">
											{{ card.payment_status }}</span> <br>
									</span>
									<b>Admin Status: </b>
										<span v-if="card.status">{{ card.status }}</span> 
										<span v-else>Pending</span> <br>
									<span v-if="card.admin_approved_at"><b>Admin Approved At:</b> {{ card.admin_approved_at }}<br></span> 
									<span v-if="card.admin_updated_at"><b>Admin Updated At:</b> {{ card.admin_updated_at }}<br></span> 
								</td>
								<td class="text-center">
									<inertia-link :href="route('gift-card.edit', card.id)" class="btn btn-info btn-xs float-right">
										<span><i class="fa fa-pencil-alt mr-1"></i></span>Edit & Continue</inertia-link>
								</td>
							</tr>
						</tbody>
					</table>
				</div>				
				<div class="col-md-12 text-center" v-if="gift_cards.length == 0">
					<span>There are no gift card requests</span>
				</div>
			</div>
			<div class="card-footer"></div>
		</div>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
		},
		props: {
			gift_cards: Object,
		},
		methods: {
			changeStatus(id, status, event) {
				axios
					.post(this.route("gift-card.changeStatus"), {
						id: id,
						status: status,
					})
					.then(function (response) {
						console.log(response.data.insurance.status);
						let status = response.data.insurance.status;
						if (status === 1) {
							event.target.classList.remove("btn-success");
							event.target.classList.add("btn-danger");
						} else {
							event.target.classList.add("btn-success");
							event.target.classList.remove("btn-danger");
						}
					})
					.catch(function (error) {
						console.log(error);
					});
			},
			siuteNum(user_id){
				return 4000 + user_id;
			},
		},
	};
</script>

<style scoped>
.table td, .table th , .table tr {
    border-color: rgb(183, 183, 183);
}
</style>
