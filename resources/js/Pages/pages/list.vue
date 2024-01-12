<template>
    <MainLayout>
		<div class="card mt-4">
        <div class="card-body">

            <div class="row">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
                        Manage Pages
                </h2>
                <div class="col-md-6" >
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">               
                    <!-- <inertia-link :href="route('page_new')" class="btn btn-success float-right">Add New Page</inertia-link> -->
                    </h2>
                </div>
                <!-- <div class="col-md-6" >
                    <input type="text" class="form-control" placeholder="Search">
                </div>-->
            </div>
        <div class="table-responsive mt-6">
          <table class="table">

					  <thead>
					    <tr>
					      <th scope="col">id</th>
					      <th scope="col">Title</th>
                <th scope="col">Page Description</th>
                <th scope="col">Created At</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>
					  <tr v-for="page in cms.data" :key="page.id">
					  	<td>{{ page.id }}</td>
					  	<td><a :href="page_url(page.post_url)" target="_blank">{{ page.title }}</a></td>
                        <td>{{ shortDescription(page.description) }}</td>
                        <td>{{ page.created_at }}</td>
					  	<td>
					  		<inertia-link :href="route('page_edit',{ id: page.id })" class="btn btn-info">Edit</inertia-link>
					  		<!-- <inertia-link href="" class="btn btn-danger" @click="destroy(post.id)">Delete</inertia-link> -->
					  	</td>
					  </tr>
					  </tbody>
					</table>
        </div>
        <pagination class="mt-6" :links="cms.links" />
        </div>
        </div>
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import Pagination from '@/Components/Pagination'


    export default {
        data() {
        return {
                    units: {},
                    term: '',
                    form: this.$inertia.form({
                            search: ''
                        }),
                    params:{
                    search:null,
                    },
                }
            },
          components: {

            MainLayout,
            Pagination,
        },
        props: {
            cms: Object,
        },
        watch:{
            params:{
                handler(){
                this.$inertia.get(this.route('pages_list'),this.params,{replace:true,preserveState:true});
                }
            }
        },
        computed:{
            
        },
        methods:{
            destroy(id) {
                if (confirm('Are you sure you want to delete this page?')) {
                    this.$inertia.delete(this.route('deletelead', id))
                }
            },
            page_url(url){
                return '/'+url;
            },
            shortDescription(description){
              let strippedString = description.replace(/(<([^>]+)>)/gi, "");
              strippedString = strippedString.slice(0,200)
              return strippedString;
            }
        }
    }
</script>
