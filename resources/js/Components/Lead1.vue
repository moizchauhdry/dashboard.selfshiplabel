<template>
    <MainLayout>
      <div>
        <!-- STOCK SUBSCRIPTION -->
    
    <section class="stock-subscription">
      <div class="container">
       <!--  <h3 class="ss-heading">Select Your Subscription</h3>
        <p class="ss-para">
          Best of all, when you join Stock Advisor today, you’ll receive
          IMMEDIATE access to our latest stock picks. And if you give Stock
          Advisor a try and decide it’s not for you, that’s fine too. Simply
          cancel within 30 days and you’ll receive every penny of your
          membership fee back.
        </p> -->
        <div class="stock-subscription-form">
            <form @submit.prevent="submit">
          <div class="row">
            <div class="col-md-6">
              <div class="stock-membership text-center">
                <div class="radio-button d-flex justify-content-center">
                  <label>
                    <input type="radio" name="subscription" value="99" v-model="form.subscription" />
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
                    <input type="radio" name="subscription" value="39" v-model="form.subscription"/>
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
                    <input name="first_name" id="first_name" type="text" class="form-control" placeholder="First Name" v-model="form.first_name" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="last_name" id="last_name" type="text" class="form-control" placeholder="Last Name" v-model="form.last_name" required />
                  </div>
                </div>
              </div>
            </div>
            <div class="billing-form">
              <h5>Billing Address</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address1" id="address1" type="text" class="form-control" placeholder="Address 1" required v-model="form.address1" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address2" id="address2" type="text" v-model="form.address2" class="form-control" placeholder="Address 2" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="city" id="city" v-model="form.city" type="text" class="form-control" placeholder="City" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <select name="state" id="state" class="form-select" v-model="form.state" required>
                      <option selected>Select State</option>                      
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
                    <select name="country" id="country" class="form-select" v-model="form.country"  required>
                      <option selected>Country</option>
                      <option value="USA">United States Of America</option>
                      <option value="Canada">Canada</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="postal_code" name="postal_code" class="form-control" v-model="form.postal_code" placeholder="Postal Code" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="phone_no" name="phone_no" v-model="form.phone_no" class="form-control" placeholder="Phone No"  required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="email" v-model="form.email" name="email_address" id="email_address" class="form-control" placeholder="Email Address" required  />
                  </div>
                </div>
              </div>


            </div>
            <div class="credit-form">
              <h5>Credit Card</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control" v-model="form.name_on_card"  id="name_on_card" name="name_on_card" placeholder="Name on Card" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="ccnumber" v-model="form.ccnumber" name="ccnumber" data-inputmask="'mask': '9999 9999 9999 9999'" class="form-control" placeholder="Card Number"  required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select id="exp_month" name="exp_month" v-model="form.exp_month" class="form-select" required style="width:100%">
                      <option selected>Expiration Month</option>
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
                    <select id="exp_year" v-model="form.exp_year"  name="exp_year" class="form-select" required style="width:100%">
                       <option selected>Expiration Year</option>
                      <option v-for="year in years" :value="year">{{ year }}</option>
                     
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input name="cvv" id="cvv" type="text" v-model="form.cvv" data-inputmask="'mask': '9999'" class="form-control" placeholder="CVV" required />
                  </div>
                </div>
              </div>
            </div>
          <!--   <div class="form-meta">
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
                <input type="checkbox" class="form-check-input" id="exampleCheck1" required />
                <label class="form-check-label" for="exampleCheck1">I agree to The Summit Stock
                  <a href="javascript:void()">Terms and Conditions</a> and
                  <a href="javascript:void()">Privacy Policy</a>.</label
                >
              </div>
            </div> -->
            <div class="order-button text-center">
              <input type="submit" value="Update" class="btn btn-danger" />
            </div>
           <!--  <div class="security-encrypted d-flex align-items-center justify-content-center">
              <svg class="icon icon-lock" width="12" height="12">
                <use href="/assets/images/sprite.svg#lock'"></use>
              </svg>
              <p>
                Your order information is securely encrypted.
              </p>
            </div>
            <div class="call-us text-center">
              <a href="#" title="call us"
                >Need assistance? Call us toll-free at (855) 692-3665.</a
              >
            </div> -->
          </form>
        </div>
      </div>
    </section>
    
          
      </div>
 
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from '@/Layouts/Guest'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
            BreezeAuthenticatedLayout,
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
            MainLayout
        },
         props: {
            errors: Object,
            lead: Object,
        },
    data() {
            return {
                form: this.$inertia.form({
                    first_name: this.lead.first_name,
                    last_name: this.lead.last_name,
                    address1: this.lead.address1,
                    address2: this.lead.address2,
                    city: this.lead.city,
                    state: this.lead.state,
                    city: this.lead.city,
                    country: this.lead.country,
                    postal_code: this.lead.postal_code,
                    phone_no: this.lead.phone_no,
                    name_on_card: this.lead.name_on_card,
                    ccnumber: this.lead.ccnumber,
                    exp_month: this.lead.exp_month,
                    exp_year: this.lead.exp_year,
                    cvv: this.lead.cvv,
                    email: this.lead.email,
                    subscription: this.lead.subscription,
                    id:this.lead.id
                }),
                currentYear: new Date().getFullYear(),
                Years : [],
            }
        },
        computed : {
            years () {
              const year = new Date().getFullYear()
              return Array.from({length: year - 2000}, (value, index) => year + index)
            }
          },
    methods: {
            submit() {
                this.form.put(this.route('lead',this.lead.id))
                // this.form.post(this.route('Createlead'), {
                //     onFinish: () => this.form.reset(),
                // })
            },

        }
    }
</script>
