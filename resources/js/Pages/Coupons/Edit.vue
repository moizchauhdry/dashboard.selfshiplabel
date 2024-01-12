<template>
  <MainLayout>
    <div class="container">
      <div class="card" style="margin-top: 0px">
        <div class="card-header">
          Edit Coupon
        </div>
        <div class="card-body stock-subscription-form">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="order-form">
              <breeze-validation-errors class="mb-4"/>
              <flash-messages class="mb-4"/>
              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-6 form-group">
                    <breeze-label for="name" value="Name"/>
                    <input v-model="form.name" name="name" id="name" type="text" class="form-control" placeholder="Enter Name" required/>
                  </div>
                  <div class="col-sm-12 col-md-4 form-group">
                    <breeze-label for="Code" value="Code"/>
                    <input v-model="form.code" name="code" id="code" type="text" class="form-control" placeholder="Enter Code" required/>
                  </div>
                  <div class="col-sm-12 col-md-6 form-group">
                    <breeze-label for="discount" value="Discount %"/>
                    <input v-model="form.discount" name="discount" id="discount" type="number" class="form-control" placeholder="Enter Discount %" max="100" min="0"  required/>
                  </div>
                  <!--<div class="col-sm-12 col-md-12 form-group">
                    <breeze-label for="description" value="Description"/>
                    <textarea v-model="form.description" name="description" id="description" class="form-control" placeholder="Enter Description"  rows="5"></textarea>
                  </div>-->
                  <div class="col-12">
                    <button class="btn btn-primary float-right" type="submit" v-on:submit="submit">Submit</button>
                  </div>

                </div>
              </div>
            </div>
          </form>
        </div>
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
        name:"",
        discount :"",
        description :"",
      })
    }
  },
  props: {
    coupon:Object
  },
  methods : {
    submit(){
      this.form.post(this.route('coupon.update'))
    }
  }


}
</script>

<style scoped>

</style>