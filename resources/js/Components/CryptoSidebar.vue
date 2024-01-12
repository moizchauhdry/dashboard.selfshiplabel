<template>
			<div class="row mb-5">
				<div class="col-md-12">
            <h3 class="font-weight-bold text-3 mt-4 mt-md-0">Crypto Currency</h3>
						<table class="table table-striped">
						<thead>
							<tr>
							<th>Symbol</th>
							<th>Last Price</th>
							<th>Change</th>	
							<th>% Change</th>	
							</tr>
						</thead>

						<tbody>
							<tr v-for="(crypto,index) in cryptoCurrency" :key="index">
							<td class="name">{{ crypto.symbol }}</td>
							<td class="price">{{ crypto.price.toFixed(3) }}</td>
							<td class="change">{{ crypto.change.toFixed(3) }}</td>
							<td class="change">{{ crypto.changesPercentage.toFixed(3) }}</td>
							</tr>
						</tbody>
						</table>
				</div>
			</div>  
</template>
<style scoped>
	td{font-weight:700 !important;}
	td.name{color:#0088CC}
	td.price{color:#000}
	td.change{color:green}
</style>

<script>

export default {
  data() {
    return {
      cryptoCurrency:[]
    };
  },
  methods: {
    getMainBlogs(){
      axios
        .get("/api/crypto-sidebar")
        .then(({ data }) => {
            this.cryptoCurrency = data;                
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
