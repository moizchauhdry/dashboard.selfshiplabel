<template>
  <MainLayout>
    <div>
      <section>
        <div class="container">

          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Store
          </h2>

          <div class="stock-subscription-form" style="margin-top:20px;">

            <form @submit.prevent="submit">
              <div class="order-form">
                <breeze-validation-errors class="mb-4" />
                <flash-messages class="mb-4" />

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <breeze-label for="warehouse_id" value="Warehouse" />
                      <select name="warehouse_id" value="Warehouse" class="form-select" v-model="form.warehouse_id" required>
                        <option selected value="">Select</option>
                        <template v-for="warehouse in warehouses" :key="warehouse.id">
                          <option :value="warehouse.id" >{{ warehouse.name }}</option>
                        </template>
                      </select>
                    </div>
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
                      <breeze-label for="pickup_charges" value="Pickup Charges ($)" />
                      <input name="pickup_charges" id="pickup_charges" type="number" class="form-control" placeholder="Pickup Charges" v-model="form.pickup_charges" required />
                    </div>
                    <div class="form-group">
                      <breeze-label for="address" value="Address" />
                      <input name="address" id="address" type="text" class="form-control" placeholder="Address" v-model="form.address" required />
                    </div>

                  </div>

                  <div class="order-button">
                    <input type="submit" value="Update Store" class="btn btn-danger" />
                  </div>
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
        id: this.store.id,
        name: this.store.name,
        warehouse_id: this.store.warehouse_id,
        country_id: this.store.country_id,
        state: this.store.state,
        city: this.store.city,
        zip: this.store.zip,
        pickup_charges: this.store.pickup_charges,
        address: this.store.address,
      })
    };
  },
  props: {
    auth: Object,
    warehouses: Object,
    countries : Object,
    store: Object
  },
  methods : {
    submit() {
      this.form.post(this.route('store.update'))
    }
  }
}
</script>
