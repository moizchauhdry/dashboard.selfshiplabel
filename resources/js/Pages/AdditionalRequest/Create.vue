<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Request
      </div>
      <div class="card-body">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-12 col-md-4">
              <label for="package_id">Package</label>
              <select class="form-control" name="package_id" v-model="form.package_id" id="package_id" required>
                <option value="">Select Package</option>
                <option v-for="packge in packages" :value="packge.id">{{ packge.package_no }}</option>
              </select>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="tracking_no">Tracking No.</label>
              <input type="text" class="form-control" name="tracking_no" id="tracking_no" v-model="form.tracking_no" required/>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="serial_no">Serial No.</label>
              <input type="text" class="form-control" name="serial_no" id="serial_no" v-model="form.serial_no" required/>
            </div>
            <div class="form-group col-12">
              <label for="message">Request</label>
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
        message :"",
        tracking_no :"",
        serial_no :"",
      })
    }
  },
  props: {
    additionalRequest: Object,
    packages: Object
  },
  methods:{
    submit(){
      this.form.post(this.route('additional-request.create'))
    },
  }

}
</script>

<style scoped>

</style>