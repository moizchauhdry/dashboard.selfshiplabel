<template>
  <MainLayout>
    <div class="container">
      <div class="card">
        <form @submit.prevent="submit">
          <div class="card-header">Customer Information</div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 form-group">
                <label>Customer Name</label>
                <input name="name" id="name" type="text" class="form-control" placeholder="Customer Name"
                  v-model="form.name" required />
              </div>
              <div class="col-md-6 form-group">
                <label>Email Address</label>
                <input type="email" readonly v-model="form.email" name="email_address" id="email_address"
                  class="form-control" placeholder="Email Address" required />
              </div>
              <div class="col-md-6 form-group">
                <label>Phone No</label>
                <input type="text" id="phone_no" name="phone_no" v-model="form.phone_no" class="form-control"
                  placeholder="Phone No" required />
              </div>
              <div class="col-md-6 form-group">
                <label>Account Status</label>
                <select v-model="form.status" class="form-control">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <input type="submit" value="Save & Update" class="btn btn-success" />
          </div>
        </form>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
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
    customer: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.customer.id,
        name: this.customer.name,
        email: this.customer.email,
        phone_no: this.customer.phone_no,
        status: this.customer.status,
        account_type: this.customer.account_type,
      }),
      currentYear: new Date().getFullYear(),
      Years: [],
    }
  },
  computed: {
    years() {
      const year = new Date().getFullYear()
      return Array.from({ length: year - 2000 }, (value, index) => year + index)
    }
  },
  methods: {
    submit() {
      this.form.post(this.route('customers.update', this.customer.id))
    },

  }
}
</script>
