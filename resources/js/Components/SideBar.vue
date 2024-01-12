<template>
    <aside class="widget widget_banner" style="border:1px solid #1D51C7;margin-bottom:35px;color:#fff;" >
      <h2 class="font-weight-bold text-8" style="text-align:center;width:100%;color:#F9A62F;line-height:80px;" >Summit Stocks Returns</h2>
      <div style="display:inline-block;width:100%;line-height:20px;text-align:center;border-top:1px solid #1D51C7;" >
        <div style="display:inline-block;width:45%;margin:2%;line-height:60px;text-align:center;border-right:1px solid #1D51C7;" >
          <strong style="font-size:larger;" >Summit Stock</strong>
          <p style="font-size:x-large;font-weight:900;color:green;" >599%</p>
          </div>
          <div style="display:inline-block;width:45%;margin:2%;line-height:60px;text-align:center;" >
            <strong style="font-size:larger;" >S&amp;P 500</strong>
            <p style="font-size:x-large;font-weight:900;color:green;" >129%</p>
            </div>
          </div>
            <div style="display:inline-block;width:100%;line-height:20px;text-align:center;margin-top:50px;" >
              <p >Summit Stock Launches in 2019</p>
              <a href="/invest-better-with-summit-stocks" class="btn btn-modern btn-secondary mb-2" >JOIN SUMMIT STOCK</a>
            </div>
    </aside>
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
