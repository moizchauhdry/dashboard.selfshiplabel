<template>
  <MainLayout>
    <div class="card mt-4">
      <div class="card-body">

        <div class="row">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
            Manage Store List
          </h2>
        </div>
        <div class="row" style="margin-top:30px;">
          <div class="col-md-6" >
            <form action="/store" method="get">
              <input type="text" name="search" class="form-control" placeholder="Search">
            </form>
          </div>
          <div class="col-md-6" >
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              <template v-if="$page.props.auth.user.type == 'admin'">
                <inertia-link :href="route('store.create')" class="btn btn-success float-right">Add New Store</inertia-link>
              </template>
            </h2>
          </div>
        </div>

        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Store Name</th>
              <th scope="col">Warehouse Name</th>
              <th scope="col">Pickup charges</th>
              <th scope="col">City</th>
              <th scope="col">State</th>
              <th scope="col">Zip</th>
              <th scope="col">Address</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="store in stores.data" :key="store.id">
              <td>{{ store.id }}</td>
              <td>{{ store.name }}</td>
              <td>{{ store.warehouse.name }}</td>
              <td>{{ store.pickup_charges }}</td>
              <td>{{ store.city }}</td>
              <td>{{ store.state }}</td>
              <td>{{ store.zip }}</td>
              <td>{{ store.address }}</td>
              <td>
                <inertia-link class="link-primary" :href="route('store.edit', store.id)" title="Edit">
                  <span><i class="fa fa-pencil-alt"></i></span>
                </inertia-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </MainLayout>
</template>
<style scoped>
.label{
  padding:5px;
}
</style>
<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
  },
  props: {
    auth: Object,
    stores:Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        search: ''
      }),
      params:{
        search:null,
      },
    }
  },
  watch:{
    params:{
      handler(){
        this.$inertia.get(this.route('shop-for-me'),this.params,{replace:true,preserveState:true});
      }
    }
  }
}
</script>
