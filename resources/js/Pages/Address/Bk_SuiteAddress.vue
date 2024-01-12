<template>
    <MainLayout>


		<div class="card mt-4">
                 
                 <div class="card-header">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
                        Using your Addresses
                    </h2>
                </div>

				<div class="card-body">
                        
                        <div class="row" style="margin-bottom:30px;">
                            <div class="col-md-12">         
                                <p>
                                    How to use your addresses when purchasing from online merchants.
                                    <ul>
                                        <li>1: Purchase form murchents</li>
                                        <li>2: Use one of these addresses</li>
                                        <li>3: We will deliver items to your address outside US. (Listed in address book) </li>
                                    </ul>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            
                            <p class="alret alert-success" v-show="addressCopied"> Address copied to clipboard!</p>

                            <div v-for="(address,index) in addresses" :key="index" class="col-md-4"> 
                                <h2> {{ address.name }} </h2>
                                <div class="copy-addresss" style="border: 1px solid black; text-align:center; cursor: pointer;">
                                    <p v-bind:id="getId(index)" v-html="address.address" v-on:click="copyData($event)">                                        
                                    </p>
                                </div>
                            </div>

                        </div>
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
            MainLayout
        },
        data() {
            return {
                addressCopied:false,
            };
        },
        props: {
            auth: Object,            
            addresses:Object
        },
        methods : {
            
            getId(index){
                return 'copy-address-'+index;
            },

            copyData(event) {

                var containerid = event.currentTarget.id;
                 
                if (window.getSelection) {
                        if (window.getSelection().empty) { // Chrome
                            window.getSelection().empty();
                        } else if (window.getSelection().removeAllRanges) { // Firefox
                            window.getSelection().removeAllRanges();
                        }
                } else if (document.selection) { // IE?
                    document.selection.empty();
                }

                if (document.selection) {
                    var range = document.body.createTextRange();
                    range.moveToElementText(document.getElementById(containerid));
                    range.select().createTextRange();
                    document.execCommand("copy");
                } else if (window.getSelection) {
                    var range = document.createRange();
                    range.selectNode(document.getElementById(containerid));
                    window.getSelection().addRange(range);
                    document.execCommand("copy");
                }

                this.addressCopied =true;
                
            },           
        }
    }
</script>
