<template>
    <MainLayout>
      <div class="card mt-4">
      <div class="card-body stock-subscription-form">
  
          <form @submit.prevent="submit">
            
                  <h5>Update Your Password</h5>

                  <div class="row">
                  
                    <div class="col-md-6 offset-md-2">
                        <breeze-validation-errors class="mb-4" />
                        <flash-messages class="mb-4" />
                        <div class="form-group">
                          <input name="old_password" id="old_password" type="password" class="form-control" placeholder="Old Password" v-model="form.old_password" required />
                        </div>
                    </div>

                    <div class="col-md-6 offset-md-2">
                      <div class="form-group">
                        <input name="new_password" id="new_password" type="password" class="form-control" placeholder="New Password" v-model="form.new_password" required />
                      </div>
                    </div>

                    <div class="col-md-6 offset-md-2">
                      <div class="form-group">
                        <input name="confirm_new_password" id="confirm_new_password" type="password" class="form-control" placeholder="Confirm Password" v-model="form.confirm_new_password" required />
                      </div>
                    </div>

                  </div>
              
                <div class="order-button text-center">
                  <input type="submit" value="Update Your Password" class="btn btn-danger" />
                </div>            
            </form>


      </div><!-- subscription -->
      </div><!-- container -->
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
            BreezeAuthenticatedLayout,
            MainLayout,
            BreezeValidationErrors,
        },
        data() {
            return {
                form: this.$inertia.form({
                    old_password: '',
                    new_password: '',
                    confirm_new_password: '',
                }),
                currentYear: new Date().getFullYear(),
                Years : [],
            }
        },
        props: {
            auth: Object,
            errors: Object,
        },
        methods: {
            submit() {
                this.form.post(this.route('update-password'), {
                     onFinish: () => {
                       //this.form.reset();
                       //this.response = 'Password Updated Successfully';                       
                       },
                 })
            },
        }
    }
</script>
