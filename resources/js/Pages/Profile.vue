<template>
    <MainLayout>
    <div>
    
        <section>
      <div class="container">

        <h2>Update Profile</h2>

        <div class="stock-subscription-form">
          <form @submit.prevent="submit">          
            <div class="order-form">                
                <breeze-validation-errors class="mb-4" />
                <flash-messages ></flash-messages>
                
                <div class="row">

                    <div class="form-group col-md-6">
                      <breeze-label for="name" value="Name" />
                      <input name="name" id="name" type="text" class="form-control" placeholder="Name" v-model="form.name" required />
                    </div>

                    <div class="form-group col-md-6">
                      <breeze-label for="first_name" value="Email" />
                      <input name="email" id="email" type="text" class="form-control" placeholder="Name" v-model="form.email" required :disabled="true" />
                    </div>

                    <div class="form-group col-md-6">
                      <breeze-label for="phone_no" value="Phone" />
                      <input name="phone_no" id="phone_no" type="text" class="form-control" placeholder="Phone" v-model="form.phone_no" required />
                    </div>


                  <div class="col-md-6">


                    <!-- <div class="form-group">
                      <breeze-label for="country" value="Country" />
                      <select required  v-model="form.country" class="form-control" aria-label="Default select example">
                        <template v-for="country in countries" :key="country">
                          <option  :value="country.name" >{{ country.name}}</option>
                        </template>
                      </select>
                    </div>

                    <div class="form-group">
                      <breeze-label for="state" value="State" />
                      <input name="state" id="state" type="text" class="form-control" placeholder="State" v-model="form.state" required />
                    </div>

                    <div class="form-group">
                      <breeze-label for="city" value="City" />
                      <input name="city" id="city" type="text" class="form-control" placeholder="City" v-model="form.city" required />
                    </div> -->


                  </div>                
                
                  <div class="col-md-6">


                    <!-- <div class="form-group">
                      <breeze-label for="postal_code" value="Zip Code" />
                      <input name="postal_code" id="postal_code" type="text" class="form-control" placeholder="Zip Code" v-model="form.postal_code" required />
                    </div> -->

                    <!-- <div class="form-group">
                      <breeze-label for="address1" value="Address 1" />
                      <input name="address1" id="address1" type="text" class="form-control" placeholder="Address1" v-model="form.address1" required />
                    </div>

                    <div class="form-group">
                      <breeze-label for="address2" value="Address2" />
                      <input name="address2" id="address2" type="text" class="form-control" placeholder="Address2" v-model="form.address2" />
                    </div> -->

                  </div>    
                </div>
            
              
              <div class="order-button">
                <input type="submit" value="Update Profile" class="btn btn-danger" />
              </div>
            </div>
           
          </form>
        </div><!-- subscription -->
      </div><!-- container -->
    </section>
         
    </div>
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'
    
    export default {
        components: {
            BreezeAuthenticatedLayout,
            MainLayout,
            BreezeLabel,
            BreezeValidationErrors
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: this.profile.name,          
                    email: this.profile.email,        
                    country: this.profile.country,
                    state: this.profile.state,
                    city: this.profile.city,
                    postal_code: this.profile.postal_code,
                    phone_no: this.profile.phone_no,
                    address1: this.profile.address1,
                    address2: this.profile.address2,
                })
            };
        },
        props: {
            auth: Object,
            profile:Object,
            countries:Object,
        },
        methods : {
          	submit() {
              this.form.post(this.route('update-profile'))
            }
        }
    }
</script>
