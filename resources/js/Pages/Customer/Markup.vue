<template>
    <MainLayout>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h5><b>Manage Customers - Markup</b></h5>
                </div>
            </div>
            <div class="card-body">
                <flash-messages></flash-messages>

                <form @submit.prevent="submit">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th colspan="3">Customer #{{ customer_id }}</th>
                                    <th>
                                        <input type="submit" value="Update Settings"
                                            class="btn btn-success btn-sm float-right" />
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col">SR.NO.</th>
                                    <th scope="col">Service Name</th>
                                    <th scope="col">Service Code</th>
                                    <th scope="col">Markup Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(record, i) in records" :key="record.id">
                                    <td>{{ i + 1 }}</td>
                                    <td>{{ record.s_name }}</td>
                                    <td>{{ record.us_service_id }}</td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Markup Percentage"
                                            :value="record.us_percentage"
                                            @input="updatePercentage(i, $event.target.value)" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

        </div>
    </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

export default {
    data() {
        return {
            form: this.$inertia.form({
                records: this.records,
                customer_id: this.customer_id,
            })
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        MainLayout,
    },
    props: {
        records: Object,
        customer_id: Object,
    },
    methods: {
        updatePercentage(index, value) {
            this.form.records[index].us_percentage = value;
        },
        submit() {
            this.form.post(this.route('customers.markup-update'))
        }
    }
}
</script>