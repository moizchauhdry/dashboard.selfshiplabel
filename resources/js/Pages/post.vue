<template>
<header-component></header-component>
<div role="main" class="main">

				<section class="page-header page-header-modern bg-color-light-scale-1 page-header-md" style="padding:88px 0px;">
					<div class="container" >
						<div class="row">

							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="text-dark font-weight-bold text-8">{{ post.post_title}}</h1>
							</div>

							<div class="col-md-12 align-self-center order-1">
								<ul class="breadcrumb d-block text-center">
									<li><inertia-link href="/">Home</inertia-link></li>
									<li > {{ post.post_title}}</li>
								</ul>
							</div>
						</div>
					</div>
				</section>

				<div class="container py-4">

					<div class="row">
						<div class="col-md-12">
							<div class="blog-posts single-post">

							<article class="post post-large blog-single-post border-0 m-0 p-0">

									<div class="post-content ml-0">

										<h2 class="font-weight-semi-bold" style="font-size:48px"><a href="javascript:void(0)">{{ post.post_title}}</a></h2>


                                        <div v-html="post.post_content" class="blog-content" style="padding:25px;"></div>
									

									</div>
								</article>	

							</div>
						</div>

					</div>

				</div>

			</div>			
            <footer-component></footer-component>
</template>
<style>
.blog-content p, div, li, span {
    color: #1d2228;
    font-size: 16px;
    line-height: 32px;
}
</style>
<style scoped>
	img{ margin:auto}
	section{
		background-image: url('/theme/shipping-banners.jpg');
	}
	section .container{
		
		background: rgba(255,255,255,0.5);
	}
</style>
<script>
import HeaderComponent from '../Components/Header.vue'
import FooterComponent from '../Components/Footer.vue'

    export default {
        components: {
        'header-component': HeaderComponent,
        'footer-component': FooterComponent,    
		
        },   
         props: {
            errors: Object,
            post: Object,
        },
        data() {
            return {
                 
            }
        },
		methods:{
			showImage(image_path){
				return '/uploads/'+image_path;
			},
		},
        computed: {
                // a computed getter
            }
    }
</script>
<style scoped>
    p{
        padding: 8px;
    }
    .wp-block-image figure img{
        display: inline-block;
        max-width: 60%;
    }
</style>
