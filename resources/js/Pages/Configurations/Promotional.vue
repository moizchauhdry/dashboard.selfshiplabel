<template>
  <MainLayout>
    <div class="container">
      <div class="card" style="margin-top: 0px">
        <div class="card-header">
          Edit Promotional Message
        </div>
        <div class="card-body stock-subscription-form">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="order-form">
              <breeze-validation-errors class="mb-4"/>

              <div class="container">
                <div class="row">
                  <div class="col-sm-12 col-md-12 form-group">
                    <breeze-label for="description" value="Description"/>
                    <textarea v-model="form.description" name="description" id="description" class="form-control" placeholder="Enter Promotional Message"  rows="5" required ></textarea>
                  </div>
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
import BreezeValidationErrors from '@/Components/ValidationErrors'

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
    BreezeLabel,
    BreezeValidationErrors
  },
  data(){
    return{
      form: this.$inertia.form({
        description:this.promotional.description,

      })
    }
  },
  props: {
    promotional:Object
  },
  methods : {
    submit(){
      this.form.post(this.route('promotional.update'))
    }
  }


}
</script>

<style scoped>

</style>