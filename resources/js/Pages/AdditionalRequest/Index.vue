<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Additional Request
        <div class="float-right">
          <inertia-link :href="route('additional-request.create')"
                        v-if="$page.props.auth.user.type == 'customer'"
                        class="btn btn-primary float-right">Request</inertia-link>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>ID</th>
              <th>Package ID</th>
              <th>Customer</th>
              <th>Tracking No.</th>
              <th>Serial No</th>
              <th>Request Message</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="request in additionalRequests" :key="request.id">
              <td>{{ request.id }}</td>
              <td>{{ request.package.package_no }}</td>
              <td>
                <inertia-link :href="route('customers.show', request.customer.id)" class="btn btn-link">
                  # {{ siuteNum(request.customer.id) }} - {{ request.customer.name }}
                </inertia-link>
              </td>
              <td>{{ request.tracking_no }}</td>
              <td>{{ request.serial_no }}</td>
              <td>{{ request.message }}</td>
              <td>
                <inertia-link :href="route('additional-request.edit',request.id)"
                              class="btn btn-primary float-right">Edit</inertia-link>
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
    additionalRequests: Object
  },
  methods:{
    changeStatus(id,status,event){
      axios.post(this.route('additional-requests.changeStatus'), {
        id:id,
        status:status,
      }).then(function (response) {
        console.log(response.data.request.status);
        let status = response.data.request.status;
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