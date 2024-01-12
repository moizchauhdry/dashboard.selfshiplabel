<template>
    <div class="row">

        <!-- <div class="col-sm-4 col-md-4 col-lg-4"></div> -->
<div class="col-sm-12 col-md-12 col-lg-12">
     <!-- <div> -->
           
        <!-- </div> -->
    <breeze-validation-errors class="mb-4" />
    <form action="thank-you.php" method="post">

    <section class="stock-subscription">
    <inertia-link href="/">
                <img alt="shippingxps" width="237" height="55" src="theme/img/cropped-logo@2x.png" style="margin:auto;margin-bottom: 50px">
              <!--   <breeze-application-logo class="w-20 h-20 fill-current text-gray-500" /> -->
    </inertia-link>

      <div class="container">

        <h3 class="ss-heading">Select Your Subscription</h3>
        <p class="ss-para">
          Best of all, when you join Stock Advisor today, you’ll receive
          IMMEDIATE access to our latest stock picks. And if you give Stock
          Advisor a try and decide it’s not for you, that’s fine too. Simply
          cancel within 30 days and you’ll receive every penny of your
          membership fee back.
        </p>
        <div class="stock-subscription-form" style="margin:auto;width:70%;">
          <div class="row">
            <div class="col-md-6">
              <div class="stock-membership text-center">
                <div class="radio-button d-flex justify-content-center">
                  <label>
                    <input type="radio" name="subscription" value="99" checked="">
                  </label>
                </div>
                <div class="membership d-flex justify-content-center">
                  <p>$99/</p>
                  <p>1 year</p>
                </div>
                <div class="membership-meta">
                  <h4>Summit Stocks Advisor</h4>
                  <p>
                    Backed by our 30-day 100% membership fee-back guarantee
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="stock-membership text-center md-mrgn">
                <div class="radio-button d-flex justify-content-center">
                  <label>
                    <input type="radio" name="subscription" value="39" checked="">
                  </label>
                </div>
                <div class="membership d-flex justify-content-center">
                  <p>$39/</p>
                  <p>1 month</p>
                </div>
                <div class="membership-meta monthly-membership">
                  <h4>Summit Stocks Advisor</h4>
                  <p>
                    No refunds available for monthly memberships, cancel anytime
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="renew-membership text-center">
            <p>
              Your subscription will automatically renew at the then current
              price.
            </p>
          </div>
          
            <div class="order-form">
              <h5>Personal Info</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="first_name" id="first_name" type="text" class="form-control" placeholder="First Name" required="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="last_name" id="last_name" type="text" class="form-control" placeholder="Last Name" required="">
                  </div>
                </div>
              </div>
            </div>
            <div class="billing-form">
              <h5>Billing Address</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address1" id="address1" type="text" class="form-control" placeholder="Address 1" required="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address2" id="address2" type="text" class="form-control" placeholder="Address 2">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="city" id="city" type="text" class="form-control" placeholder="City" required="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <select name="state" id="state" class="form-select" required="">
                      <option selected="">Select State</option>                      
                      <option value="AL">Alabama</option><option value="AK">Alaska</option>
                      <option value="AS">American Samoa</option><option value="AZ">Arizona</option>
                      <option value="AR">Arkansas</option><option value="AA">Armed Forces Americas</option>
                      <option value="AE">Armed Forces Europe</option><option value="AP">Armed Forces Pacific</option>
                      <option value="CA">California</option><option value="CO">Colorado</option>
                      <option value="CT">Connecticut</option><option value="DE">Delaware</option>
                      <option value="DC">District of Columbia</option><option value="FL">Florida</option>
                      <option value="GA">Georgia</option><option value="GU">Guam</option>
                      <option value="HI">Hawaii</option><option value="ID">Idaho</option>
                      <option value="IL">Illinois</option><option value="IN">Indiana</option>
                      <option value="IA">Iowa</option><option value="KS">Kansas</option>
                      <option value="KY">Kentucky</option><option value="LA">Louisiana</option>
                      <option value="ME">Maine</option><option value="MD">Maryland</option>
                      <option value="MA">Massachusetts</option><option value="MI">Michigan</option>
                      <option value="MN">Minnesota</option><option value="MS">Mississippi</option>
                      <option value="MO">Missouri</option><option value="MT">Montana</option>
                      <option value="NE">Nebraska</option><option value="NV">Nevada</option>
                      <option value="NH">New Hampshire</option><option value="NJ">New Jersey</option>
                      <option value="NM">New Mexico</option><option value="NY">New York</option>
                      <option value="NC">North Carolina</option><option value="ND">North Dakota</option>
                      <option value="MP">Northern Mariana Islands</option><option value="OH">Ohio</option>
                      <option value="OK">Oklahoma</option><option value="OR">Oregon</option>
                      <option value="PA">Pennsylvania</option><option value="PR">Puerto Rico</option>
                      <option value="RI">Rhode Island</option><option value="SC">South Carolina</option>
                      <option value="SD">South Dakota</option><option value="TN">Tennessee</option>
                      <option value="TX">Texas</option><option value="UT">Utah</option>
                      <option value="VT">Vermont</option><option value="VI">Virgin Islands</option>
                      <option value="VA">Virginia</option>
                      <option value="WA">Washington</option><option value="WV">West Virginia</option>
                      <option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <select name="country" id="country" class="form-select" required="">
                      <option selected="">Country</option>
                      <option value="USA">United States Of America</option>
                      <option value="Canada">Canada</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="Postal Code" required="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="Phone No" required="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="email" name="email_address" id="email_address" class="form-control" placeholder="Email Address" required="">
                  </div>
                </div>
              </div>


            </div>
            <div class="credit-form">
              <h5>Credit Card</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name on Card" required="">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="ccnumber" name="ccnumber" data-inputmask="'mask': '9999 9999 9999 9999'" class="form-control" placeholder="Card Number" required="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select id="exp_month" name="exp_month" class="form-select" required="">
                      <option selected="">Expiration Month</option>
                      <option value="01">Jan</option><option value="02">Feb</option>
                      <option value="03">Mar</option><option value="04">Apr</option>
                      <option value="05">May</option><option value="06">Jun</option>
                      <option value="07">Jul</option><option value="08">Aug</option>
                      <option value="09">Sep</option><option value="10">Oct</option>
                      <option value="11">Nov</option><option value="12">Dec</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <select id="exp_year" name="exp_year" class="form-select" required="">
                      <option selected="">Expiration Year</option>
                      <option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option> 
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input name="cvv" id="cvv" type="text" data-inputmask="'mask': '9999'" class="form-control" placeholder="CVV" required="">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-meta">
              <h5>Important Notes</h5>
              <p>
                By clicking Submit Order, you are agreeing to our
                <a href="javascript:void()">Terms and Conditions</a> and
                <a href="javascript:void()">Privacy Policy</a>.
              </p>
              <p>
                Sales tax may apply. For more information, please see our tax
                <a href="javascript:void()">FAQs.</a>
              </p>
              <p>
                Your subscription will automatically renew at the then current
                price, using the most recent credit card we have on file.
                Subscriptions purchased with Apple Pay will renew using Apple
                Pay. All prices quoted are in U.S. dollars.
              </p>
              <p>
                If there is no refund offer or credit transfer offer stated on
                this order page, none will apply for the purchase of this
                product.
              </p>
              <p>
                To cancel or request changes to your subscription please contact
                us here. Please note that some subscriptions can be managed
                online via your My Account page.
              </p>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" required="">
                <label class="form-check-label" for="exampleCheck1">I agree to The Summit Stock
                  <a href="javascript:void()">Terms and Conditions</a> and
                  <a href="javascript:void()">Privacy Policy</a>.</label>
              </div>
            </div>
            <div class="order-button text-center">
              <input type="submit" value="Submit my order" class="btn btn-danger">
            </div>
            <div class="security-encrypted d-flex align-items-center justify-content-center">
              <svg class="icon icon-lock" width="12" height="12">
                <use href="https://summitstocks.com/lp/summit-stock/assets/images/sprite.svg#lock"></use>
              </svg>
              <p>
                Your order information is securely encrypted.
              </p>
            </div>
            <div class="call-us text-center">
              <a href="#" title="call us">Need assistance? Call us toll-free at (855) 692-3665.</a>
            </div>
          
        </div>
      </div>
    </section>
   </form>
            
        </div>
    </div>
  

    
</template>

<script>
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from '@/Layouts/Guest'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        // layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },

        props: {
            auth: Object,
            errors: Object,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
