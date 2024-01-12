<template>
    <div v-if="$page.props.auth.user.type == 'admin' && order.payment_status != 'Paid'">

        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#payment_modal"
            v-on:click="show()">
            <i class="fa fa-plus mr-1"></i>Order Payment
        </button>

        <div class="modal fade" id="payment_modal" tabindex="-1" aria-labelledby="payment_modal_label" aria-hidden="true">
            <div class="modal-dialog border">
                <form @submit.prevent="add_payment">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payment_modal_label">
                                Order Payment
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <breeze-validation-errors class="mb-4" />
                                </div>

                                <div class="col-md-5 form-group">
                                    <select class="form-control custom-select" v-model="payment_form.payment_type">
                                        <option value="">Choose Method</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Authorize.net">Authorize.net</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-7 form-group">
                                    <input type="text" class="form-control" v-model="payment_form.transaction_id"
                                        placeholder="Transaction ID" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" v-on:click="close">
                                Close
                            </button>
                            <button type="submit" class="btn btn-success">Add Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
    name: "Payment Component",
    components: {
        BreezeValidationErrors,
    },
    props: {
        order: Object,
    },
    data() {
        return {
            payment_form: this.$inertia.form({
                payment_module_id: this.order.id,
                payment_module: "order",
                transaction_id: "",
                payment_type: "",
            }),
        };
    },
    methods: {
        show() {
            var modal = document.getElementById("payment_modal");
            modal.classList.add("show");
            $("#payment_modal").show();
        },
        close() {
            var modal = document.getElementById("payment_modal");
            modal.style.display = "none";
        },
        add_payment() {
            this.payment_form.post(this.route("payment.add"))
                .then(response => {
                    this.close();
                })
                .catch(error => {
                    //
                })
                .finally(() => {
                    //
                });
        },
    },
};
</script>
