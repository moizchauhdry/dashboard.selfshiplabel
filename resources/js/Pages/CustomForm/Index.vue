<template>
    <MainLayout>
		<div class="card">
            <div class="card-header">
                Custom Form
                <inertia-link class="btn btn-primary float-right" :href="route('custom-form.create')">
                    <i class="fas fa-plus mr-1"></i><span>Add New</span>
                </inertia-link>
            </div>
				<div class="card-body">                    
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ship From</th>
                                <th>Ship To</th>
                                <th>Package</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="custom in customs.data" :key="custom.id">
                                <td>{{ custom.id }}</td>
                                <td>
                                    {{ custom.ship_from_email }} <br>
                                    {{ custom.ship_from_contact }} <br>
                                    {{ custom.ship_from_country }} <br>
                                </td>
                                <td>
                                    {{ custom.ship_to_email }} <br>
                                    {{ custom.ship_to_contact }} <br>
                                    {{ custom.ship_to_country }} <br>
                                </td>
                                <td>
                                    {{ custom.package_date }} <br>
                                    {{ custom.package_no }} <br>
                                    {{ custom.tracking_number }} <br>
                                </td>
                                <td>
                                    <!-- <inertia-link class="nav-link" :href="route('warehouses.create', custom.id)">
                                        <i class="fas fa-external-link-alt"></i><span>Edit</span>
                                    </inertia-link> -->

                                    <a :href="route('custom-form.print', custom.id)" class="btn btn-warning btn-sm m-1" target="_blank">
                                        <i class="fa fa-print mr-1"></i>Print</a>
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
            customs:Object,
        },
    }
</script>
