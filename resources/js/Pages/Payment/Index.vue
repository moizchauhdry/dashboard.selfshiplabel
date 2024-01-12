<template>
	<MainLayout>
		<div class="card">
			<div class="card-header">
				Manage Payments
				<div class="float-right">
					<!-- <a
						v-if="$page.props.auth.user.type == 'admin'"
						:href="route('generateReportList')"
						class="btn btn-primary"
						target="_blank"
						>Download Reports</a
					> -->
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3 form-group">
						<label for="">Inoice No</label>
						<input type="text" class="form-control" v-model="filter.search_invoice_no"
							placeholder="Search by Invoice No" @keyup="getResults(route('payments.getPayments'))" />
					</div>
					<div class="col-md-3 form-group">
						<label for="">Suit No</label>
						<input type="text" class="form-control" v-model="filter.search_suit_no"
							placeholder="Search by Suit No" @keyup="getResults(route('payments.getPayments'))" />
					</div>
					<div class="col-md-3 form-group">
						<label for="">Filter By</label>
						<select class="form-control" @change="getResults(route('payments.getPayments'))"
							v-model="filter.date_selection" id="">
							<option value="">Select</option>
							<option value="1">Today</option>
							<option value="2">Yesterday</option>
							<option value="3">Last 7 Days</option>
							<option value="4">Last 30 Days</option>
							<option value="5">Custom Range</option>
						</select>
					</div>
					<div class="col-md-3 form-group">
						<label for="" v-show="filter.date_selection === '5'">Date Range</label>
						<Datepicker v-show="filter.date_selection === '5'" v-model="date" range :format="format"
							:enableTimePicker="false"></Datepicker>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th>Sr. #</th>
								<th>Invoice ID</th>
								<th>Customer</th>
								<th>Payment Type</th>
								<th>Transaction ID</th>
								<th>Payment Method</th>
								<th>Shipping Service</th>
								<th>Charged Amount (USD)</th>
								<th>Charged Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in data.data" :key="item.id">
								<td>{{ ++index }}</td>
								<td>{{ item.id }}</td>
								<td>
									<span v-if="item.customer">
										{{ item.customer.name + " - " + item.customer.suite_no }}
									</span>
								</td>
								<td>
									<template v-if="item.order_id != null">
										ORDER #{{ item.order_id }}
									</template>
									<template v-if="item.gift_card_id != null">
										<inertia-link :href="route('gift-card.edit', item.gift_card_id)"
											class="link-hover-style-1 ms-1">GIFT #{{ item.gift_card_id }}</inertia-link>
									</template>
									<template v-if="item.package_id != null">
										<inertia-link :href="route('packages.show', item.package.id)"
											class="link-hover-style-1 ms-1">PKG #{{ item.package.id }}</inertia-link>
									</template>
								</td>
								<td>{{ item.transaction_id }}</td>
								<td>{{ item.payment_type ?? "Authorize.net" }}</td>
								<td>
									{{ item.package != NULL ? item.package.service_label : "" }}
								</td>
								<td>{{ item.charged_amount }}</td>
								<td>{{ item.charged_at }}</td>
								<td>
									<a :href="route('payment.invoice', item.id)" class="btn btn-primary btn-sm m-1"
										target="_blank">Print Invoice</a>
									<a :href="route('generateReport', item.id)" target="_blank"
										class="btn btn-info btn-sm m-1">Print Report</a>
								</td>
							</tr>
							<tr v-show="data.data.length === 0">
								<td colspan="15">
									<div class="container text-center p-5">No Record Found</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="parent">
					<nav class="float-right">
						<ul class="pagination">
							<template v-for="link in data.links">
								<li v-if="link.active" class="page-item active" aria-current="page">
									<span class="page-link" v-html="link.label"></span>
								</li>
								<li v-else class="page-item">
									<a href="javascript:void(0)" class="page-link" @click="getResults(link.url)"
										v-html="link.label"></a>
								</li>
							</template>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import Paginate from "@/Components/Paginate";
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";

export default {
	data() {
		return {
			data: this.payments,
			filter: this.$inertia.form({
				search_invoice_no: "",
				search_suit_no: "",
				date_selection: "",
				date_range: null,
				per_page: null,
			}),
			date: null,
		};
	},
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		BreezeLabel,
		Paginate,
		Datepicker,
	},
	props: {
		auth: Object,
		payments: Object,
	},
	mounted() { },
	methods: {
		getResults(url) {
			if (url != null) {
				axios.post(url, this.filter).then((response) => {
					this.data = response.data.payments;
				});
			}
		},
		changeStatus(id, status, event) {
			axios
				.post(this.route("coupon.changeStatus"), {
					id: id,
					status: status,
				})
				.then(function (response) {
					console.log(response.data.coupon.status);
					let status = response.data.coupon.status;
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
		getAddress(address) {
			return (
				address.address + ", " + address.city + ", " + address.country.name
			);
		},
		format() {
			var start = new Date(this.date[0]);
			var end = new Date(this.date[1]);
			console.log(this.date[0]);
			console.log(this.date[1]);
			var startDay = start.getDate();
			var startMonth = start.getMonth() + 1;
			var startYear = start.getFullYear();
			var endDay = end.getDate();
			var endMonth = end.getMonth() + 1;
			var endYear = end.getFullYear();

			this.filter.date_range = `${startYear}/${startMonth}/${startDay} - ${endYear}/${endMonth}/${endDay}`;
			this.getResults(route("payments.getPayments"));
			return `${startDay}/${startMonth}/${startYear} - ${endDay}/${endMonth}/${endYear}`;
		},
	},
	created() {
		console.log(this.data);
	},
};
</script>

<style scoped></style>
