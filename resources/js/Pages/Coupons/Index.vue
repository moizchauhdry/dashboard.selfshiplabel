<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Manage Coupons
        <div class="float-right">
          <inertia-link :href="route('coupon.create')" v-if="$page.props.auth.user.type == 'admin'"
            class="btn btn-primary float-right">Add Coupon</inertia-link>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-responsive table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Code</th>
              <th>Discount Amount</th>
              <!-- <th>Expired Date</th> -->
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="coupon in coupons" :key="coupon.id">
              <td>{{ coupon.id }}</td>
              <td>{{ coupon.name }}</td>
              <td>{{ coupon.code }}</td>
              <td>${{ coupon.discount }}</td>
              <!-- <td>{{ coupon.expires_at }}</td> -->
              <td>
                <input type="checkbox" @click="changeStatus(coupon)" :checked="coupon.status == 1" class="mr-1">
                <label for=""><b>{{ coupon.status == 1 ? 'Active' : "Inactive" }}</b></label>
              </td>
            </tr>
          </tbody>
        </table>
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
  props: {
    coupons: Object
  },
  data() {
    return {
      coupon_form: this.$inertia.form({
        id: "",
        status: "",
      }),
    };
  },
  methods: {
    changeStatus(coupon) {
      coupon.status = coupon.status === 1 ? 0 : 1;
      this.coupon_form.id = coupon.id;
      this.coupon_form.status = coupon.status;
      this.coupon_form.post(this.route("coupon.changeStatus"));
    }
  }

}
</script>

<style scoped></style>