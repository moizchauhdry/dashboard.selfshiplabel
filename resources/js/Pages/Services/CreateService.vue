<template>
    <MainLayout>
    <div>
    
        <section>
      <div class="container">

        <h1>Create Service </h1>

        <div class="stock-subscription-form">

          <form @submit.prevent="submit">          
            <div class="order-form">                
                <breeze-validation-errors class="mb-4" />
                <flash-messages class="mb-4" />    
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <breeze-label for="title" value="Title" />
                      <input name="title" id="title" type="text" class="form-control" placeholder="Title" v-model="form.title" required />
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <breeze-label for="price" value="Price($)" />
                      <input name="price" id="price" type="number" class="form-control" placeholder="Price" v-model="form.price" required />
                    </div>
                  </div>  
                  <div class="col-md-2">
                    <div class="form-group">
                      <breeze-label for="status" value="Active" />
                      <input name="status" id="status" type="checkbox" class="form-control" v-model="form.status" />
                    </div>
                  </div>               
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <breeze-label for="description" value="Description" />
                        <textarea v-model="form.description" 
                        name="description" 
                        id="description" 
                        class="form-control" 
                        placeholder="Description" 
                        rows="4"
                        style="resize:none;"
                        required >
                        </textarea>
                      </div>
                  </div>
                </div>
                          
              <div class="order-button">
                <input type="submit" value="Create Service" class="btn btn-danger" />
              </div>
            </div>
           
          </form>
        </div><!-- subscription -->
      </div><!-- container -->
    </section>
         
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
        data() {
            return {
                form: this.$inertia.form({
                    title: '',          
                    description: '',
                    price : '',
                    status : true
                })
            };
        },
        props: {
            auth: Object,
        },
        methods : {
          	submit() {
              this.form.post(this.route('services.store'))
            }
        }
    }
</script>
