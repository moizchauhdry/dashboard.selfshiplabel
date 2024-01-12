<template>
  <MainLayout>
    <div class="container">
      <div class="card mt-0">
        <div class="card-header">
          <h1 class="text-center font-bold text-lg">Customer Account  #{{suiteNumber(customer.id)}}</h1>
        </div>
        <div class="card-body">
          <table class="table table-responsive table-bordered table-striped">
            <thead>
            <tr>
              <th colspan="4" class="bg-primary text-white text-center">Personal Information</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th>Suite #</th>
              <td>{{ suiteNumber(customer.id) }}</td>

              <th>Name</th>
              <td>{{ customer.name }}</td>
            </tr>
            <tr>
              <th>Email</th>
              <td>{{ customer.email }}</td>

              <th>Phone</th>
              <td>{{ customer.phone_no }}</td>
            </tr>
            </tbody>
          </table>

          <table class="table table-responsive table-bordered table-hover text-capitalize text-center">
            <thead>
            <tr>
              <th colspan="7" class="bg-primary text-white text-center">Order Information</th>
            </tr>
            <tr>
              <th scope="col">Order ID</th>
              <th scope="col">Order From</th>
              <th scope="col">Warehouse</th>
              <th scope="col">Order Type</th>
              <th scope="col">Order Status</th>
              <th scope="col">Order Date</th>
              <th scope="col"></th>
            </tr>
            </thead>
            <template v-if="customer.orders.length > 0">
              <tbody>
              <tr v-for="order in customer.orders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ order.received_from }}</td>
                <td>{{ order?.warehouse?.name }}</td>
                <td>{{ order.order_type }}</td>
                <td>{{ order.status }}</td>
                <td>{{ order.created_at }}</td>
                <td>
                  <inertia-link class="btn btn-info btn-sm" :href="route('orders.show', order.id)">
                    <i class="fa fa-list mr-1"></i>Detail
                  </inertia-link>
                </td>
              </tr>

              </tbody>
            </template>
            <template v-else>
              <tbody>
              <tr>
                <td class="text-primary text-center" colspan="7">There are no orders added yet.</td>
              </tr>
              </tbody>
            </template>
          </table>

        </div>
        <div class="card-footer">

        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout
  },

  props: {
    auth: Object,
    customer: Object,
  },
  methods: {
    suiteNumber(user_id){
      return 4000 + user_id;
    },
  }
}
</script>

<style scoped>

</style>