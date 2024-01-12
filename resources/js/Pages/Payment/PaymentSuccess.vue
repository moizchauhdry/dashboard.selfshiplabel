<template>
  <MainLayout>
    <div class="container">
      <div class="card" style="margin-top: 0px">
        <div class="card-header">Payment</div>
        <div class="card-body text-center">
          <h2 class="mb-4" style="font-size: 25px;color: green">Payment Success</h2>
          <h2 class="mb-4" style="font-size: 25px;color: green">Transaction Id : {{payment.transaction_id}}</h2>


          <inertia-link class="btn btn-primary" :href="route('dashboard')" >
            Dashboard
          </inertia-link>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeLabel from '@/Components/Label'
import BreezeValidationErrors from '@/Components/ValidationErrors'
import BreezeNavLink from '@/Components/NavLink'
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink'
export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
    BreezeLabel,
    BreezeValidationErrors,
    BreezeNavLink,
    BreezeResponsiveNavLink,
  },
  props:{
    payment:Object,
  },
  methods : {

  },
}
</script>

<style scoped>

</style>