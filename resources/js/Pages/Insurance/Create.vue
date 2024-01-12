<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Request
      </div>
      <div class="card-body">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label for="package_id">Package</label>
              <select class="form-control" name="package_id" v-model="form.package_id" id="package_id" required>
                <option value="">Select Package</option>
                <option v-for="packge in packages" :value="packge.id">{{ packge.package_no }}</option>
              </select>
            </div>
            <div class="form-group col-12 col-md-6">
              <label for="shipping_service">Shipping Services</label>
              <select class="form-control" name="shipping_service" v-model="form.shipping_service" id="shipping_service" required>
                <option value="">Select Service</option>
                <option v-for="service in shipping_services" :value="service">{{ service }}</option>
              </select>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="insurance_amount">Insurance Amount</label>
              <input type="number" step="0.01" class="form-control" name="insurance_amount" id="insurance_amount" v-model="form.insurance_amount" required :readonly="$page.props.auth.user.type == 'admin'"/>
            </div>
            <div class="form-group col-12">
              <label for="message">Message</label>
              <textarea class="form-control" name="message" id="message" v-model="form.message" cols="10" rows="5" required></textarea>
            </div>
            <div class="form-group col-12">
              <button type="submit" class="btn btn-primary">Send</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-footer">

      </div>
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
  data(){
    return{
      form: this.$inertia.form({
        package_id:"",
        shipping_service :"",
        insurance_amount :"",
        message :"",
      })
    }
  },
  props: {
    shipping_services: Object,
    packages: Object
  },
  methods:{
    submit(){
      this.form.post(this.route('insurance.create'))
    },
  }

}
</script>

<style scoped>

</style>