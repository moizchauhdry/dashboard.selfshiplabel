<template>
    <MainLayout>
        <div class="card">
            <div class="card-header font-bold">General Settings</div>
            <div class="card-body">
                <form @submit.prevent="submit">
                    <table class="table table-sm table-striped table-bordered">
                        <tbody>
                            <tr>
                                <th colspan="3" class="text-center text-lg">Fee & Charges</th>
                            </tr>
                            <tr v-for="setting in form.settings" :key="setting.id">
                                <td class="text-uppercase">{{ setting.name }}</td>
                                <td>
                                    <input name="title" id="title" type="text" class="form-control" placeholder="Title"
                                        v-model="setting.value" required />
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-center text-lg">Shipping Services - Markup Percentage</th>
                            </tr>
                            <tr v-for="service in form.shipping_services" :key="service.id">
                                <td class="text-uppercase">{{ service.service_name }}</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Markup Percentage"
                                        v-model="service.markup_percentage" required />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <input type="submit" value="Update Settings" class="btn btn-success float-right" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
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
    data() {
        return {
            form: this.$inertia.form({
                settings: this.settings,
                shipping_services: this.shipping_services,
            })
        };
    },
    props: {
        auth: Object,
        settings: Object,
        shipping_services: Object,
    },
    methods: {
        submit() {
            this.form.post(this.route('settings.update'))
        }
    }
}
</script>

<style scoped>
.label {
    padding: 5px;
}
</style>
