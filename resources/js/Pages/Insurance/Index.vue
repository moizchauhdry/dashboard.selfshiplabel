<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Insurance
        <div class="float-right">
          <inertia-link :href="route('insurance.create')"
                        v-if="$page.props.auth.user.type != 'admin'"
                        class="btn btn-primary float-right">Add Request</inertia-link>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive">
            <thead>
            <tr>
              <th>ID</th>
              <th>Package ID</th>
              <th>Customer</th>
              <th>Insurance Amount</th>
              <th>Shipping Service</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="insurance in insurances" :key="insurance.id">
              <td>{{ insurance.id }}</td>
              <td>
                <inertia-link :href="route('customers.show', insurance.customer.id)" class="btn btn-link">
                  # {{ siuteNum(insurance.customer.id) }} - {{ insurance.customer.name }}
                </inertia-link>
              </td>
              <td>{{ insurance.package.package_no }}</td>
              <td>{{ insurance.insurance_amount }}</td>
              <td>{{ insurance.shipping_service }}</td>
              <td>
                <inertia-link v-show="insurance.payment_status != 'Paid'" :href="route('insurance.edit',insurance.id)"
                              class="btn btn-primary float-right">Edit</inertia-link>
                <!--              <a class="btn btn-danger" v-if="insurance.status == 1" v-on:click="changeStatus(insurance.id,0,$event)">Deactivate</a>
                              <a class="btn btn-success" v-else v-on:click="changeStatus(insurance.id,1,$event)">Activate</a>-->
              </td>
            </tr>
            </tbody>
          </table>
        </div>
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
    insurances: Object
  },
  methods:{
    changeStatus(id,status,event){
      axios.post(this.route('insurance.changeStatus'), {
        id:id,
        status:status,
      }).then(function (response) {
        console.log(response.data.insurance.status);
        let status = response.data.insurance.status;
        if(status === 1){
          event.target.classList.remove('btn-success');
          event.target.classList.add('btn-danger');
        }else{
          event.target.classList.add('btn-success');
          event.target.classList.remove('btn-danger');
        }

      }).catch(function (error) {
        console.log(error);
      });
    },
    siuteNum(user_id){
      return 4000 + user_id;
    },
  }

}
</script>

<style scoped>

</style>