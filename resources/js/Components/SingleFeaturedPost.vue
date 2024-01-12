<template>
  <h3 class="font-weight-bold text-3 mt-4 mt-md-0">Featured Posts</h3> 
    <div  v-for="featuredBlog in featuredBlogs" :key="featuredBlog.id">
        <a href="blog-post.html">
            <article>
                <div class="thumb-info thumb-info-no-borders thumb-info-bottom-info thumb-info-bottom-info-dark thumb-info-bottom-info-show-more thumb-info-no-zoom border-radius-0">
                    <div class="thumb-info-wrapper thumb-info-wrapper-opacity-6">
                        <img :src="featuredBlog.post_image" class="img-fluid" :alt="featuredBlog.post_image">
                        <div class="thumb-info-title bg-transparent p-4">
                            <div class="thumb-info-type bg-color-primary px-2 mb-1">Stock Watch</div>
                            <div class="thumb-info-inner mt-1">
                                <h2 class="text-color-light line-height-2 text-4 font-weight-bold mb-0">{{ featuredBlog.post_title }}</h2>
                            </div>
                            <div class="thumb-info-show-more-content">
                                <p class="mb-0 text-1 line-height-9 mb-1 mt-2 text-light opacity-5">{{ featuredBlog.post_content }}...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </a>
    </div>
</template>

<script>

export default {
  data() {
    return {
      featuredBlogs:[]
    };
  },
  methods: {
    getFeaturedBlogs(){
      axios
        .get("/api/featuredBlogs")
        .then(({ data }) => {
            this.featuredBlogs = data.data;                
            }
        );
    }
  },
  mounted() {},
  created() {
    //this.$Progress.start();
    this.getFeaturedBlogs();
    //this.$Progress.finish();
  }
};
</script>
