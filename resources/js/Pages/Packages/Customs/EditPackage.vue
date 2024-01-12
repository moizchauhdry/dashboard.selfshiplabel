<template>
    <MainLayout>
    <div>
    
    <section>
      <div class="container">

        <div class="stock-subscription-form">
          <h2> Update Package</h2>
          <form @submit.prevent="submit" enctype="multipart/form-data">   
            <input type="hidden" name="order_id" v-model="order_id" >       
            <div class="order-form">                
                <breeze-validation-errors class="mb-4" />
                <flash-messages class="mb-4" />    
                

                <div class="row">
                    
                  <div class="col-md-6" style="border : 1px solid #6b7280;">
                    <h2>Ship From : </h2>
                    <p><strong>Street Address:</strong>3578 W SAVANNA ST </p>
                    <p><strong>City:</strong>ANAHEIM </p>
                    <p><strong>State:</strong>CA </p>
                    <p><strong>Zip code:</strong>92804 </p>        
                    <p><strong>Phone Number:</strong>657-201-7881 </p>                                                         
                
                  </div>

                  <div class="col-md-6" style="border : 1px solid #6b7280;">
                       <p><strong>Tracking No:</strong> {{ order.tracking_number_in }} </p>
                       <p><strong>Date :</strong> {{ order.create_at }} </p>
                       <p><strong>Package-Id :</strong>Pkg- {{ order.order_id }} </p>
                  </div>

                </div>

                <div class="row">
                    
                  <div class="col-md-6" style="border : 1px solid #6b7280;">
                    
                    <h2>Ship To : </h2>

                    <div class="form-group">
                      <select v-on:change="selectAddress" name="address_book_id" class="form-select" v-model="address_book_id" required>
                          <template v-for="(address,index) in address_book" :key="address.id">
                           <option  :value="address.id" >{{ address.label}}</option>
                          </template>
                      </select>
                    </div>

                    <p><strong>Street Address:</strong>3578 W SAVANNA ST </p>
                    <p><strong>City:</strong>ANAHEIM </p>
                    <p><strong>State:</strong>CA </p>
                    <p><strong>Zip code:</strong>92804 </p>        
                    <p><strong>Phone Number:</strong>657-201-7881 </p>                                                         
                  </div>

                  <div class="col-md-6" style="border : 1px solid #6b7280;">
                       <p><strong>Sold To :</strong> Same as Shipped </p>
                  </div>
                </div>



                <div class="row">

                  <h1>Items</h1>

                  <div class="col-md-12">

                                          
                    <div class="row" style="margin-bottom:20px;">
                      <div class="col-md-4">
                            <breeze-label value="Description" />
                      </div>
                      <div class="col-md-1">
                            <breeze-label value="Qty" />
                      </div>
                      <div class="col-md-2">
                            <breeze-label value="Value" />
                      </div>
                      <div class="col-md-2">
                            <breeze-label value="Origin" />
                      </div>
                      <div class="col-md-2">
                            <breeze-label value="Batteries" />
                      </div>
                      <div class="col-md-1">
                      </div>
                    </div>

                    <div v-for="(item,index) in form.items" :key="item.id" class="row">
                      
                      <div class="col-md-4">
                        <div class="form-group">
                          <input v-model="item.description" name="description" id="description" type="text" class="form-control" placeholder="Description" required />
                        </div>
                      </div>

                      <div class="col-md-1">
                        <div class="form-group">
                          <input v-model="item.quantity" name="quantity" id="quantity" type="number" class="form-control" placeholder="Qty" required />
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <input v-model="item.value" name="value" id="value" type="number" step="0.01" class="form-control" placeholder="Value" required />
                        </div>
                      </div>

                      <div class="col-md-2">
                          <div class="form-group">
                            <select name="origin_country" class="form-select" v-model="item.origin_country" required>
                                <option selected value="">Select</option>
                                <template v-for="country in countries" :key="country.id">
                                  <option :value="country.id" >{{ country.name}}</option>
                                </template>
                            </select>
                          </div>
                      </div>

                      <div class="col-md-2">
                          <div class="form-group">
                            <select name="batteries" class="form-select" v-model="item.batteries" required>
                                <option selected value="">Select</option>
                                <option value="0">No Battery</option>
                                <option value="1">Simple Batteries (Shipped on on Fedex)</option>
                                <option value="2">Batteries Packaed with Equipment</option>
                                <option value="3">Batteries Contained in Equipment</option>
                            </select>
                          </div>
                      </div>

                      <div class="col-md-1" v-show="index!=0">
                        <div class="form-group">
                          <a v-on:click="removeItem(index)" class="btn btn-primary">
                            <span>Delete</span>
                          </a>
                        </div>
                      </div>

                    </div>

                    <div class="row">

                     <div class="col-md-1 offset-md-4" style="padding-right: 0px;">                        
                        <div class="form-group">
                            Total Value: 
                        </div>
                      </div>

                      <div class="col-md-2" style="padding-right: 0px;">                        
                        <div class="form-group">
                          <input v-model="total_value" readonly name="total_value" id="total_value" type="number" class="form-control" placeholder="Value" required />
                        </div>
                      </div>

                      <div class="col-md-2 offset-md-3" style="padding-right: 0px;">
                        <a v-on:click="addItem" class="btn btn-primary" style="float:right;">
													<span>Add Item </span>
												</a>
                      </div>
                    </div>

                  </div> 

            </div>
                                            
              <div class="order-button">
                <input type="submit" value="Create Order" class="btn btn-danger" />
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
                    id:this.order.id,
                    order_id: this.order.id,                                           
                    address_book_id: this.address_book_id,
                    items:this.items,
                    total_value :0
                })
            };
        },
        props: {
            auth: Object,
            countries:Object,
            address_book_id:Number,
            items : Object,  
            address_book : Object,
            first_address : String,
            order : Object,  
        },
        methods : {
          	submit() {
              this.form.post(this.route('package.update'))
            },
            addItem(){
                this.form.items.push(
                        {
                          description: "",
                          quantity: "",
                          value:0,
                          origin_country: "",
                          batteries:""
                        }
              )
            },
            removeItem(index){
              this.form.items.splice(index, 1);
            },
            storePhoto() {
                if (this.$refs.photo) {
                    this.form.post_image = this.$refs.photo.files[0]
                }
                this.form.post(route('photo.store'), {
                    preserveScroll: true
                });
            },
            getTotalValue(){
              return this.items.reduce(function(a, c){return a + Number((c.quantity*c.value) || 0)}, 0)
            }
        },
        computed: {
          total_value() {  

            return this.form.items.reduce((acc, item) => {
               return acc + (parseInt(item.value)*parseInt(item.quantity));
            }, 0);    

          }
        }
    }
</script>
