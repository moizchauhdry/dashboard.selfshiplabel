<template>
  <nav v-if="links.length > 3" aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
    <ul class="pagination">
      <template v-for="(link, p) in links" :key="p">
        <li v-if="link.url === null" class="page-item disabled">
          <a class="page-link" href="javascript:void(0)" v-html="link.label">
          </a>
        </li>
        <li v-else class="page-item"><inertia-link  :href="link.url" class="page-link" :class="{ 'active': link.active }" @click="getResults(link.url)" v-html="link.label"></inertia-link></li>

      </template>
    </ul>
  </nav>
</template>

<script>
export default {
  props: {
    links: Array
  },
  methods: {
    getResults(url) {
      this.$parent.getResults(url);
    }
  }
}


</script>