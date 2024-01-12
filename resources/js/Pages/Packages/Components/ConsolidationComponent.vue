<template>
    <div class="col-md-12" v-if="$page.props.auth.user.type == 'admin' && packag.pkg_type == 'consolidation'">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="text-uppercase">{{ packag.pkg_type }} Package Dimension</h3>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitConsolidateForm">
                    <div class="row" v-for="(item, index) in consolidation_form.package_boxes" :key="item.id">
                        <div class="col-md-2 form-group">
                            <breeze-label for="warehouse_id" value="Weight Unit" />
                            <select class="form-select" readonly :disabled="1">
                                <option value="lb">Lb / Inch</option>
                                <option value="kg">Kg / Cm</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <breeze-label for="weight" value="Weight" />
                            <input v-model="item.weight" min="1" name="weight" id="weight" type="number"
                                class="form-control" placeholder="Weight" :step="0.01" required />
                        </div>
                        <div class="col-md-2 form-group">
                            <breeze-label for="length" value="Length" />
                            <input v-model="item.length" min="1" name="length" id="length" type="number"
                                class="form-control" placeholder="Length" :step="0.01" required />
                        </div>
                        <div class="col-md-2 form-group">
                            <breeze-label for="height" value="Height" />
                            <input v-model="item.height" min="1" name="height" id="height" type="number"
                                class="form-control" placeholder="Height" :step="0.01" required />
                        </div>
                        <div class="col-md-2 form-group">
                            <breeze-label for="width" value="Width" />
                            <input v-model="item.width" min="1" name="width" id="width" type="number" class="form-control"
                                placeholder="Width" :step="0.01" required />
                        </div>
                        <div class="col-md-2 form-group">
                            <button v-on:click="removeItem(index)" class="btn btn-danger mr-1" v-show="index != 0">
                                <span><i class="fa fa-trash"></i></span>
                            </button>
                            <button type="button" v-on:click="addItem" class="btn btn-primary">
                                <span><i class="fa fa-plus"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="Consolidate Package" class="btn btn-success float-left" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Consolidation Component",
    props: {
        packag: Object,
        package_boxes: Object,
    },
    data() {
        return {
            consolidation_form: this.$inertia.form({
                package_id: this.packag.id,
                package_boxes: this.package_boxes,
            }),
        }
    },
    methods: {
        submitConsolidateForm() {
            this.consolidation_form.post(this.route('packages.consolidate'));
        },
        addItem() {
            this.consolidation_form.package_boxes.push(
                {
                    dim_unit: "in",
                    weight_unit: "lb",
                    weight: "",
                    length: "",
                    height: "",
                    width: "",
                }
            )
        },
        removeItem(index) {
            this.consolidation_form.package_boxes.splice(index, 1);
        },
    },
    mounted() {
        if (this.package_boxes.length == 0) {
            this.addItem();
        }
    },
}
</script>