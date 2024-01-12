<template>
    <MainLayout>
		<div class="card mt-4">
				<div class="card-body">                    

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
                        Warehouses 
                    </h2>
                    <inertia-link class="btn btn-primary nav-link" style="float:right;" :href="route('warehouses.create')">
                        <i class="fas fa-external-link-alt"></i><span>Add New</span>
                    </inertia-link>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Country</th>
                            <th scope="col">State</th>
                            <th scope="col">City</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="warehouse in warehouses.data" :key="warehouse.id">
                            <th scope="col">#</th>
                            <td>{{ warehouse.name }}</td>
                            <td>{{ warehouse.country.nicename }}</td>
                            <td>{{ warehouse.state }}</td>
                            <td>{{ warehouse.city }}</td>
                            <td>{{ warehouse.phone }}</td>
                            <td>{{ warehouse.address }}</td>
                            <td>
                                <inertia-link class="nav-link" :href="route('warehouses.edit', warehouse.id)">
                                    <i class="fas fa-external-link-alt"></i><span>Edit</span>
                                </inertia-link>

                                <!-- <inertia-link class="nav-link" :href="route('warehouses.destroy', warehouse.id)" method="delete">
                                    <i class="fas fa-external-link-alt"></i><span>Delete</span>
                                </inertia-link> -->
                            </td>
                        </tr>
                        </tbody>
					</table>
                </div>
            </div>

    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'


    export default {
        components: {
            BreezeAuthenticatedLayout,
            MainLayout,
        },
        props: {
            auth: Object,
            warehouses:Object,
        },
        methods : {
            destroy(address_id) {
                if (confirm('Are you sure you want to delete this address?')) {
                    this.$inertia.delete(this.route('address.destroy', address_id))
                }
            },
        }
    }
</script>
