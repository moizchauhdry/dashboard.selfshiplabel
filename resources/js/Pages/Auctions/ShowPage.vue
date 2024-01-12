<template>
  <Header></Header>

  <div class="container-xl product-section">
    <div class="row">
      <div class="col-md-6">
        <img :src="'/'+auction.thumbnail" class="card-img-top img-aspect" alt="...">
        <div class="d-flex justify-center">
          <template v-for="(image, index) in auction_images" :key="image.id">
            <img :src="'/'+image.image"  class="img-aspect-sub" alt="...">
          </template>
        </div>
      </div>
      <div class="col-md-6">
        <h1>{{ auction.name }}</h1>

        <p class="text-123"><strong>Starting Price :</strong><span>&nbsp; $ {{ auction.starting_price }}</span></p>
        <p class="text-123" v-if="auction.latest_bid"><strong>Latest Bid :</strong><span>&nbsp; $ {{ auction.latest_bid.amount }}</span></p>
        <p class="time text-123"><strong>Time Left :</strong><span>&nbsp; {{ getRemainingTime(auction.ending_at) }}</span></p>

        <h3>Description</h3>
        <p class="description" v-html="auction.description"></p>

        <p class="weight"><strong>Weight : </strong><span>&nbsp; {{ `${auction.weight} ${auction.weight_unit}`  }}</span></p>
        <p class="weight"><strong>Dimensition : </strong><span>&nbsp; {{ `${auction.length}l X ${auction.width}w X ${auction.height}h  ${auction.dimension_unit}`  }}</span></p>
        <div class="hr mb-4">
          &nbsp;
        </div>

        <template v-if="!$page.props.auth.user">
          <inertia-link class="view-btn" :href="route('auctions.detail', auction.id)">Login To Bid</inertia-link>
        </template>
        <template v-else>
          <a v-if="$page.props.auth.user.type == 'customer'" href="javascript:void(0)" class="view-btn" v-on:click="bidModal = true">Bid</a>

        </template>

        <div class="modal fade show" id="bidModal" tabindex="-1" role="dialog" aria-labelledby="bidModalTitle"  :style="bidModal ? 'display: block' : 'display: none'">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="bidModalLongTitle">BID - {{ auction.name }}</h5>
                <button type="button" class="close" v-on:click="bidModal = false" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <label for="bid-amount">Bid Amount</label><br>
                <input id="bid-amount" class="w-full" v-model="amount" type="number" name="amount" step="0.01" :min="auction.starting_price">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" v-on:click="bidModal = false" data-dismiss="modal" >Close</button>
                <button type="button" class="btn btn-primary" v-on:click="updateBid">Confirm Bid</button>
              </div>
            </div>
          </div>
        </div>




      </div>
    </div>
  </div>
</template>
<script>

import Header from '@/Components/Header.vue'
import Pagination from "@/Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";

export default {
  props: {
    errors: Object,
    auction: Object,
    auction_images: Object,
  },
  components: {
    Header, Pagination
  },
  data() {
    return {
      bidModal:false,
      amount:(this.auction.latest_bid != null ? this.auction.latest_bid.amount : this.auction.starting_price) + 0.10
    }
  },
  computed: {
  },
  methods: {
    getRemainingTime(timestamp) {
      // Calculate the difference between the timestamp and the current time.

      let endDate = new Date(timestamp)

      const now = new Date();
      const delta = endDate - now;

      // Convert the difference to seconds.
      const seconds = delta / 1000;

      // Divide the number of seconds by the number of seconds in a day, hour, minute, and second to get the number of days, hours, minutes, and seconds remaining.
      const days = Math.floor(seconds / 86400);
      const hours = Math.floor((seconds % 86400) / 3600);
      const minutes = Math.floor(((seconds % 86400) % 3600) / 60);
      const secondsRemaining = ((seconds % 86400) % 3600) % 60;

      if(days > 0){
        return `${days}D ${hours}h ${minutes}m`;
      }else{
        return `${hours}h ${minutes}m ${parseInt(secondsRemaining)}s`;
      }


    },
    updateBid(){
      let auctionID = this.auction.id
      if(this.amount <= (this.auction.latest_bid != null ? this.auction.latest_bid.amount : this.auction.starting_price)){
        alert('Price should be greater than current bid.')
        return;
      }else{
        axios.post(this.route('auctions.bid'), {
          id:this.auction.id,
          amount:this.amount,
        }).then(function (response) {
          if(response.data.status == 1){
            alert(response.data.message);
            const url = `${route("auctions.detail",auctionID)}`;
            Inertia.visit(url, { });
          }
        }).catch(function (error) {
          console.log(error);
        });
      }
    }

  }
}
</script>

<style scoped>
.text-123 span {
  color: #ff0000;
}

.img-aspect{
  aspect-ratio: 3/4;
  object-fit: cover;
  width: 100%;
  padding: 5px;
  margin-bottom: 10px;
  border: solid 1px #d5d5d5;
  border-radius: 25px;
}

.img-aspect-sub{
  aspect-ratio: 3/4;
  object-fit: cover;
  max-width: 80px;
  margin-bottom: 10px;
  border: solid 1px #d5d5d5;
}

.d-flex.time.text-123 {
  font-size: 12px;
}

.hr{
  border-bottom: 2px solid #d5d5d5;
  margin-bottom: 15px;
}

.view-btn{
  text-align: center;
  width: 100%;
  max-width: 450px;
  padding: 15px;
  background: #fab906;
  color: #0a0a0a;
  margin-top: 25px;
  border-radius: 5px;
}

.view-btn:hover{
  background: #ffd350;
}

.description{
  min-height: 150px;
}

.product-section{
  margin-top: 50px;
  margin-bottom: 100px;
}

.product-section h1{
  font-size: 30px;
  font-weight: 600;
}

.product-section h3{
  margin-top: 15px;
  font-size: 16px;
  font-weight: 600;
}

</style>