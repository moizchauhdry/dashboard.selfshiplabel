<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Request Edit
      </div>
      <div class="card-body">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="row">
            <div class="form-group col-12 col-md-4">
              <label for="package_id">Package</label>
              <select class="form-control" name="package_id" v-model="form.package_id" id="package_id" required readonly disabled>
                <option value="">Select Package</option>
                <option v-for="packge in packages" :value="packge.id">{{ packge.package_no }}</option>
              </select>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="tracking_no">Tracking No.</label>
              <input type="text" class="form-control" name="tracking_no" id="tracking_no" v-model="form.tracking_no" required readonly/>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="serial_no">Serial No.</label>
              <input type="text" class="form-control" name="serial_no" id="serial_no" v-model="form.serial_no" required readonly/>
            </div>
            <div class="form-group col-12">
              <label for="message">Request</label>
              <textarea class="form-control" name="message" id="message" v-model="form.message" cols="10" rows="5" required></textarea>
            </div>
            <div class="form-group col-12 col-md-4">
              <label for="price">Price</label>
              <input type="number" step="0.01" class="form-control" name="price" id="price" v-model="form.price" required :readonly="$page.props.auth.user.type != 'admin'"/>
            </div>
            <div class="form-group col-12">
              <button type="submit" name="submit" class="btn btn-primary">Send</button>
              <button v-show="$page.props.auth.user.type != 'admin' && form.price != NULL && additionalRequest.payment_status != 'Paid'" type="button" name="approve" class="btn btn-primary ml-2" @click="approve()">Approve & Checkout</button>
            </div>
          </div>
        </form>
        <div>
          <div class="row">
            <div class="form-group col-12">
              <label for="cmessage">Add Comment</label>
              <textarea class="form-control" v-model="commentForm.message" name="message" id="cmessage" cols="5" rows="2"></textarea>
            </div>
            <div class="col-12">
              <label class="btn btn-primary float-right" @click="saveComment()">Add Comment</label>
            </div>
          </div>
        </div>
        <fieldset v-show="comments.length > 0" class="border p-4 mb-4 col-12 mt-2">
          <legend class="w-auto">Comment(s)</legend>
          <div v-for="comment in comments" :key="comment.id" class="card" style="margin:10px 0px;">
            <div class="card-body" style="padding: 5px 10px">
              {{comment.message}}
            </div>
            <div class="card-footer" style="padding: 5px 10px"><p class="float-right">{{comment.user.name}}</p></div>
          </div>
        </fieldset>
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
  data(){
    return{
      form: this.$inertia.form({
        package_id:this.additionalRequest.package_id,
        message :this.additionalRequest.message,
        tracking_no :this.additionalRequest.tracking_no,
        serial_no :this.additionalRequest.serial_no,
        price :this.additionalRequest.price,
        approve : 0,
      }),
      commentForm: this.$inertia.form({
        message:"",
      }),
      comments : this.comments,

    }
  },
  props: {
    additionalRequest: Object,
    packages: Object,
    comments: Object
  },
  methods:{
    submit(){
      this.form.post(this.route('additional-request.edit',this.additionalRequest.id))
    },
    approve(){
      this.form.approve = 1;
      this.form.post(this.route('additional-request.edit',this.additionalRequest.id))
    },
    loadComments(){
    },
    saveComment(){
      if(this.commentForm.message == ''){
        return false;
      }
      this.commentForm.post(this.route('additional-request.storeComment',this.additionalRequest.id));
      this.commentForm.reset();
    }
  },
  created() {
  }

}
</script>

<style scoped>

</style>