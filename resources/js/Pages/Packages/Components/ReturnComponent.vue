<template>
	<div class="row" v-if="packag.pkg_type == 'single' && ($page.props.auth.user.type == 'customer' || packag.return_package == 1)">
		<div class="col-md-12">
			<button @click="open()" class="text-lg text-primary"><i class="fa fa-list mr-1"></i>Return Package</button>
			<div class="modal fade" id="return_to_sender" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="true">
				<div class="modal-dialog border" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Return Package To Sender</h5>
							<button type="button" @click="close()" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<template v-if="packag.return_package == 0">
								<breeze-validation-errors class="mb-4" />
								<div class="form-group">
									<label for="">Do You Have Label?</label>
									<select v-model="form.return_label" class="form-control custom-select">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>
								<div class="form-group" v-if="form.return_label == 1">
									<input type="file" @input="form.return_label_file = $event.target.files[0]" />
									<progress v-if="form.progress" :value="form.progress.percentage" max="100">{{ form.progress.percentage }}%</progress>
								</div>
							</template>
							<div class="form-group" v-if="packag.return_label == 1">
								<label><b>Label File</b></label>
								<a :href="fileUrl(packag.return_label_file)" class="form-control" download>
									<i class="fas fa-download"></i> {{packag.return_label_file}}</a>
							</div>
							<div v-if="packag.return_package == 1 && $page.props.auth.user.type == 'customer'">
								<span>The request for the package to be returned to the sender has been sent.</span>
							</div>
							<div v-if="packag.return_package == 1 && $page.props.auth.user.type == 'admin'">
								<span>The customer has requested to return package to sender.</span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" @click="close()" data-dismiss="modal">
								{{packag.return_package == 0 ? 'Cancel' : 'Close'}}</button>
							<button type="button" class="btn btn-primary" @click="submit()" v-if="packag.return_package == 0 && packag.payment_status != 'Paid'">Submit</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import $ from "jquery";
	import BreezeValidationErrors from "@/Components/ValidationErrors";

	export default {
		name: "Return Component",
		props: {
			packag: Object,
		},
		components: {
			BreezeValidationErrors,
		},
		data() {
			return {
				form: this.$inertia.form({
					package_id: this.packag.id,
					return_label: 0,
					return_label_file: null,
				}),
			};
		},
		methods: {
			fileUrl(url) {
				return "/public/uploads/" + url;
			},
			open() {
				var modal = document.getElementById("return_to_sender");
				modal.classList.add("show");
				$("#return_to_sender").show();
			},
			close() {
				var modal = document.getElementById("return_to_sender");
				modal.style.display = "none";
			},
			submit() {
				this.form.post(this.route("packages.return-package"));
			},
		},
	};
</script>
