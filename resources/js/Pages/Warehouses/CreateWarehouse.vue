<template>
    <MainLayout>
    <div>
        <section>
      <div class="container">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Create Warehouse
        </h2>

        <div class="stock-subscription-form" style="margin-top:20px;">

          <form @submit.prevent="submit">          
            <div class="order-form">                
                <breeze-validation-errors class="mb-4" />
                <flash-messages class="mb-4" />    
                
                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group">
                      <breeze-label for="name" value="Name" />
                      <input name="name" id="name" type="text" class="form-control" placeholder="Name" v-model="form.name" required />
                    </div>

                    <div class="form-group">
                      <breeze-label for="country_id" value="Country" />
                      <select name="country_id" class="form-select" v-model="form.country_id" required>
                          <option selected>Select</option>
                          <template v-for="country in countries" :key="country.id">
                            <option :value="country.id" >{{ country.name}}</option>
                          </template>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <breeze-label for="city" value="City" />
                      <input name="city" id="city" type="text" class="form-control" placeholder="City" v-model="form.city" required />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <breeze-label for="state" value="State" />
                      <input name="state" id="state" type="text" class="form-control" placeholder="State" v-model="form.state" required />
                    </div>

                    <div class="form-group">
                      <breeze-label for="state" value="Zip" />
                      <input name="zip" id="zip" type="text" class="form-control" placeholder="Zip" v-model="form.zip" required />
                    </div>

                     <div class="form-group">
                      <breeze-label for="phone" value="Phone" />
                      <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone" v-model="form.phone" required />
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <breeze-label for="address" value="Address" />
                      <input name="address" id="address" type="text" class="form-control" placeholder="Address" v-model="form.address" required />
                    </div>

                  </div>                
                </div>
                          
              <div class="order-button">
                <input type="submit" value="Create Warehouse" class="btn btn-danger" />
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

    export default {
        components: {
            BreezeAuthenticatedLayout,
            MainLayout,
            BreezeLabel
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: '',          
                    country_id: '',
                    state: '',
                    city: '',
                    zip:'',
                    phone: '',
                    address: '',
                })
            };
        },
        props: {
            auth: Object,
            countries : Object,
        },
        methods : {
          	submit() {
              this.form.post(this.route('warehouses.store'))
            }
        }
    }
</script>
