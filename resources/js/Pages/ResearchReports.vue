<template>
	<NoSideBar>
     <section class="section border-0 m-0 pb-3">
                    <div class="container">
                        <div class="row pb-1">


                        <div class="col-lg-3 mb-4 pb-2" v-for="(post,index) in posts.data" :key="index">
							<inertia-link  :href="post.post_url">
								<article class="thumb-info thumb-info-no-borders thumb-info-bottom-info thumb-info-bottom-info-dark thumb-info-bottom-info-show-more thumb-info-no-zoom border-radius-0">
									<div class="thumb-info-wrapper thumb-info-wrapper-opacity-6">
									
										<img :src="showImage(post.post_image)" class="img-fluid" :alt="post.post_title" style="max-height:228px;" />
										
										<div class="thumb-info-title bg-transparent p-4">
											<div class="thumb-info-type bg-color-dark px-2 mb-1">Photography</div>
											<div class="thumb-info-inner mt-1">
												<h2 class="font-weight-bold text-color-light line-height-2 text-5 mb-0">{{post.post_title}}</h2>
											</div>
											<div class="thumb-info-show-more-content">
												<p class="mb-0 text-1 line-height-9 mb-1 mt-2 text-light opacity-5" v-html="post.post_content.substring(0,150)"></p>
											</div>
										</div>

									</div>
								</article>
							</inertia-link>
						</div>        
						
						<pagination class="mt-6" :links="posts.links" />

						                            
                        </div>
                    </div>
                </section>
	</NoSideBar>
</template>
<script>
    import Pagination from '@/Components/Pagination'
 	import NoSideBar from '@/Layouts/NoSideBar'

    export default {
        components: {
			NoSideBar,
            Pagination,
        },   
         props: {
            posts: Object,
        },
        data() {
            return {
                 
            }
        },
        computed: {
            },
        methods: {
            showImage(img){
				return '/uploads/' + img;
			}

        },
		created() {
			console.log(this.posts);
		}
    }
</script>