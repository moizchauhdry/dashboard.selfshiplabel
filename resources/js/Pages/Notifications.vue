<template>
	<MainLayout>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
			Notifications
		</h2>
		<div class="row" v-if="notifications.length" style="margin-top: 20px">
			<div class="col mb-5">
				<inertia-link :href="route('notifications.mark-all-read')" class="float-right"><i
						class="far fa-bookmark mr-1"></i>Mark all as read</inertia-link>
				<div class="d-block" v-for="notificationData in notifications" :key="notificationData.date">
					<h3 class="notification-date">
						<strong>{{ notificationData.date }}</strong>
					</h3>
					<hr />
					<div v-for="notification in notificationData.notifications" :key="notification.id">
						<div class="card card-notification" :class="notification.read_at == null
								? 'card-notification-unread'
								: 'card-notification-success'
							" style="margin-top: 5px">
							<div class="card-body">
								<h5 class="title" v-html="notification.content"></h5>
								<div class="float-end d-inline-flex mt-3">
									<div v-on:click="markAsRead($event, notification)" v-if="notification.read_at == null"
										class="mark-as-read">
										Mark as read&nbsp;
									</div>
									<div class="ms-3 date-section">
										{{ notification.created_at }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center">
					<a href="javascript:void(0)" v-if="totalPage > currentPage" class="btn btn-dark mt-3 mb-5"
						@click="loadMore(currentPage)">Load More</a>
				</div>
			</div>
		</div>
		
		<div v-if="notifications.length == 0">
			<h6>There are no notifications</h6>
		</div>
	</MainLayout>
</template>
<style scoped>
.row {
	margin-right: 0px !important;
}

.click-here {
	color: orangered;
}

.card-notification {
	border-radius: 7px !important;
}

.notification-date {
	font-size: 18px;
	margin-top: 20px;
}

.card-notification-success {
	border-left: solid 5px #282828;
}

.card-notification-unread {
	border-left: solid 5px #282828;
}

.card-notification-success {
	background: #fff;
}

.card-notification-unread {
	background: rgba(40, 40, 40, 0.2);
}

.card-notification .card-body {
	padding: 10px 15px;
}

.card-notification .title {
	text-transform: none;
}

.mark-as-read {
	color: #000000;
	font-weight: 600;
}

.mark-as-read:hover {
	text-decoration: underline;
	cursor: pointer;
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
		notifications: Object,
		totalPage: Object,
		currentPageProp: Object,
	},
	data() {
		return {
			form: this.$inertia.form({
				notification_id: "",
			}),
			totalPage: this.totalPage,
			currentPage: this.currentPageProp,
			notifications: this.notifications,
		};
	},
	methods: {
		markAsRead(event, notification) {
			let loadMore = this.loadMore(this.currentPage, 1);
			axios
				.post(this.route("notifications.mark-read"), {
					notification_id: notification.id,
				})
				.then(function (response) {
					setTimeout(function () {
						loadMore;
					}, 500);
				})
				.catch(function (error) {
					console.log(error);
				});
		},
		loadMore(pageNumber, same = 0) {
			let number = same == 1 ? pageNumber : ++pageNumber;
			axios
				.get(this.route("notifications") + "?current_page=" + number, {
					parms: { current_page: number },
				})
				.then((response) => {
					console.log(response);
					let data = response.data;
					this.currentPage = data.currentPage;
					this.notifications = data.notifications;

					console.log(data.notifications);
					console.log(this.notifications);
				})
				.catch((error) => {
					console.log(error);
				});
		},
	},
};
</script>
