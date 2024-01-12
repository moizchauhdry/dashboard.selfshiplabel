<template>
  <MainLayout>
    <div>
      <section>
        <div class=container>
          <div class=card>
            <div class=card-header><strong>Product Detail -{{ auction.name }}</strong></div>
            <div class=card-body>
              <table class="table table-borderd">
                <tr>
                  <th>Name</th>
                  <td>{{ auction.name }}</td>
                </tr>
                <tr>
                  <th>Category</th>
                  <td>{{ auction.category_name }}</td>
                </tr>
                <tr>
                  <th>Weight</th>
                  <td>{{ auction.weight + ' ' + auction.weight_unit }}</td>
                </tr>
                <tr>
                  <th>Starting Price</th>
                  <td>${{ auction.starting_price }}</td>
                </tr>
                <tr>
                  <th>Buying Price</th>
                  <td>${{ auction.buy_price }}</td>
                </tr>
                <tr>
                  <th>Ending At</th>
                  <td>{{ formatDate(auction.ending_at) }}</td>
                </tr>
                <tr>
                  <th>Expired</th>
                  <td>{{ auction.expired_at != null ? 'YES' : 'NO' }}</td>
                </tr>
                <tr>
                  <th>Bought</th>
                  <td>{{ auction.bought_at != null ? 'YES' : 'NO' }}</td>
                </tr>
                <tr>
                  <th>Feature Image</th>
                  <td><img style=width:100px;height:auto class=img-thumbnail :src="'/' + auction.thumbnail"
                      @click=viewImage($event)></td>
                </tr>
                <tr>
                  <th>Images</th>
                  <td>
                    <div class=row>
                      <div v-for="(image, index) in auction_images" :key=image.id class=col-md-2>
                        <div>
                          <img style="width:100px;height:auto" class=img-thumbnail :src=imgURL(image.image)
                            @click=viewImage($event)>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </table>

              <template v-if="auction.bids.length > 0">
                <h3>Bids</h3>
                <table class="table table-borderd">
                  <tr>
                    <th>Sr #</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>At</th>
                    <th v-if="$page.props.auth.user.type != 'customer'">Action </th>
                  </tr>
                  <tr v-for="(bid, index) in auction.bids" v-bind:key="bid.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ bid.customer_name }}</td>
                    <td>{{ bid.amount }}</td>
                    <td>{{ formatDateTime(bid.created_at) }}</td>
                    <td v-if="$page.props.auth.user.type != 'customer'">
                      <a v-show="!auction.is_bidder_selected" href="javascript:void(0)" v-on:click="selectBid(bid)"
                        class="btn btn-primary btn-sm">
                        <i class="fa fa-plus mr-1"></i>Select Bid</a>

                      <span class="badge rounded bg-green-700 text-white" v-if="bid.is_selected">SELECTED</span>
                    </td>
                    <td v-if="bid.is_selected && auction.payment_status == 'Pending'">
                      <button class="btn btn-primary" @click="checkout()">Checkout</button>
                    </td>
                  </tr>
                </table>
              </template>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="modal fade show" id="bidModal" tabindex="-1" role="dialog" aria-labelledby="bidModalTitle"
      :style="bidModal ? 'display: block' : 'display: none'">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bidModalLongTitle">BID - {{ auction.name }}</h5>
            <button type="button" class="btn btn-secondary close" v-on:click="closeModal"
              data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <h3>Are you sure ?</h3>
            <p>You want to select this bid </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" v-on:click="closeModal" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" v-on:click="confirmBid">Yes</button>
          </div>
        </div>
      </div>
    </div>

  </MainLayout>
</template>
<script>
import MainLayout from "@/Layouts/Main";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    BreezeAuthenticatedLayout: BreezeAuthenticatedLayout,
    MainLayout: MainLayout,
    BreezeLabel: BreezeLabel,
    BreezeValidationErrors: BreezeValidationErrors
  },
  data() {
    return {
      bidModal: false,
      'form': {
        'bid_id': null
      },
      checkout_form: this.$inertia.form({
        auction_id: this.auction.id,
        payment_module: "auction",
      }),
    }
  },
  props: {
    auth: Object,
    auction: Object,
    auction_images: Object
  },
  methods: {
    imgURL: e => "/" + e, viewImage(e) {
      console.log(e.target.src);
      var t = document.getElementById("imageViewer");
      document.querySelector("#imageViewer img").src = e.target.src, t.classList.add("show"), $("#imageViewer").show()
    },
    formatDate: e => new Date(e).toLocaleString("en-GB", { dateStyle: "short" }),
    formatDateTime: e => new Date(e).toLocaleString("en-GB"),
    selectBid(bid) {
      this.form.bid_id = bid.id;
      this.bidModal = true;
    },
    closeModal() {
      this.bidModal = false
      this.form.bid_id = null;
    },
    confirmBid() {
      var auctionID = this.auction.id
      axios.post(this.route('auctions.select-bid'), this.form).then(function (response) {
        if (response.data.status == 1) {
          alert(response.data.message);
          const url = `${route("auctions.show", auctionID)}`;
          Inertia.visit(url, {});
        } else {
          alert(response.data.message);
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    checkout() {
      this.$inertia.post(route("payment.index"), this.checkout_form);
    },
  }
}
</script>
<style scoped></style>