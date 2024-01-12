<template>
  <MainLayout>
    <div class="card mt-4">
      <div class="card-body">
        <div class="row">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title mb-4">Manage Orders</h2>
        </div>
        <div class="row">
            <div class="col-md-9" >
              <form @submit.prevent="submit">          
                <div class="row">
                    <div class="form-group col-md-4">
                      <input type="number" name="search" v-model="form.suite_no"  class="form-control" placeholder="Search By Suite #" >
                    </div>
                    <div class="form-group col-md-4">
                      <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                  </div>
              </form>
            </div>
          <div class="col-md-3" v-if="$page.props.auth.user.type == 'admin' || $page.props.auth.user.type == 'manager'" >
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              <inertia-link :href="route('orders.create')" class="btn btn-success float-right"><i class="fa fa-plus-alt mr-1"></i>Add Order</inertia-link>
            </h2>
          </div>
        </div>

        <div class="row my-4">
            <div class="col-md-3">
                <button type="button"  :class="{'active':active === 'arrived'}"  class="btn btn-light w-100"  @click="searchOrder('arrived')">Arrived</button>
            </div>
            <div class="col-md-3">
                <button type="button"  :class="{'active':active === 'labeled'}"  class="btn btn-light w-100"  @click="searchOrder('labeled')">Labeled</button>
            </div>
            <div class="col-md-3">
                <button type="button"  :class="{'active':active === 'shipped'}"  class="btn btn-light w-100"  @click="searchOrder('shipped')">Shipped</button>
            </div>
            <div class="col-md-3">
                <button type="button"  :class="{'active':active === 'rejected'}"  class="btn btn-light w-100"  @click="searchOrder('rejected')">Rejected</button>
            </div>
        </div>
        <order-list v-bind="$props"></order-list>

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
import OrderList from './Partials/OrderList.vue'
import { Inertia } from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
    OrderList,
  },
  props: {
    auth: Object,
    orders:Object,
    filter:Object,
  },
  data() {
    return {
      active : 'arrived',
      form: useForm({
        suite_no: "",
      }),
      order_form: {
        order_status: this.filter.order_status,
        suite_no: '',
        processing: false,
      }
    }
  },
  methods:{
      searchOrder(status){
          this.active = status;
          this.order_form.order_status = status;
          this.order_form.suite_no = this.form.suite_no;
          Inertia.post(route("orders"),this.order_form);
      },
      siuteNum(user_id){
          return 4000 + user_id;
      },
      submit() {
        this.searchOrder(this.order_form.order_status);
      },
  },
  watch:{
    params:{
      handler(){
        this.$inertia.get(this.route('orders'),this.params,{replace:true,preserveState:true});
      }
    }
  }
}
</script>

<style>
    button.active.btn.btn-light.w-100 {
        background-color: red !important;
        color: white;
    }
</style>