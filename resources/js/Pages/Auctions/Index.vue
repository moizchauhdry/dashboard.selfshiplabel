<template>
    <Header></Header>

    <div class="container-xl">
      <div class="row">
        <div class="col-md-3">
          <h2 style="font-size: 25px;font-weight: 700;text-transform: uppercase;">Category</h2>
          <div class="hr">
            &nbsp;
          </div>
          <ul class="list-category">
            <li v-on:click="selectCat('')">All </li>
            <li v-for="cat in categories" v-bind:key="cat.id" v-on:click="selectCat(cat.id)">{{ cat.name }} ({{ cat.auctions_count}})</li>
          </ul>
        </div>
        <div class="col-md-9">
          <div class="d-flex justify-between sect">
            <div>{{ auctions.total }} items found</div>
            <div>
              <select name="filter" id="" v-model="form.filter" v-on:change="submit">
                <option value="">Filter :</option>
                <option value="new">Filter : Newest</option>
                <option value="old">Filter : Oldest</option>
                <option value="htl">Filter : High to Low</option>
                <option value="lth">Filter : Low to High</option>
              </select>
            </div>
          </div>
          <div class="hr">
            &nbsp;
          </div>
          <div class="row">
            <template v-for="auction in auctions.data" :key="auction.id">
              <div class="col-md-3 mb-2">
                <div class="card p-2">
                  <img :src="auction.thumbnail" class="card-img-top img-aspect" alt="...">
                  <hr>
                  <div class="d-flex text-123">
                    <strong>Current Bid : </strong>
                    <span>&nbsp; $ {{ auction.latest_bid != null ?  auction.latest_bid.amount : auction.starting_price }}</span>
                  </div>
                  <div class="d-flex time text-123">
                    <strong>Time Left : </strong>
                    <span>&nbsp; {{ getRemainingTime(auction.ending_at) }}</span>
                  </div>
                  <div class="hr">
                    &nbsp;
                  </div>
                  <inertia-link class="view-btn" :href="route('auctions.detail', auction.id)">View Detail</inertia-link>
                </div>
              </div>
            </template>
            <div class="col-md-12">
              <Pagination :links="auctions.links" class="float-right"></Pagination>
            </div>
          </div>

        </div>
      </div>
    </div>
</template>
<script>

import Header from '@/Components/Header.vue'
import Pagination from "@/Components/Pagination.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    props: {
        errors: Object,
        auctions: Object,
        serverTime:String,
        categories:Object,
    },
    components: {
        Header, Pagination
    },
    data() {
        return {
          form:{
            filter:"",
            category:""
          }
        }
    },
    computed: {
    },
    methods: {
      submit() {
        const queryParams = new URLSearchParams(this.form);
        const url = `${route("auctions.index")}?${queryParams.toString()}`;
        Inertia.visit(url, { preserveState: true });
      },
      selectCat(id){
        this.form.category = id
        this.submit();
      },
      getRemainingTime(timestamp) {
        console.log(this.serverTime)
        // Calculate the difference between the timestamp and the current time.

        let endDate = new Date(timestamp)

        const now = new Date(this.serverTime);
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
  }

  .d-flex.time.text-123 {
    font-size: 12px;
  }

  .sect{
    line-height: 42px;
  }

  .hr{
    border-top: 2px solid #d5d5d5;
    margin-top: 5px;
  }

  .view-btn{
    text-align: center;
    width: 100%;
    padding: 5px;
    background: #fab906;
    color: #0a0a0a;
    margin-top: 5px;
  }

  .view-btn:hover{
    background: #ffd350;
  }

  .list-category li{
    cursor: pointer;
  }

  .list-category li:hover{
    font-weight: 800;
    cursor: pointer;
  }


</style>