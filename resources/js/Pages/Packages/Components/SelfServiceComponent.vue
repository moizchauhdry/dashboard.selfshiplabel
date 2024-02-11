<template>
    <div class="card mt-2">
        <div class="card-header">
            <h3 class="text-uppercase">Package Payments & Files</h3>
        </div>
        <div class="card-body">

            <template v-if="payments.length > 0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <tr>
                            <th colspan="6" class="bg-warning">
                                <h3>Package Payments</h3>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control" v-model="self_service_charge_form.amount">
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" @click="charge()">Charge
                                    Amount</button>
                            </td>
                        </tr>
                        <tr class="text-uppercase">
                            <th>Sr.no.</th>
                            <th>Invoice ID</th>
                            <th>Charged Amount</th>
                            <th>Charged Date</th>
                            <th></th>
                        </tr>
                        <tr v-for="payment, index in payments" :key="payment.id">
                            <td>{{ ++index }}</td>
                            <td>{{ payment.id }}</td>
                            <td>${{ payment.charged_amount }}</td>
                            <td>{{ payment.charged_at }}</td>
                            <td>
                                <template v-if="payment.charged_at">
                                    <a :href="route('payment.invoice', payment.id)" class="btn btn-primary btn-sm m-1"
                                        target="_blank"><i class="fa fa-print mr-1"></i>Print Invoice</a>
                                </template>
                            </td>
                        </tr>
                    </table>
                </div>
            </template>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <tr>
                        <th colspan="6" class="bg-warning text-white">
                            <h3>Package Files</h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <input type="file" class="form-control" @change="handleFileUpload" />
                        </td>
                        <td>
                            <button @click="uploadFile" class="btn btn-success btn-sm">Upload Image</button>
                        </td>
                    </tr>

                    <template v-if="package_files.length > 0">
                        <tr class="text-uppercase">
                            <th>Sr.no.</th>
                            <th>File ID</th>
                            <th>Path</th>
                        </tr>
                        <tr v-for="file, index in package_files" :key="file.id">
                            <td>{{ ++index }}</td>
                            <td>{{ file.id }}</td>
                            <td>
                                <a :href="'/storage/' + file.path" target="_blank" rel="noopener noreferrer">
                                    {{ file.path }}
                                </a>
                            </td>
                        </tr>
                    </template>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Self Service Component",
    props: {
        record: Object,
        payments: Array,
        package_files: Array,
    },
    data() {
        return {
            self_service_charge_form: this.$inertia.form({
                package_id: this.record.pkg_id,
                payment_module: 'package',
                amount: 0,
            }),
            self_service_file_form: {
                package_id: this.record.pkg_id,
                image: "",
            },
            selected_file: null,
        };
    },
    methods: {
        charge() {
            var result = window.confirm("Are you sure you want to charge?");
            if (result) {
                this.self_service_charge_form.post(this.route("payment.square-charge-later"));
                this.self_service_charge_form.amount = 0;
            }
        },
        handleFileUpload(event) {
            this.selected_file = event.target.files[0];
        },
        uploadFile() {
            let form_data = new FormData();
            form_data.append('file', this.selected_file);
            form_data.append('package_id', this.record.pkg_id);

            this.$inertia.post(route("packages.upload-file"), form_data).then(() => {
                // 
            });
        },
    },
};
</script>
