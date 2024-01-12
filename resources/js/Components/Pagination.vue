<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, p) in links" :key="p">
        <div v-if="link.url === null" class="mr-1 mb-1 px-3 py-2 text-sm leading-4 text-gray-400 border rounded"
             v-html="link.label"/>
        <inertia-link v-else  :href="link.url"
                      class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                      :class="{ 'bg-blue-700 text-white': link.active }" @click="getResults(link.url)" v-html="link.label"/>
      </template>
    </div>
  </div>
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