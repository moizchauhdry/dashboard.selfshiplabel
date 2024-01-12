<template>
    <MainLayout>
		<div class="card mt-4">
            <div class="card-body">
                <breeze-validation-errors class="mb-4" />
                <flash-messages ></flash-messages>

            <form @submit.prevent="submit">
              <div class="order-form">
                  <div class="row">
                    <h2>Edit User `{{ form.name }}`</h2>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name</label>
                        <input name="name" id="name" type="text" class="form-control" placeholder="Full Name" v-model="form.name" required />
                      </div>

                      <div class="form-group">
                        <label>Address</label>
                        <input name="address1" id="address1" type="text" class="form-control" placeholder="Address" v-model="form.address1" required />
                      </div>

                      <div class="form-group">
                        <label>City</label>
                        <input name="city" id="city" type="text" class="form-control" placeholder="city" v-model="form.city" required />
                      </div>

                      <div class="form-group">
                        <label>State</label>
                        <input name="state" id="state" type="text" class="form-control" placeholder="state" v-model="form.state" required />
                      </div>

                      <div class="form-group">
                        <label>Country</label>
                        <input name="country" id="country" type="text" class="form-control" placeholder="country" v-model="form.country" />
                      </div>

                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>postal Code</label>
                        <input name="postal_code" id="postal_code" type="text" class="form-control" placeholder="Postal code" v-model="form.postal_code" />
                      </div>

                      <div class="form-group">
                        <label>Phone no</label>
                        <input name="phone_no" id="phone_no" type="text" class="form-control" placeholder="Phone no" v-model="form.phone_no" />
                      </div>

                      <div class="form-group">
                        <label>Email</label>
                        <input name="email" id="email" type="text" class="form-control" placeholder="email" autocomplete="off" v-model="form.email" required />
                      </div>

                      <div class="form-group">
                        <label>User Type</label>
                        <select name="type" id="type" class="form-control" v-model="form.type">
                            <option value="customer" selected>Customer</option>
                            <option value="admin" v-if="$page.props.auth.user.type == 'admin'">Admin User</option>
                            <option value="manager"  v-if="$page.props.auth.user.type == 'admin'">Warehouse Manager</option>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="order-button">
                <input type="submit" value="Save & Update" class="btn btn-danger" />
              </div>
          </form>
        </div>
      </div>
    </MainLayout>
</template>

<script>
    import MainLayout from '@/Layouts/Main'
    import Editor from '@tinymce/tinymce-vue'
    import BreezeValidationErrors from '@/Components/ValidationErrors'
    export default {
        components: {
            MainLayout,
            Editor,
            BreezeValidationErrors
        },
         props: {
            errors: Object,
            categories:Object,
            user:Object,
        },
        data() {
            return {
                form: this.$inertia.form({
                    id: this.user.id,
                    name: this.user.name,
                    address1: this.user.address1,
                    city: this.user.city,
                    state: this.user.state,
                    country: this.user.country,
                    postal_code: this.user.postal_code,
                    phone_no: this.user.phone_no,
                    email: this.user.email,
                    type: this.user.type,
                }),
                currentYear: new Date().getFullYear(),
                Years : [],
            }
        },
        computed : {
            years () {
              const year = new Date().getFullYear()
              return Array.from({length: year - 2000}, (value, index) => year + index)
            }
          },
        methods: {
            submit() {
                this.form.post(this.route('update-users'))
                // this.form.post(this.route('Createlead'), {
                //     onFinish: () => this.form.reset(),
                // })
            },
            updatePhotoPreview() {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(this.$refs.photo.files[0]);
            },
            storePhoto() {
                if (this.$refs.photo) {
                    this.form.post_image = this.$refs.photo.files[0]
                }
                this.form.post(route('photo.store'), {
                    preserveScroll: true
                });
            },     
            save_image_upload_handler (blobInfo, success, failure, progress) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/api/upload_image');
                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };
                xhr.onload = function() {
                    var json;
                    if (xhr.status === 403) {
                        failure('HTTP Error: ' + xhr.status, { remove: true });
                        return;
                    }
                    if (xhr.status < 200 || xhr.status >= 300) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };

                xhr.onerror = function () {
                    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            }

        }
    }
</script>
