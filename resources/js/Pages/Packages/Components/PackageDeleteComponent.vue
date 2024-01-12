<template>
	<button
		v-if="
			$page.props.auth.user.type == 'admin' ||
			$page.props.auth.user.type == 'manager'
		"
		class="btn btn-danger float-left mb-4"
		v-show="
			packag.status == 'open' ||
			packag.status == 'filled' ||
			packag.status == 'labeled'
		"
		v-on:click="confirmation()"
	>
		<i class="fa fa-trash mr-1"></i>Delete Package
	</button>

	<div
		v-if="
			$page.props.auth.user.type == 'admin' ||
			$page.props.auth.user.type == 'manager'
		"
		class="modal fade"
		id="package_delete_modal"
		tabindex="-1"
		role="dialog"
		aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true"
		data-backdrop="true"
	>
		<div class="modal-dialog border" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Delete Package</h5>
					<button
						type="button"
						@click="close()"
						class="close"
						data-dismiss="modal"
						aria-label="Close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h3 class="text-center mb-2">
						Are You sure you want to delete this Package
						<br />
						You can't undo this action.
					</h3>
					<div class="alert alert-warning" role="alert">
						<h3 class="alert-heading">Warning!</h3>
						<p>
							By deleting this package it will delete all the data including
							images, orders, services etc.
						</p>
					</div>
				</div>
				<div class="modal-footer">
					<button
						type="button"
						class="btn btn-dark"
						@click="close()"
						data-dismiss="modal"
					>
						Cancel
					</button>
					<button
						type="button"
						class="btn btn-danger"
						@click="delete_package()"
					>
						Delete
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import $ from "jquery";

	export default {
		name: "Package Delete Component",
		props: {
			packag: Object,
		},
		data() {
			return {
				package_delete_form: this.$inertia.form({
					package_id: this.packag.id,
				}),
			};
		},
		methods: {
			confirmation() {
				var modal = document.getElementById("package_delete_modal");
				modal.classList.add("show");
				$("#package_delete_modal").show();
			},
			close() {
				var modal = document.getElementById("package_delete_modal");
				modal.style.display = "none";
			},
			delete_package() {
				this.package_delete_form.post(this.route("packages.destroy"));
			},
		},
	};
</script>
