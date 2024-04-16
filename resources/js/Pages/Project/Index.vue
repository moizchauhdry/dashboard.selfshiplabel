<template>
    <MainLayout>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h5><b>Manage Projects</b></h5>
                </div>
            </div>
            <div class="card-body">
                <flash-messages></flash-messages>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Project ID</th>
                                <th scope="col">Project Name</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in projects.data" :key="p.id">
                                <td>{{ p.id }}</td>
                                <td>{{ p.name }}</td>
                                <td>
                                    <inertia-link :href="route('project.markup', { project_id: p.id })"
                                        class="btn btn-info btn-sm"><i class="fas fa-dollar"></i> Markup</inertia-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination class="mt-6" :links="projects.links" />
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
            //
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        MainLayout,
        Pagination,
    },
    props: {
        projects: Object,
    },

    watch: {
        params: {
            handler() {
                this.$inertia.get(this.route('project.index'), this.params, { replace: true, preserveState: true });
            }
        }
    },
    methods: {
        //
    }
}
</script>