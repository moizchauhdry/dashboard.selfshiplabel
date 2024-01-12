<template>
	<breeze-validation-errors class="mb-4" />

	<form @submit.prevent="submit">
		<div>
			<breeze-label for="name" value="Name" />
			<breeze-input
				id="name"
				type="text"
				class="mt-1 block w-full"
				v-model="form.name"
				required
				autofocus
				autocomplete="name"
			/>
		</div>

		<div class="mt-4">
			<breeze-label for="email" value="Email" />
			<breeze-input
				id="email"
				type="email"
				class="mt-1 block w-full"
				v-model="form.email"
				required
				autocomplete="username"
			/>
		</div>

		<div class="mt-4">
			<breeze-label for="email" value="Phone" />
			<vue-tel-input
				v-model="form.phone_no"
				mode="international"
				class="mt-1 block w-full"
				required
				default-country="US"
				:valid-characters-only="true"
			></vue-tel-input>
		</div>

		<div class="mt-4">
			<breeze-label for="password" value="Password" />
			<breeze-input
				id="password"
				type="password"
				class="mt-1 block w-full"
				v-model="form.password"
				required
				autocomplete="new-password"
			/>
		</div>

		<div class="mt-4">
			<breeze-label for="password_confirmation" value="Confirm Password" />
			<breeze-input
				id="password_confirmation"
				type="password"
				class="mt-1 block w-full"
				v-model="form.password_confirmation"
				required
				autocomplete="new-password"
			/>
		</div>

		<div class="form-group col-md-12 mt-4">
			<breeze-label for="hear_from" value="How did you hear about us?" />
			<select
				class="form-control mt-1 block w-full"
				name="type"
				v-model="form.hear_from"
				id="type"
				required
			>
				<option value="" selected>--Please Select--</option>
				<option value="Google">Google</option>
				<option value="Instagram">Instagram</option>
				<option value="Twitter">Twitter</option>
				<option value="Youtube">Youtube</option>
				<option value="Articles & Blogs">Articles & Blogs</option>
				<option value="Friend Recommendation">Friend Recommendation</option>
				<option value="Other">Other</option>
			</select>
		</div>

		<div class="flex items-center justify-end mt-4">
			<inertia-link
				:href="route('login')"
				class="underline text-sm text-gray-600 hover:text-gray-900"
			>
				Already registered? Login now
			</inertia-link>

			<breeze-button
				id="register_btn"
				class="ml-4"
				:class="{ 'opacity-25': form.processing }"
				:disabled="form.processing"
			>
				Register
			</breeze-button>
		</div>
	</form>
</template>

<script>
	import BreezeButton from "@/Components/Button";
	import BreezeGuestLayout from "@/Layouts/Guest";
	import BreezeInput from "@/Components/Input";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";
	import { VueTelInput } from "vue-tel-input";
	import "vue-tel-input/dist/vue-tel-input.css";

	export default {
		layout: BreezeGuestLayout,

		components: {
			BreezeButton,
			BreezeInput,
			BreezeLabel,
			BreezeValidationErrors,
			VueTelInput,
		},

		props: {
			auth: Object,
			errors: Object,
		},

		data() {
			return {
				form: this.$inertia.form({
					name: "",
					email: "",
					phone_no: "",
					password: "",
					password_confirmation: "",
					hear_from: "",
					captcha_token: "",
				}),
			};
		},

		methods: {
			submit() {
				// this.form.post(this.route('register'), {
				//     onFinish: () => this.form.reset('password', 'password_confirmation'),
				// })

				let submitForm = (token) => {
					this.form.captcha_token = token;
					this.form.post(this.route("register"), {
						onFinish: () =>
							this.form.reset("password", "password_confirmation"),
					});
				};

				grecaptcha
					.execute("6LcKxb0hAAAAALPcMiT1eLu03DnQfxaluzJhgD8F", {
						action: "submit",
					})
					.then(function (token) {
						submitForm(token);
					});
			},
		},
	};
</script>
