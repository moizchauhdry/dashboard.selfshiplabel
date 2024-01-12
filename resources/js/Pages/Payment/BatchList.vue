<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">Payments</div>
      <div class="card-body">
        <table class="table table-responsive table-hover">
          <thead>
          <tr>
            <th>ID</th>
            <th>Payment Method</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="item in batchLists" :key="item.batchId">
              <td>{{ item.batchId }}</td>
              <td>{{ item.paymentMethod }}</td>
            </tr>
          </tbody>
        </table>
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
    batchLists: Object
  },
  methods:{
    changeStatus(id,status,event){
      axios.post(this.route('coupon.changeStatus'), {
        id:id,
        status:status,
      }).then(function (response) {
        console.log(response.data.coupon.status);
        let status = response.data.coupon.status;
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
    }
  }

}
</script>

<style scoped>

</style>