<template>
  <MainLayout>
    <div class="card mt-4">
      <div class="card-body stock-subscription-form">
        <form @submit.prevent="submit">

          <h5>Edit Page - {{ form.meta_title }}</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="meta_title">Page Title</label>
                <input name="meta_title" id="title" type="text" class="form-control" placeholder="Enter Title Name" v-model="form.title" required/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input name="meta_title" id="meta_title" type="text" class="form-control" placeholder="Meta Title Name" v-model="form.meta_title" required/>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="meta_keywords">Keywords</label> <small>Please use "," as separators to define your "Keyword".</small>
                <input name="meta_keywords" id="meta_keywords" type="text" class="form-control" placeholder="Enter Meta Keywords" v-model="form.meta_keywords" required>
              </div>
            </div>
            <div class="col-md-12 ">
              <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea v-model="form.meta_description" class="form-control" name="meta_description" id="meta_description" rows="4" required></textarea>
              </div>
            </div>
            <div class="col-md-12 ">
              <div class="form-group">
                <label for="description">Page Description</label>
                <textarea v-model="form.description" class="form-control" name="description" id="description" rows="4" required></textarea>
              </div>
            </div>
          </div>
          <div class="order-button text-center">
            <input type="submit" value="Update" class="btn btn-danger"/>
          </div>
        </form>
      </div>
    </div>

  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import Editor from '@tinymce/tinymce-vue'

export default {
  components: {
    MainLayout,
    Editor
  },
  props: {
    errors: Object,
    cms: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        title: this.cms.title,
        meta_title: this.cms.meta_title,
        meta_description: this.cms.meta_description,
        meta_keywords: this.cms.meta_keywords,
        description: this.cms.description,
        id: this.cms.id
      }),
      currentYear: new Date().getFullYear(),
      Years: [],
    }
  },
  computed: {
    years() {
      const year = new Date().getFullYear()
      return Array.from({length: year - 2000}, (value, index) => year + index)
    }
  },
  methods: {
    submit() {
      this.form.post(this.route('page_update'))
      // this.form.post(this.route('Createlead'), {
      //     onFinish: () => this.form.reset(),
      // })
    },
    inArray: function (needle, haystack) {
      console.log(needle + ' -- ' + haystack);
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
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
    save_image_upload_handler(blobInfo, success, failure, progress) {
      var xhr, formData;
      xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open('POST', '/api/upload_image');
      xhr.upload.onprogress = function (e) {
        progress(e.loaded / e.total * 100);
      };
      xhr.onload = function () {
        var json;
        if (xhr.status === 403) {
          failure('HTTP Error: ' + xhr.status, {remove: true});
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
