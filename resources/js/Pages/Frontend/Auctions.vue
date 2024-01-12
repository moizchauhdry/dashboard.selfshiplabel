<template>
  <Header></Header>
  <!-- MAIN SECTION -->
  <header>
    <div class="mb-4 ">
      <div class="container py-4 header ">
        &nbsp;
      </div>
    </div>
    <!-- Heading -->
  </header>

  <!-- sidebar + content -->
  <section class="">
    <div class="container">
      <div class="row">
        <!-- sidebar -->
        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 mb-2" >

          <h4 class="pt-2 m-2">All Category</h4>
          <hr>
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">
              <ul class="list-unstyled" >

                <li v-on:click="selectCat('')">
                  <a href="javascript:void(0)" class="text-dark text-decoration-none py-2 " id="sidebar">All</a>
                </li>
                <li v-for="cat in categories" v-bind:key="cat.id" v-on:click="selectCat(cat.id)">
                  <a href="javascript:void(0)" class="text-dark text-decoration-none py-2 " id="sidebar">{{ cat.name }} </a>
                </li>
              </ul>
            </div>
          </div>



        </div>

        <!-- sidebar -->
        <!-- content -->
        <div class="col-lg-10">
          <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
            <strong class="d-block py-2">{{ auctions.total }} Items found </strong>
            <div class="ms-auto">
              <select class="form-select d-inline-block w-auto border pt-1" name="filter" id="" v-model="form.filter" v-on:change="submit">
                <option value="">Filter :</option>
                <option value="new">Newest</option>
                <option value="old">Oldest</option>
                <option value="htl">High to Low</option>
                <option value="lth">Low to High</option>
              </select>

              <!-- <div class="btn-group shadow-0 border">
                <a href="#" class="btn btn-light" title="List view">
                  <i class="fa fa-bars fa-lg"></i>
                </a>
                <a href="#" class="btn btn-light active" title="Grid view">
                  <i class="fa fa-th fa-lg"></i>
                </a>
              </div> -->
            </div>
          </header>

          <div class="row">
            <template v-for="auction in auctions.data" :key="auction.id">
            <div class="col-lg-3 col-md-6 col-sm-6 d-flex" >
              <div class="card w-100 my-2 shadow-2-strong" id="box">
                <img :src="auction.thumbnail" class="card-img-top" />
                <div class="card-body d-flex flex-column">
                  <div class="d-flex flex-row">
                    <h5 class="mb-3 me-1">Current Bid : <span class="text-danger">$ {{ auction.latest_bid != null ?  auction.latest_bid.amount : auction.starting_price }}</span></h5>

                  </div>
                  <p class="card-text">Time Left : <span class="text-danger">&nbsp; {{ getRemainingTime(auction.ending_at) }}</span> </p>
                  <div class="card-footer d-flex align-items-end pt-4 px-1 pb-0 mt-auto">
                    <inertia-link :href="route('auctions.detail', auction.id)" class="btn btn-warning w-100 me-1">View Bid</inertia-link>
                  </div>
                </div>
              </div>
            </div>
            </template>

          </div>

          <hr />

          <div class="col-md-12">
            <Pagination :links="auctions.links" class="float-right"></Pagination>
          </div>
          <!-- Pagination -->
<!--          <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">5</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>-->
          <!-- Pagination -->
        </div>
      </div>
    </div>
  </section>
  <!-- sidebar + content -->
  <FooterSection></FooterSection>
</template>

<script>


import Header from '@/Components/Frontend/Header'
import FooterSection from '@/Components/Frontend/Footer'
import Pagination from "@/Components/Frontend/Pagination";
import {Inertia} from "@inertiajs/inertia";

export default {
  name: "Auctions",
  props: {
    errors: Object,
    auctions: Object,
    serverTime:String,
    categories:Object,
  },
  components: {
    Header,FooterSection, Pagination
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
    selectCat(id,ev){
      this.form.category = id
      this.submit();
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
    getRemainingTime(timestamp) {
      console.log(this.serverTime)
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


      if(days <= 0 && hours <= 0 && minutes <= 0 && secondsRemaining <= 0 ){
        // const url = `${route("auctions.index")}`;
        // Inertia.visit(url, { preserveState: true });
      }

      let nextInterval = 1000;
      if(days > 0){
        return  `${days}D ${hours}h ${minutes}m`;
        nextInterval = 60000

      }else{
        return  `${hours}h ${minutes}m ${parseInt(secondsRemaining)}s`;

      }

    }

  }
}
</script>

<style scoped>
.card-img-top{
  aspect-ratio: 1/1;
}
</style>