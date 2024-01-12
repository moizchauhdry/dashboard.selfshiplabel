<template>
  <Header></Header>

  <div class="container">
    <div class="main">
      <section class="py-5">
        <div class="container">
          <div class="row gx-5">
            <aside class="col-lg-6">
              <div class="border rounded-4 mb-3 d-flex justify-content-center">
                <a data-fslightbox="mygalley" ref="mainImage" class="rounded-4" target="_blank" data-type="image" href="javascript:void(0)">
                  <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit main-image" :src="'/'+default_image"/>
                </a>
              </div>
              <div class="d-flex justify-content-center mb-3">
                <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" v-on:click="default_image = auction.thumbnail" data-type="image" href="javascript:void(0)">
                  <img width="60" height="60" class="rounded-2" :src="'/'+auction.thumbnail"/>
                </a>
                <template v-for="(image, index) in auction_images" :key="image.id">
                  <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" v-on:click="default_image = image.image" data-type="image" href="javascript:void(0)">
                    <img width="60" height="60" class="rounded-2" :src="'/'+image.image"/>
                  </a>
                </template>


              </div>
              <!-- thumbs-wrap.// -->
              <!-- gallery-wrap .end// -->
            </aside>
            <main class="col-lg-6">
              <div class="ps-lg-3">
                <h4 class="title text-dark">
                  {{ auction.name }}
                </h4>
                <!--                <div class="d-flex flex-row my-3">
                                  <div class="text-warning mb-1 me-2">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="ms-1">
                                    4.5
                                  </span>
                                  </div>
                                  <span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1"></i>154 orders</span>
                                  <span class="text-success ms-2">In stock</span>
                                </div>-->

                <!--                <div class="mb-3">
                                  <span class="h5">$75.00</span>
                                  <span class="text-muted">/per box</span>
                                </div>-->

                <div class="mb-3">
                  <span class="h5">${{ auction.latest_bid != undefined ? auction.latest_bid.amount : auction.starting_price }}</span>
                  <span class="text-muted"> Current Bid</span>
                </div>

                <p v-html="auction.description"></p>

                <div class="row">
                  <dt class="col-3">Weight:</dt>
                  <dd class="col-9">{{ `${auction.weight} ${auction.weight_unit}` }}</dd>

                  <dt class="col-3">Ending On</dt>
                  <dd class="col-9">{{ formatDateOnly(auction.ending_at) }}</dd>


                  <dt class="col-3">Time Left</dt>
                  <dd class="col-9 time-left">{{ timeLeft }}</dd>

                  <dt class="col-3">Current Bid</dt>
                  <dd class="col-9">
                    <span class="c-amount mb-2">$ {{ auction.latest_bid != undefined ? auction.latest_bid.amount : auction.starting_price }}</span>
                    <br>
                   <div class="mt-1"> {{ auction.bids.length }} (<a href="javascript:void(0)" v-on:click="bidHistory = true">bid history</a>)</div>
                  </dd>
                </div>

                <hr/>


                <template v-if="!$page.props.auth.user">
                  <a :href="route('login')" class="btn btn-warning shadow-0 mt-3"> Login To Bid </a>
                </template>
                <template v-else>
                  <a v-if="$page.props.auth.user.type == 'customer'" href="javascript:void(0)" class="btn btn-warning shadow-0 mt-3" v-on:click="bidModal = true">Bid ${{ amount }}</a>

                </template>

              </div>
            </main>
          </div>
        </div>
      </section>
    </div>

    <div class="modal fade show" id="bidModal" tabindex="-1" role="dialog" aria-labelledby="bidModalTitle" :style="bidModal ? 'display: block' : 'display: none'">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bidModalLongTitle">BID - {{ auction.name }}</h5>
            <button type="button" class="btn btn-secondary close" v-on:click="bidModal = false" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <label for="bid-amount">Bid Amount</label><br>
            <div class="input-group mb-3">
              <span class="input-group-text">$</span>
              <input id="bid-amount" class="form-control" v-model="amount" type="number" name="amount" step="0.01" :min="auction.amount != undefined ? auction.latest_bid.amount : auction.starting_price">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" v-on:click="bidModal = false" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="updateBid">Confirm Bid</button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade show" id="bidHistory" tabindex="-1" role="dialog" aria-labelledby="bidHistoryTitle" :style="bidHistory ? 'display: block' : 'display: none'">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bidHistoryLongTitle">BID - {{ auction.name }}</h5>
          <button type="button" class="btn btn-secondary close" v-on:click="bidHistory = false" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tr class="bg-dark text-white text-center">
              <th>Date</th>
              <th>Bid Amount</th>
              <th>Bidder</th>
            </tr>
            <tbody style="max-height: 10px; overflow-y: auto">
            <tr v-for="bidder in auction.bids" v-bind:key="bidder.id">
              <td>{{ formatDate(bidder.created_at) }}</td>
              <td>{{ bidder.amount }}</td>
              <td>{{ bidder.customer_name }}</td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" v-on:click="bidHistory = false" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" v-on:click="updateBid">Confirm Bid</button>
        </div>
      </div>
    </div>
  </div>


  <FooterSection></FooterSection>
</template>

<script>


import Header from '@/Components/Frontend/Header'
import FooterSection from '@/Components/Frontend/Footer'
import Pagination from "@/Components/Pagination";
import {Inertia} from "@inertiajs/inertia";

export default {
  name: "AuctionDetail",
  props: {
    errors: Object,
    auction: Object,
    auction_images: Object,
  },
  components: {
    Header, FooterSection, Pagination
  },
  data() {
    return {
      bidModal: false,
      bidHistory: false,
      amount: parseFloat((this.auction.latest_bid != null ? this.auction.latest_bid.amount : this.auction.starting_price) + 0.10).toFixed(2),
      timeLeft: '',
      default_image: this.auction.thumbnail
    }
  },
  computed: {},
  methods: {
    getRemainingTime(timestamp) {
      // Calculate the difference between the timestamp and the current time.

      let endDate = new Date(timestamp + ' EST')

      const now = new Date();
      const delta = endDate - now;

      // Convert the difference to seconds.
      const seconds = delta / 1000;

      // Divide the number of seconds by the number of seconds in a day, hour, minute, and second to get the number of days, hours, minutes, and seconds remaining.
      const days = Math.floor(seconds / 86400);
      const hours = Math.floor((seconds % 86400) / 3600);
      const minutes = Math.floor(((seconds % 86400) % 3600) / 60);
      const secondsRemaining = ((seconds % 86400) % 3600) % 60;

      console.log(`${days}D ${hours}h ${minutes}m ${parseInt(secondsRemaining)}s`)
      console.log(days <= 0 && hours <= 0 && minutes <= 0 && secondsRemaining <= 0)

      if (days <= 0 && hours <= 0 && minutes <= 0 && secondsRemaining <= 0) {
        // const url = `${route("auctions.index")}`;
        // Inertia.visit(url, { preserveState: true });
      }

      let nextInterval = 1000;
      if (days > 0) {
        this.timeLeft = `${days}D ${hours}h ${minutes}m`;
        nextInterval = 60000

      } else {
        this.timeLeft = `${hours}h ${minutes}m ${parseInt(secondsRemaining)}s`;

      }

      const ev = this;
      setTimeout(function () {
        ev.getRemainingTime(timestamp)
      }, nextInterval)


    },
    formatDate(dateTime) {
      const today = new Date(dateTime);

      let date = today.getDate();
      let month = today.getMonth() + 1;
      let year = today.getFullYear();
      let hour = today.getHours();
      let minute = today.getMinutes();
      let seconds = today.getSeconds();

      return `${date}-${month}-${year} ${hour}:${minute}:${seconds}`;
    },
    formatDateOnly(dateTime) {
      const today = new Date(dateTime);
      let date = today.getDate();
      let month = today.getMonth() + 1;
      let year = today.getFullYear();

      return `${date}-${month}-${year}`;
    },
    updateBid() {
      let auctionID = this.auction.id
      if (this.amount <= (this.auction.latest_bid != null ? this.auction.latest_bid.amount : this.auction.starting_price)) {
        alert('Price should be greater than current bid.')
        return;
      } else {
        axios.post(this.route('auctions.bid'), {
          id: this.auction.id,
          amount: this.amount,
        }).then(function (response) {
          if (response.data.status == 1) {
            alert(response.data.message);
            const url = `${route("auctions.detail", auctionID)}`;
            Inertia.visit(url, {});
          } else {
            alert(response.data.message);
          }
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    getServerTime() {
      const xhr = new XMLHttpRequest();
      xhr.open('HEAD', window.location.href, false);
      xhr.setRequestHeader('Content-Type', 'text/html');
      xhr.send('');
      let dateHeaderValue = xhr.getResponseHeader('Date');
      var date = new Date(dateHeaderValue);

      var timezoneOffsetMinutes = date.getTimezoneOffset();

      var offsetHours = Math.floor(Math.abs(timezoneOffsetMinutes) / 60);
      var offsetMinutes = Math.abs(timezoneOffsetMinutes) % 60;

      var offsetSign = (timezoneOffsetMinutes < 0) ? '-' : '+';

      var offsetString = offsetSign +
          ('0' + offsetHours).slice(-2) + // Add leading zeros if necessary
          ':' +
          ('0' + offsetMinutes).slice(-2); // Add leading zeros if necessary

      var formattedDate = date.toLocaleString() + ' (UTC' + offsetString + ')';

      console.log(formattedDate);
    }

  },
  mounted() {
    const ending_at = this.auction.ending_at
    this.getRemainingTime(ending_at);
  }
}
</script>

<style scoped>
.main-image {
  aspect-ratio: 1;
  width: 100%;
}

.time-left {
  color: #d90000;
  font-weight: 600;
}

.c-amount {
  padding: 6px;
  background-color: #2a4e70;
  color: #fff;
  font-weight: 800;
  font-size: 20px;
  border-radius: 6px;
}


</style>