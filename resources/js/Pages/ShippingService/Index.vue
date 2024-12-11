<template>
    <MainLayout>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h5><b>Shipping Services</b></h5>
                </div>
            </div>
            <div class="card-body">
                <flash-messages></flash-messages>

                <form @submit.prevent="submit">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">SR #</th>
                                    <th scope="col">Service Name</th>
                                    <th scope="col">Service Code</th>
                                    <th scope="col">
                                        <input type="submit" value="Save & Update"
                                            class="btn btn-success float-right" />
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="service,i in services" :key="service.id">
                                    <td>{{ i + 1 }}</td>
                                    <td>{{ service.service_name }}</td>
                                    <td>{{ service.service_code }}</td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Markup Percentage"
                                            :value="service.markup_percentage"
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
                services: this.services
            })
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        MainLayout,
    },
    props: {
        services: Object,
    },

    watch: {
        params: {
            handler() {
                this.$inertia.get(this.route('shipping-services.index'), this.params, { replace: true, preserveState: true });
            }
        }
    },
    methods: {
        updatePercentage(index, value) {
            this.form.services[index].markup_percentage = value;
        },
        submit() {
            this.form.post(this.route('shipping-services.update'))
        }
    }
}
</script>