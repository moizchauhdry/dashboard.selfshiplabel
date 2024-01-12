<template>
	<header-component></header-component>
	<div role="main" class="main">
		<section
			class="page-header page-header-modern page-header-background page-header-background-md parallax overlay overlay-color-dark overlay-show overlay-op-5 mt-0"
			data-plugin-parallax data-plugin-options="{'speed': 1.2}"
			data-image-src="img/page-header/page-header-parallax.jpg">
			<div class="container">
				<div class="row">
					<div class="col-md-8 order-2 order-md-1 align-self-center p-static">
						<h1>Contact Us <strong>Advanced</strong></h1>
						<span class="sub-title">Get in touch with us</span>
					</div>
					<div class="col-md-4 order-1 order-md-2 align-self-center">
						<ul class="breadcrumb breadcrumb-light d-block text-md-right">
							<li><a href="#">Home</a></li>
							<li class="active">Pages</li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<div class="container">

			<div class="row py-4">
				<div class="col-lg-7 ">

					<div class="offset-anchor" id="contact-sent"></div>


					<h2 class="font-weight-bold text-7 mt-2 mb-0">Contact Us</h2>
					<p class="mb-4">Feel free to ask for details, don't save any questions!</p>

					<form id="contactFormAdvanced" action="<?php echo basename($_SERVER['PHP_SELF']); ?>#contact-sent"
						method="POST" enctype="multipart/form-data">
						<input type="hidden" value="true" name="emailSent" id="emailSent">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="mb-1 text-2">Full Name</label>
								<input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
									class="form-control text-3 h-auto py-2" name="name" id="name" required>
							</div>
							<div class="form-group col-md-6">
								<label class="mb-1 text-2">Email Address</label>
								<input type="email" value="" data-msg-required="Please enter your email address."
									data-msg-email="Please enter a valid email address." maxlength="100"
									class="form-control text-3 h-auto py-2" name="email" id="email" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="mb-1 text-2">Subject</label>
								<select data-msg-required="Please enter the subject."
									class="form-control text-3 h-auto py-2" name="subject" id="subject" required>
									<option value="Cancellation &amp; Billing">Cancellation &amp; Billing</option>
									<option value="Purchase a service">Purchase a service</option>
									<option value="More information about Summit Stock services">More information about
										Summit Stock services</option>
									<option value="Trouble logging in">Trouble logging in</option>
									<option value="Issue with Scorecard">Issue with Scorecard</option>
									<option value="Change my email preferences">Change my email preferences</option>
									<option value="Change my account information">Change my account information</option>
									<option value="Personal Data Request">Personal Data Request</option>
									<option value="European Personal Data Request">European Personal Data Request</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-6">
								<p class="mb-2">Checkboxes</p>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" name="checkboxes[]" type="checkbox"
											data-msg-required="Please select at least one option." id="inlineCheckbox1"
											value="option1"> 1
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" name="checkboxes[]" type="checkbox"
											data-msg-required="Please select at least one option." id="inlineCheckbox1"
											value="option2"> 2
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" name="checkboxes[]" type="checkbox"
											data-msg-required="Please select at least one option." id="inlineCheckbox1"
											value="option3"> 3
									</label>
								</div>
							</div>
							<div class="form-group col-md-6">
								<p class="mb-2">Radios</p>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="radios"
											data-msg-required="Please select at least one option." id="inlineRadio1"
											value="option1"> 1
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="radios"
											data-msg-required="Please select at least one option." id="inlineRadio2"
											value="option2"> 2
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" name="radios"
											data-msg-required="Please select at least one option." id="inlineRadio3"
											value="option3"> 3
									</label>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label class="mb-1 text-2">Attachment</label>
								<input class="d-block" type="file" name="attachment" id="attachment">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12 mb-4">
								<label class="mb-1 text-2">Message</label>
								<textarea maxlength="5000" data-msg-required="Please enter your message." rows="6"
									class="form-control text-3 h-auto py-2" name="message" id="message" required></textarea>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12 mb-0">
								<label class="mb-1 text-2">Human Verification</label>
							</div>
						</div>
						<div class="form-row pt-2">
							<div class="form-group col-md-4">
								<div class="captcha form-control text-3 h-auto py-2">
									<div class="captcha-image">

									</div>
									<div class="captcha-refresh">
										<a href="#" id="refreshCaptcha"><i class="fas fa-sync"></i></a>
									</div>
								</div>
							</div>
							<div class="form-group col-md-8">
								<input type="text" value="" maxlength="6" data-msg-captcha="Wrong verification code."
									data-msg-required="Please enter the verification code."
									placeholder="Type the verification code."
									class="form-control text-3 h-auto py-2 form-control-lg captcha-input" name="captcha"
									id="captcha" required>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12">
								<hr>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-12 mb-5">
								<input type="submit" id="contactFormSubmit" value="Send Message"
									class="btn btn-primary btn-modern pull-right" data-loading-text="Loading...">
							</div>
						</div>
					</form>

				</div>

				<div class="col-lg-5">

					<div class="overflow-hidden mb-1">
						<h4 class="text-primary mb-0 " data-="maskUp" data--delay="200">Get in <strong>Touch</strong></h4>
					</div>
					<div class="overflow-hidden mb-3">
						<p class="lead text-4 mb-0 " data-="maskUp" data--delay="400">Lorem ipsum dolor sit amet,
							consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae
							velit congue iaculis vitae at risus.</p>
					</div>
					<div class="overflow-hidden">
						<p class="mb-0 " data-="maskUp" data--delay="600">Lorem ipsum dolor sit amet, consectetur adipiscing
							elit. Curabitur eget leo at velit imperdiet varius.</p>
					</div>

					<div class="" data-="fadeInUpShorter" data--delay="800">
						<h4 class="text-primary pt-5">Our <strong>Office</strong></h4>
						<ul class="list list-icons list-icons-style-3 mt-2">
							<li><i class="fas fa-map-marker-alt top-6"></i> <strong>Address:</strong> 1234 Street Name, City
								Name, United States</li>
							<li><i class="fas fa-phone top-6"></i> <strong>Phone:</strong> (123) 456-789</li>
							<li><i class="fas fa-envelope top-6"></i> <strong>Email:</strong> <a
									href="mailto:mail@example.com">mail@example.com</a></li>
						</ul>

						<div class="row lightbox mt-4 mb-0 pb-0"
							data-plugin-options="{'delegate': 'a', 'type': 'image', 'gallery': {'enabled': true}}">
							<div class="col-md-4 mb-4 mb-md-0">
								<a class="img-thumbnail p-0 border-0 d-block" href="img/office/our-office-1.jpg"
									data-plugin-options="{'type':'image'}">
									<img class="img-fluid" src="img/office/our-office-1.jpg" alt="Office">
								</a>
							</div>
							<div class="col-md-4 mb-4 mb-md-0">
								<a class="img-thumbnail p-0 border-0 d-block" href="img/office/our-office-2.jpg"
									data-plugin-options="{'type':'image'}">
									<img class="img-fluid" src="img/office/our-office-2.jpg" alt="Office">
								</a>
							</div>
							<div class="col-md-4">
								<a class="img-thumbnail p-0 border-0 d-block" href="img/office/our-office-3.jpg"
									data-plugin-options="{'type':'image'}">
									<img class="img-fluid" src="img/office/our-office-3.jpg" alt="Office">
								</a>
							</div>
						</div>

						<h4 class="text-primary pt-5">Business <strong>Hours</strong></h4>
						<ul class="list list-icons list-dark mt-2">
							<li><i class="far fa-clock top-6"></i> Monday - Friday - 9am to 5pm</li>
							<li><i class="far fa-clock top-6"></i> Saturday - 9am to 2pm</li>
							<li><i class="far fa-clock top-6"></i> Sunday - Closed</li>
						</ul>
					</div>

				</div>

			</div>

		</div>
	</div>
	<footer-component showTicker=false></footer-component>
</template>
<script>
import HeaderComponent from '../Components/Header.vue'
import FooterComponent from '../Components/Footer.vue'
export default {
	components: {
		'header-component': HeaderComponent,
		'footer-component': FooterComponent,
	},
	props: {
		errors: Object,
		post: Object,
	},
	data() {
		return {

		}
	},
	computed: {
	},
	methods: {


	}
}
</script>