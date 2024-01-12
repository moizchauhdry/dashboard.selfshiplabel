<template>
  <MainLayout>
    <div class="card mt-4 pr-2">
      <div class="card-header">
        Services
        <div class="float-right">

        </div>
      </div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <table class="table table-responsive">
            <thead>
            <tr>
              <th>Services</th>
              <th>Charges</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in form.services" :key="item.id">
              <td style="width:40%" v-html="item.name"></td>
              <td style="width:60%"><textarea v-model="item.description" name="services[]" style="width: 100%" :id="item.id" rows="5"></textarea></td>
            </tr>
            </tbody>
          </table>
          <input type="submit" value="Update" class="btn btn-primary" />
        </form>
      </div>
      <div class="card-footer">

      </div>
    </div>
  </MainLayout>
</template>

<script>
import MainLayout from '@/Layouts/Main'
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeLabel from '@/Components/Label'

export default {
  components: {
    BreezeAuthenticatedLayout,
    MainLayout,
    BreezeLabel
  },
  data() {
    return {
      form: this.$inertia.form({
        services: this.services
      })
    };
  },
  props: {
    services: Object
  },
  methods: {
    submit() {
      this.form.post(this.route('service-page.update'))
    }
  }

}
</script>

<style scoped>

</style>