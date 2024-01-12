<template>
  <MainLayout>
    <div class="row mt-4">
      <div class="col-md-7 offset-md-1">
        <div class="card mt-4">
          <div class="card-header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight form-title">
              Using your Addresses
            </h2>
          </div>
          <div class="card-body">
            <p>
              How to use your addresses when purchasing from online merchants.
            <ul>
              <li>1: Purchase form merchants</li>
              <li>2: Use one of these addresses</li>
              <li>3: We will deliver items to your address outside US. (Listed in address book)</li>
            </ul>
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4 offset-md-1" v-for="(address,index) in addresses" v-bind:key="address.id">
        <div class="card">
          <div class="card-header">
            {{ address.name }} Mailing Address
          </div>
          <div class="card-body">
            <div class="address-card mb-4">
              <p><strong>Full Name:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ siuteNum }} – {{ $page.props.auth.user.name }}</span> <span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>Street Address:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ address.address }}</span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>City:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ address.city }}</span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>Suite #:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)"> {{ siuteNum }} </span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>State:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ address.state }} </span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>Zip code:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ address.zip }} </span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
              <p><strong>Phone Number:</strong><span class="copy-item" :id="'copy-item-'+count" v-on:click="copyContent($event)">{{ address.phone }}</span><span v-if="copied_id=='copy-item-'+count++" style="color:green">Copied!</span></p>
            </div>
            <br/><br/>
            <a href="javascript:void(0)" class="btn btn-primary" @click="copyToClipBoard(address,index)">
              <span v-if="!address.clicked">Click Here To Copy</span>
              <span v-if="address.clicked">Copied</span>
            </a>
          </div>
        </div>
      </div>
    </div>


  </MainLayout>
</template>
<style scoped>
.card {
  margin-top: 25px;
}

.card-body {
  padding: 0.8rem;
}

.card-body p strong {
  color: #212529;
  margin-right: 12px;
}

.address-card {
  height: 190px;
}

.copy-item:hover {
  cursor: pointer;
  border: 1px dotted green;
}

.row {
  margin-right: 0px !important;
}
</style>
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
      count:0,
      copied1: false,
      copied2: false,
    }
  },
  props: {
    auth: Object,
    addresses: Object
  },
  computed: {
    siuteNum() {
      return 4000 + this.$page.props.auth.user.id;
    },
  },
  methods: {
    copyToClipBoard(address,index) {
      var text = address.address +', '+ address.city +', ' + address.state +', ' + address.zip;
      navigator.clipboard.writeText(text).then(function () {
      }, function (err) {
        console.error('Async: Could not copy text: ', err);
      });
    },
    copyContent(event) {
      var text = event.target.innerText;
      this.copied_id = event.target.id;
      navigator.clipboard.writeText(text).then(function () {
      }, function (err) {
        console.error('Async: Could not copy text: ', err);
      });
    },

  },
  mounted() {
  }
}
</script>
