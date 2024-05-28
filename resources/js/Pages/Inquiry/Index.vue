<template>
    <MainLayout>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h5><b>Manage Inquiries</b></h5>
                </div>
            </div>
            <div class="card-body">
                <flash-messages></flash-messages>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Department</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in inquiries.data" :key="p.id">
                                <td>{{ p.id }}</td>
                                <td>{{ p.name }}</td>
                                <td>{{ p.email }}</td>
                                <td>{{ p.subject }}</td>
                                <td>{{ p.department }}</td>
                                <td> <span v-if="p.status === 'open'" class=" bg-blue-600 text-white px-4 py-1 rounded-lg text-sm"> Open</span>
                                        <span v-else class=" bg-red-400 text-white px-4 py-1 rounded-lg text-sm"> Close</span>
                                    </td>
                                <td>
                                    <inertia-link v-if="p.status === 'open' " :href="route('inquirie.fetch', { user_id: p.user_id ,track_id: p.id })"
                                        class="btn btn-info btn-sm"> <i class="fa-solid fa-message"></i></inertia-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination class="mt-6" :links="inquiries.links" />
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
        inquiries: Object,
    },

    watch: {
        params: {
            handler() {
                this.$inertia.get(this.route('inquirie.index'), this.params, { replace: true, preserveState: true });
            }
        }
    },
    methods: {
        //
    }
}
</script>