<template>
	<MainSideBar>
		<h2 class="font-weight-bold text-2 mb-0 mt-4 mt-md-0" style="font-size:24px !important;"> Search Results For "{{ keyword }}"</h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Symbol</th>
					<th>Name</th>
					<th>Currency</th>
					<th>Stock Exchange</th>
					<th>exchangeShortName</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(sc,index) in searchResults" :key="index">
					<td>{{ index + 1 }}</td>
					<td><inertia-link :href="route('market-earning-activity-details',{ symbol: sc.symbol })" >{{ sc.symbol }}</inertia-link></td>
					<td>{{ sc.name }}</td>
					<td>{{ sc.currency }}</td>
					<td>{{ sc.stockExchange }}</td>
					<td>{{ sc.exchangeShortName }}</td>
				</tr>
			</tbody>
		</table>
	</MainSideBar>
</template>
<script>
 	import MainSideBar from '@/Layouts/MainSideBar'
    export default {
        components: {
			MainSideBar,
        },   
         props: {
            searchResults: Array,
			keyword: String,
        },
        data() {
            return {
                 
            }
        },
        computed: {
            },
        methods: {
            

        },
		created() {

		}
    }
</script>