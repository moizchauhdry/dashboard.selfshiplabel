<template>
    <h4>Quotes</h4>
    <ul class="list list-icons list-icons-sm">
        <li><i class="fas fa-caret-right"></i> <inertia-link :href="route('market-earning-activity-details',{ symbol: symbol })">Summary </inertia-link></li>
        <!-- <li><i class="fas fa-caret-right"></i> <inertia-link :href="route('market-earning-activity-real-time',{ symbol: symbol })">Real-Time</inertia-link></li>
        <li><i class="fas fa-caret-right"></i> After Hours</li>
        <li><i class="fas fa-caret-right"></i> Pre Market</li>
         <li><i class="fas fa-caret-right"></i> Charts</li> -->
    </ul>    
    <h4>News & Analysis</h4>
    <ul class="list list-icons list-icons-sm">
        <li><i class="fas fa-caret-right"></i> <inertia-link :href="route('market-earning-activity-news',{ symbol: symbol })">News</inertia-link></li>
        <li><i class="fas fa-caret-right"></i> <inertia-link :href="route('market-earning-activity-news',{ symbol: symbol })">Press Releases </inertia-link></li>
        <li><i class="fas fa-caret-right"></i> Analyst Research</li>
    </ul>    
    <hr class="solid my-5" />
    <ul class="list list-icons list-icons-sm">
        <li><i class="fas fa-caret-right"></i><inertia-link :href="route('market-earning-activity-dividend-history',{ symbol: symbol })">Dividend History</inertia-link></li>
        <li><i class="fas fa-caret-right"></i><inertia-link :href="route('market-earning-activity-historical-data',{ symbol: symbol })">Historical Quotes</inertia-link></li>
        <li><i class="fas fa-caret-right"></i><inertia-link :href="route('market-earning-activity-financials',{ symbol: symbol })">Financials</inertia-link></li>
        <!-- <li><i class="fas fa-caret-right"></i>Earnings</li>
        <li><i class="fas fa-caret-right"></i>P/E & PEG Ratios</li>
        <li><i class="fas fa-caret-right"></i>Option Chain</li>
        <li><i class="fas fa-caret-right"></i>Short Interest</li>
        <li><i class="fas fa-caret-right"></i>Institutional Holdings</li>
        <li><i class="fas fa-caret-right"></i>Insider Activity</li>
        <li><i class="fas fa-caret-right"></i>SEC Filings</li>
        <li><i class="fas fa-caret-right"></i>Revenue EPS </li>    -->
    </ul>    
</template>
<style scoped>
  h4{ font-size: 18px;margin: 10px auto;font-weight: 700;}
</style>
<script>

export default {
  props:{
    symbol:String,
  },
  data() {
    return {
      mainBlogs:[]
    };
  },
  methods: {
    getMainBlogs(){
      axios
        .get("/api/mainBlogs")
        .then(({ data }) => {
            this.mainBlogs = data.data;                
            }
        );
    }
  },
  mounted() {},
  created() {
    //this.$Progress.start();
    this.getMainBlogs();
    //this.$Progress.finish();
  }
};
</script>
