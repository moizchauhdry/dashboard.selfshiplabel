<template>
	<MainLayout>
        <div class="row mb-5">
            <div class="col-md-9">
                <div class="card">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="card-header">Customs Form</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h2>Ship From</h2>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_from_company" class="form-control" placeholder="Company">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_from_person" class="form-control" placeholder="Person">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_from_contact" class="form-control" placeholder="Phone">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" v-model="form.ship_from_email" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_from_address" class="form-control" placeholder="Address">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_from_city" class="form-control" placeholder="City">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_from_state" class="form-control" placeholder="State">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_from_zipcode" class="form-control" placeholder="Zipcode">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_from_country" class="form-control" placeholder="Country">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_from_incoterms" class="form-control" placeholder="Incoterms">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h2>Ship To</h2>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_company" class="form-control" placeholder="Company">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_person" class="form-control" placeholder="Person">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_contact" class="form-control" placeholder="Phone">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" v-model="form.ship_to_email" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_address1" class="form-control" placeholder="Address 1">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_address2" class="form-control" placeholder="Address 2">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_address3" class="form-control" placeholder="Address 3">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.ship_to_tax_id" class="form-control" placeholder="Tax ID">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_to_city" class="form-control" placeholder="City">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_to_state" class="form-control" placeholder="State">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_to_zipcode" class="form-control" placeholder="Zipcode">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" v-model="form.ship_to_country" class="form-control" placeholder="Country">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h2>Package Information</h2>
                                    <!-- <div class="form-group">
                                        <input type="text" v-model="form.tracking_number" class="form-control" placeholder="Tracking Number">
                                    </div> -->
                                    <div class="form-group">
                                        <input type="date" v-model="form.package_date" class="form-control" placeholder="Package Date">
                                    </div>
                                    <!-- <div class="form-group">
                                        <input type="text" v-model="form.package_no" class="form-control" placeholder="Package ID">
                                    </div> -->
                                    <div class="form-group">
                                        <input type="text" v-model="form.sold_to" class="form-control" placeholder="Sold To">
                                    </div>
                                   <div class="form-group">
                                        <select class="form-control custom-select" v-model="form.package_type" >
                                            <option value="" selected disabled>Package Type</option>
                                            <option value="Commercial">Commercial</option>
                                            <option value="Gift">Gift</option>
                                            <option value="Sample">Sample</option>
                                            <option value="Personal - Not for sale">Personal - Not for sale</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="form.package_weight" class="form-control" placeholder="Package Weight">
                                    </div>
                                    <div class="form-group">
                                        <textarea v-model="form.special_instructions" rows="5" placeholder="Special Instructions"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div v-for="(item, index) in form.package_items" :key="item.id" class="row">
                                        <div class="col-md-3 form-group">
                                            <label for=""><b>Description *</b></label>
                                            <input
                                                v-model="item.desc"
                                                type="text"
                                                class="form-control"
                                                placeholder="Description"
                                            />
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for=""><b>HS Code</b></label>
                                            <input
                                                v-model="item.code"
                                                name="hs_code"
                                                id="hs_code"
                                                type="text"
                                                class="form-control"
                                                placeholder="HS Code"
                                            />
                                        </div>

                                        <div class="col-md-1 form-group">
                                            <label for=""><b>Qty *</b></label>
                                            <input
                                                v-model="item.qty"
                                                @keyup="calculateShippingTotal"
                                                v-on:change="calculateShippingTotal"
                                                name="quantity"
                                                min="1"
                                                step="1"
                                                id="quantity"
                                                type="number"
                                                class="form-control"
                                                placeholder="Qty"
                                            />
                                        </div>

                                        <div class="col-md-1 form-group">
                                            <label for=""><b>Price *</b></label>
                                            <input
                                                v-model="item.price"
                                                @keyup="calculateShippingTotal"
                                                v-on:change="calculateShippingTotal"
                                                min="0"
                                                type="number"
                                                step="0.01"
                                                class="form-control"
                                                placeholder="Price"
                                            />
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for=""><b>Origin Country *</b></label>
                                            <select
                                                name="origin_country"
                                                class="form-control custom-select"
                                                v-model="item.origin"
                                            >
                                                <option selected value="">Select</option>
                                                <template v-for="country in countries" :key="country.id">
                                                    <option :value="country.id">
                                                        {{ country.name }}
                                                    </option>
                                                </template>
                                            </select>
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for=""><b>Batteries</b></label>
                                            <select
                                                name="batteries"
                                                class="form-control custom-select"
                                                v-model="item.battery"
                                            >
                                                <option selected value="">Select</option>
                                                <option value="0">No Battery</option>
                                                <option value="1">
                                                    Simple Batteries (Shipped on on Fedex)
                                                </option>
                                                <option value="2">
                                                    Batteries Packaed with Equipment
                                                </option>
                                                <option value="3">
                                                    Batteries Contained in Equipment
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-1 form-group" v-show="index != 0">
                                            <label for=""></label>
                                            <button
                                                v-on:click="removeItem(index)"
                                                class="btn btn-link"
                                            >
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="col-md form-group">
                                                <input
                                                    v-model="form.shipping_total"
                                                    readonly
                                                    name="shipping_total"
                                                    id="shipping_total"
                                                    type="text"
                                                    class="form-control"
                                                    required
                                                />
                                            </div>
                                        </div>

                                        <div
                                            class="col-md-2 offset-md-3"
                                            style="padding-right: 0px"
                                        >
                                            <a
                                                v-on:click="addItem"
                                                class="btn btn-primary"
                                                style="float: right"
                                            >
                                                <i class="fas fa-plus mr-1"></i> Add Item
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Save & Update</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <breeze-validation-errors class="mb-4 text-lg" />
            </div>
        </div>
	</MainLayout>
</template>

<script>
	import MainLayout from "@/Layouts/Main";
	import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
	import BreezeLabel from "@/Components/Label";
	import BreezeValidationErrors from "@/Components/ValidationErrors";

	export default {
		components: {
			BreezeAuthenticatedLayout,
			MainLayout,
			BreezeLabel,
			BreezeValidationErrors,
		},
		props: {
			custom: Object,
			countries: Object,
		},
		data() {
			return {
				form: this.$inertia.form({
					ship_from_company: '',
					ship_from_person: '',
					ship_from_contact: '',
					ship_from_email: '',
					ship_from_address: '',
					ship_from_city: '',
					ship_from_state: '',
					ship_from_zipcode: '',
					ship_from_country: '',
					ship_from_incoterms: 'DDU/DAP',
					// tracking_number: '',
					package_date: '',
					// package_no: '',
					package_type: '',
					sold_to: 'Same as consignee',
					ship_to_company: '',
					ship_to_person: '',
					ship_to_contact: '',
					ship_to_email: '',
					ship_to_address1: '',
					ship_to_address2: '',
					ship_to_address3: '',
					ship_to_tax_id: '',
					ship_to_city: '',
					ship_to_state: '',
					ship_to_zipcode: '',
					ship_to_country: '',
					package_weight: '',
                    special_instructions: '',
                    shipping_total: '',
                    package_items: [],
				}),
			};
		},
		methods: {
            submit() {
				this.form.post(this.route("custom-form.store"));
            },
            addItem() {
				this.form.package_items.push({
                    desc: "",
					code: "",
					qty: 1,
					price: 0,
					origin: "",
					battery: "",
				});
			},
			removeItem(index) {
				this.form.package_items.splice(index, 1);
				this.calculateShippingTotal();
            },
            calculateShippingTotal() {
				let final_amount = 0;
				this.form.package_items.forEach((item, index) => {
					final_amount =
						final_amount +
						parseFloat(item.price) * parseInt(item.qty);
				});

				if (final_amount > 0) {
					this.form.shipping_total = parseFloat(final_amount).toFixed(2);
				} else {
					this.form.shipping_total = "";
				}
			},
		},
		mounted() {
			this.addItem();
		},
	};
</script>
