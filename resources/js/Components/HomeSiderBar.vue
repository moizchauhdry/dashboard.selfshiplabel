<template>
    <article class="post post-large" v-for="mainBlog in mainBlogs" :key="mainBlog.id">
        <div class="post-image">
            <a href="blog-post.html">
                <img :src="mainBlog.post_image" class="img-fluid img-thumbnail img-thumbnail-no-borders rounded-0" :alt="mainBlog.post_title" />
            </a>
        </div>

        <div class="post-date text-1">
            <span class="day border-radius-0 text-4 text-dark">{{ mainBlog.date }}</span>
            <span class="month border-radius-0 text-1 bg-color-dark">{{mainBlog.month}}</span>
        </div>

        <div class="post-content">

            <h2 class="font-weight-semibold text-6 line-height-3 mb-3"><a href="blog-post.html" class="text-color-dark text-color-hover-primary">{{mainBlog.post_title}}</a></h2>
            <p>{{mainBlog.post_content}} [...]</p>

            <div class="post-meta">
                <span><i class="far fa-user"></i> By <a href="#">Mark Allen</a> </span>
                <span><i class="far fa-folder"></i> 
                      
                      <a href="#" v-for="category in mainBlog.post_category" :key="category">{{ category }}</a>
                       </span>
                <span><i class="far fa-comments"></i> <a href="#">12 Comments</a></span>
                <span class="d-block d-sm-inline-block float-sm-right mt-3 mt-sm-0"><a href="blog-post.html" class="btn btn-xs btn-light text-1 text-uppercase">Read More</a></span>
            </div>

        </div>
    </article>   
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
