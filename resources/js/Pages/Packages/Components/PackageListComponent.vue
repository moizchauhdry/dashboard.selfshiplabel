<template>
	<div class="table-responsive">
		<table class="table table-striped table-bordered text-center text-sm table-sm table-hover">
			<thead>
				<tr>
					<th scope="col">SR #</th>
					<th scope="col">Package ID</th>
					<th scope="col">Tracking Number</th>
					<th scope="col">Warehouse</th>
					<th scope="col">Status</th>
					<th scope="col">Customer</th>
					<th scope="col">Created Date</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(pkg, index) in pkgs.data" :key="pkg.id">
					<td>{{ ++index }}</td>
					<td style="width: 200px;">
						<span class="badge badge-primary text-sm">PKG #{{ pkg.id }}</span> <br>
						<template v-for="child_pkg in pkg.child_packages" :key="child_pkg.id">
							<span class="badge badge-info mr-1 mb-1" v-if="child_pkg.id != pkg.id">
								PKG #{{ child_pkg.id }}</span>
						</template>
					</td>
					<td>
						<div v-for="box in pkg.boxes" :key="box.id">
							<span v-if="box.tracking_out && pkg.carrier_code" class="font-bold text-primary underline">
								<a :href="'https://www.fedex.com/apps/fedextrack/?action=track&amp;trackingnumber=' + box.tracking_out"
									target="_blank" v-if="pkg.carrier_code == 'fedex'">
									{{ box.tracking_out }}</a>
								<a :href="'http://www.dhl.com/en/express/tracking.html?brand=DHL&amp;AWB=' + box.tracking_out"
									target="_blank" v-if="pkg.carrier_code == 'dhl'">
									{{ box.tracking_out }}</a>
								<a :href="'https://www.ups.com/track?loc=en_US&tracknum=' + box.tracking_out + '&requester=WT%2Ftrackdetails'"
									target="_blank" v-if="pkg.carrier_code == 'ups'">
									{{ box.tracking_out }}</a>
								<a :href="'https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=' + box.tracking_out + '%2C&tABt=false'"
									target="_blank" v-if="pkg.carrier_code == 'usps'">
									{{ box.tracking_out }}</a>
							</span>
							<span v-else>-</span>
						</div>
					</td>
					<td>
						{{ pkg?.warehouse?.name }}
					</td>
					<td>
						<span class="mr-1" :class="getLabelClass(pkg.status)">{{ pkg.status }}</span>
						<span class="badge badge-warning text-uppercase mr-1"
							v-if="pkg.pkg_type == 'consolidation' || pkg.pkg_type == 'multipiece'">{{ pkg.pkg_type }}</span>
						<span class="badge badge-success text-uppercase mr-1"
							v-if="pkg.payment_status == 'Paid'">Paid</span>
						<span class="badge badge-warning text-uppercase mr-1" v-if="pkg.auctioned == 1">Auctioned</span>
					</td>
					<td>
						<inertia-link :href="route('customers.show', pkg?.customer?.id)" class="btn btn-link">
							{{ pkg?.customer?.name }} - {{ siuteNum(pkg?.customer?.id) }}
						</inertia-link>
					</td>
					<td>{{ pkg.created_at }}</td>
					<td>
						<template v-if="pkg.pkg_type != 'assigned'">
							<inertia-link class="btn btn-info btn-sm m-1" :href="route('packages.show', pkg.id)">
								<i class="fa fa-list mr-1"></i>Detail</inertia-link>
							<template v-if="pkg.status != 'open'">
								<a class="btn btn-warning btn-sm m-1" :href="route('packages.pdf', pkg.id)" target="_blank">
									<i class="fa fa-print mr-1"></i>Print</a>
							</template>
						</template>
						<template v-else>
							<inertia-link class="btn btn-link" :href="route('packages.show', pkg.package_handler_id)">
								This package is assigned to PKG #{{ pkg.package_handler_id }}
							</inertia-link>
						</template>
					</td>
				</tr>
				<tr v-if="pkgs.data.length == 0">
					<td class="text-primary text-center" colspan="9">
						There are no packages found.
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
export default {
	name: "Packages List",
	props: {
		auth: Object,
		pkgs: Object,
		filter: Object,
	},
	data() {
		return {
			//
		};
	},
	methods: {
		getLabelClass(status) {
			switch (status) {
				case "pending":
					return "text-uppercase badge badge-warning text-white";
					break;
				case "open":
					return "text-uppercase badge badge-info text-white";
					break;
				case "filled":
					return "text-uppercase badge badge-info text-white";
					break;
				case "open":
					return "text-uppercase badge badge-success text-white";
					break;
				case "labeled":
					return "text-uppercase badge badge-success text-white";
					break;
				case "shipped":
					return "text-uppercase badge badge-primary p-1";
					break;
				case "delivered":
					return "text-uppercase badge badge-success text-white";
					break;
				case "consolidation":
					return "text-uppercase badge badge-danger text-white";
					break;
				case "served":
					return "label bg-success";
					break;
				case "rejected":
					return "label bg-danger";
					break;
				default:
					return "text-uppercase badge badge-primary text-white";
			}
		},
		siuteNum(user_id) {
			return 4000 + user_id;
		},
	},
};
</script>
