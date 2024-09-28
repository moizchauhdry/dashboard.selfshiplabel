<template>
    <MainLayout>
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h5><b>Manage Inquiries</b></h5>
                    </div>
                </div>
                <div class="card-body">
                    <flash-messages></flash-messages>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Ticket ID</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Inquiry Date</th>
                                    <th scope="col">Inquiry Status</th>
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
                                    <td>{{ formatDateTime(p.created_at) }}</td>
                                    <td> 
                                        <span v-if="p.status === 'open'" class="badge badge-primary"> Open</span>
                                        <span v-else class="badge badge-success">Close</span>
                                    </td>
                                    <td>
                                        <inertia-link
                                            :href="route('inquirie.fetch', { user_id: p.user_id, track_id: p.id })"
                                            class="btn btn-info btn-sm">
                                            <i class="fa-solid fa-message"></i> Detail
                                        </inertia-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination class="mt-6" :links="inquiries.links" />
                </div>
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
        formatDateTime(date) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true, // Set to true for 12-hour format with AM/PM
            };
            return new Date(date).toLocaleString(undefined, options);
        }
    }
}
</script>