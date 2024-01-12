<template>
    <MainLayout>
      <div class="card mt-4">
      <div class="card-body stock-subscription-form">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Manage Users
        </h2>
            <div class="col-md-4" style="float: right;margin-bottom: 30px ">
            <form action="/users" method="get">
              <input type="text" name="search"  class="form-control" placeholder="Search By Suite #">
            </form>
          </div>
            <div class="col-md-6" >
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">               
                    <inertia-link :href="route('create-users')" 
                        v-if="$page.props.auth.user.type == 'admin'"
                        class="btn btn-success float-right">Add New User</inertia-link>
                </h2>
            </div>

          <flash-messages ></flash-messages>
          <table class="table">

					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Name</th>
                    <th scope="col">Phone No</th>
                    <th>Role</th>
    					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>
					  <tr v-for="user in users.data" :key="user.id">
					  	<td>{{ user.id }}</td>
					  	<td>{{ user.name  }}</td>
              <td>{{ user.phone_no }}</td>
              	<td>{{ user.type  }}</td>
 					  	<td>
					  		<inertia-link :href="route('edit-users',{ id: user.id })" class="btn btn-info">Edit</inertia-link> |
					  		<inertia-link href="" class="btn btn-danger" @click="destroy(user.id)">Delete</inertia-link>
					  	</td>
					  </tr>
					  </tbody>
					</table>
          <pagination class="mt-6" :links="users.links" />
        </div>
        </div>
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
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
            BreezeAuthenticatedLayout,
            MainLayout,
            Pagination,

        },
    props: {
         users: Object,
    },
        
    watch:{
      params:{
        handler(){
          this.$inertia.get(this.route('users'),this.params,{replace:true,preserveState:true});
        }
      }
    },
    methods:{
      siuteNum(user_id){
                return 4000 + user_id;
            },
      destroy(id) {
            if (confirm('Are you sure you want to delete this Customer?')) {
                this.$inertia.delete(this.route('delete-users', id))
            }
        }
      }
    }
</script>
