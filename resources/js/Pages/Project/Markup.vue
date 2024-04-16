<template>
    <MainLayout>
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h5><b>Manage Projects - Markup</b></h5>
                </div>
            </div>
            <div class="card-body">
                <flash-messages></flash-messages>

                <form @submit.prevent="submit">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th colspan="3">PROJECT #{{ project_id }}</th>
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
                                <tr v-for="(service, index) in shipping_services" :key="service.id">
                                    <td>{{ ++index }}</td>
                                    <td>{{ service.service_name }}</td>
                                    <td>{{ service.service_code }}</td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Markup Percentage"
                                            v-model="service.markup_percentage" required />
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
                shipping_services: this.shipping_services,
                project_id: this.project_id,
            })
        }
    },
    components: {
        BreezeAuthenticatedLayout,
        MainLayout,
    },
    props: {
        shipping_services: Object,
        project_id: Object,
    },
    methods: {
        submit() {
            this.form.post(this.route('project.markup-update'))
        }
    }
}
</script>