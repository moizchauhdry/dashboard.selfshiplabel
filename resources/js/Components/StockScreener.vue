<template>
			<div class="row mb-5">
				<div class="col-md-12">
            <h3 class="font-weight-bold text-3 mt-4 mt-md-0">Stock Screener</h3>
						<table class="table table-striped">
						<thead>
							<tr>
							<th>Company</th>
							<th>Price</th>
							<th>Volume</th>	
							<th>Open</th>	
							<th>High</th>	
							<th>Low</th> 
							</tr>
						</thead>

						<tbody>
							<tr v-for="(hd,index) in HistoricalData.historical" :key="index">
							<td>{{ hd.date}}</td>
							<td>{{ hd.close}}</td>
							<td>{{ hd.volume }}</td>
							<td>{{ hd.open}}</td>
							<td>{{ hd.high}}</td>
							<td>{{ hd.low}}</td>
							</tr>
						</tbody>
						</table>
				</div>
			</div>  
</template>

<script>

export default {
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
