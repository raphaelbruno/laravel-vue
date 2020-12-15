<template>
	<div class="float-right d-flex align-items-center">
		<div>{{ trans('crud.total') }}: {{ total }}</div>
		<div v-if="lastPage > 1" class="ml-2">
			<nav>
			<ul class="pagination pagination-sm m-0">
				<li :class="`page-item ${link.active ? 'active' : ''}`" v-for="(link, key) in processedLinks" :key="key">
					<a v-if="link.url" :href="ajax == undefined ? link.url : 'javascript:void(0);'" v-on:click="navigate(link)" class="page-link">{{ link.label }}</a>
					<span v-else class="page-link">{{ link.label }}</span>
				</li>
			</ul>
			</nav>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['data', 'ajax'],
		data(){
			return {
				currentPage: 1,
				lastPage: 1,
				total: 0,
				links: [],
			};
		},
		mounted(){
			let data = JSON.parse(this.data);
			this.currentPage = data.current_page;
			this.lastPage = data.last_page;
			this.total = data.total;
			this.links = data.links;
		},
		methods: {
			navigate(link){
				this.$emit('paginate', {
					...link,
					currentPage: this.currentPage,
					lastPage: this.lastPage,
					total: this.total,
				});
			}
		},
		computed: {
			processedLinks(){
				if(this.links.length < 1) return [];

				let first = this.links[0];
				first.label = '‹';
				first.page = first.url ? this.currentPage - 1 : null;
				
				let last = this.links[(this.links.length-1)];
				last.label = '›';
				last.page = last.url ? this.currentPage + 1 : null;

				for(let i = 1; i < this.links.length-1; i++)
					this.links[i].page = parseInt(this.links[i].label);
				
				return this.links;
			}
		}
	}
</script>