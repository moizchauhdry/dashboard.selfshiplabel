<template>
	<MainLayout>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<b>Packages</b>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<form @submit.prevent="submit">
								<div class="d-flex search">

									<div class="form-group">
										<label for="">Package Number</label>
										<input type="number" name="number" v-model="form.pkg_id" class="form-control" />
									</div>
									<div class="form-group">
										<label for="">Customer Number</label>
										<input type="number" name="number" v-model="form.suit_no"
											class="form-control" />
									</div>
									<div class="form-group">
										<label for="">Payment Status</label>
										<select class="form-control custom-select" v-model="form.payment_status">
											<option value="" selected>All</option>
											<option value="Paid">Paid</option>
											<option value="Pending">Pending</option>
										</select>
									</div>
									<div class="form-group">
										<label for="">Tracking Number</label>
										<input type="text" v-model="form.tracking_out" class="form-control" />
									</div>
									<div class="form-group">
										<label for="">Date Range</label>
										<Datepicker v-model="date" range :format="format" :enableTimePicker="false">
										</Datepicker>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<button type="submit" class="btn btn-primary mr-1">Search</button>
										<button type="button" class="btn btn-info" @click="clear()">Clear</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<package-list-component v-bind="$props"></package-list-component>
				</div>
				<div class="card-footer">
					<span class="float-left"><b>Showing Records: {{ packages_count }}</b></span>
					<pagination :links="pkgs.links" class="float-right"></pagination>
				</div>
			</div>
		</div>
	</MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import { Inertia } from "@inertiajs/inertia";
import PackageListComponent from "./Components/PackageListComponent.vue";
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";
import Pagination from "@/Components/Pagination.vue";

export default {
	components: {
		BreezeAuthenticatedLayout,
		MainLayout,
		PackageListComponent,
		Datepicker,
		Pagination
	},
	props: {
		auth: Object,
		pkgs: Object,
		open_pkgs_count: Object,
		packages_count: Object,
		filters: Object,
		projects: Object,
	},
	data() {
		return {
			active: "packages",
			date: "",
			form: {
				// project_id: this.filters.project_id,
				pkg_id: this.filters.pkg_id,
				suit_no: this.filters.suit_no,
				pkg_status: this.filters.pkg_status,
				pkg_type: this.filters.pkg_type,
				payment_status: this.filters.payment_status,
				auctioned: this.filters.auctioned,
				tracking_out: this.filters.tracking_out,
				date_range: this.filters.date_range,
			},
		};
	},
	methods: {
		submit() {
			const queryParams = new URLSearchParams(this.form);
			const url = `${route("packages.index")}?${queryParams.toString()}`;
			Inertia.visit(url, { preserveState: true });
		},
		siuteNum(user_id) {
			return 4000 + user_id;
		},
		clear() {
			this.form.project_id = "";
			this.form.pkg_id = "";
			this.form.suit_no = "";
			this.form.pkg_status = "";
			this.form.pkg_type = "";
			this.form.payment_status = "";
			this.form.date_range = "";
			this.form.tracking_out = "";
			this.date = "";
			this.submit();
		},
		format() {
			var start = new Date(this.date[0]);
			var end = new Date(this.date[1]);
			var startDay = start.getDate();
			var startMonth = start.getMonth() + 1;
			var startYear = start.getFullYear();
			var endDay = end.getDate();
			var endMonth = end.getMonth() + 1;
			var endYear = end.getFullYear();

			this.form.date_range = `${startYear}/${startMonth}/${startDay} - ${endYear}/${endMonth}/${endDay}`;
			return `${startDay}/${startMonth}/${startYear} - ${endDay}/${endMonth}/${endYear}`;
		},
	},
};
</script>

<style>
button.active.btn.btn-light.w-100 {
	background-color: red !important;
	color: white;
}

.dp__input {
	background-color: var(--dp-background-color);
	border-radius: 0px;
	font-family: -apple-system, blinkmacsystemfont, "Segoe UI", roboto, oxygen, ubuntu, cantarell, "Open Sans", "Helvetica Neue", sans-serif;
	border: 1px solid var(--dp-border-color);
	outline: none;
	transition: border-color .2s cubic-bezier(0.645, 0.045, 0.355, 1);
	width: 100%;
	font-size: 1rem;
	line-height: 1.5rem;
	padding: 4px 33px;
	color: var(--dp-text-color);
	box-sizing: border-box;
}

.label {
	padding: 5px;
}

.search .form-group {
	margin-left: 1px
}
</style>
