<template>
  <MainLayout>
    <div class="card mt-4">
      <div class="card-body">
        <template v-if="$page.props.auth.user.type == 'admin' && order.package.status == 'open'">
          <div class="row">
            <div class="col-md-12">
              <inertia-link :href="route('order.edit', order.id)" class="btn btn-primary float-right mb-2"><i class="fa fa-edit mr-1"></i>Edit Package</inertia-link>
            </div>
          </div>
        </template>

          <h2 class="font-semibold text-xl text-gray-800 leading-tight">Package Detail</h2>
          <fieldset class="border p-3 mt-2 mb-2">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th>Customer</th>
                    <td>#{{ order.customer.id }} - {{ order.customer.name }}</td>
                  </tr>
                  <tr>
                    <th>Tracking In</th>
                    <td>{{ order.tracking_number_in }}</td>
                  </tr>
                  <tr>
                    <th>Received At</th>
                    <td>{{ order.warehouse.name }}</td>
                  </tr>
                  <tr>
                    <th>Weight</th>
                    <td>{{ parseFloat(order.package_weight).toFixed(2) }} {{ order.weight_unit }}</td>
                  </tr>
                  <tr>
                    <th>Length</th>
                    <td>{{ order.package_length }} {{ order.dim_unit }}</td>
                  </tr>
                  <tr>
                    <th>Width</th>
                    <td>{{ order.package_width }} {{ order.dim_unit }}</td>
                  </tr>
                  <tr>
                    <th>Height</th>
                    <td>{{ order.package_height }} {{ order.dim_unit }}</td>
                  </tr>
                  <tr>
                    <th>Images</th>
                    <td>
                      <div v-for="image in order.images" :key="image.id">
                        <div class="m-1 p-1">
                          <img style="width:100px;height:auto;" class="img-thumbnail" @click="viewImage($event)" :src="imgURL(image.image)"/>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
  </MainLayout>
  <ImageViewer></ImageViewer>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import ImageViewer from '@/Components/ImageViewer'
import $ from 'jquery'

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
    ImageViewer
  },

  props: {
    auth: Object,
    order: Object,
    order_details: Object
  },
  methods: {
    imgURL(url) {
      return "/public/uploads/" + url;
    },
    viewImage(event) {
      console.log(event.target.src);
      var modal = document.getElementById('imageViewer');
      var imageSRC = document.querySelector('#imageViewer img')
      imageSRC.src = event.target.src;
      modal.classList.add('show');
      $('#imageViewer').show();
    },
  }
}
</script>
