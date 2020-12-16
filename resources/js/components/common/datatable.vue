<template>
	<div :ref="datatableRef" class="vld-parent">
		<div v-if="withControl" class="table-actions">
            <div class="mb-2 table-control row">
				 <div class="col">
					<button v-if="withAdvanceFilter" type="button" @click.prevent="advanceFilterFunc" class="btn btn-outline-secondary">
						<i class="fa  fa-filter" />
						<span>Advance Filter</span>
						<b-badge  v-if="filter_count"> {{filter_count}} </b-badge>
					</button>
					<small v-if="filter_count" class="ml-2 text-secondary"><a @click="$events.fire('clear-datatable-filter', false, null)" >clear filter</a></small>
                    <slot class="ml-2" name="additional-control-left"></slot>
                </div>
                <div class="col right">
                    <b-button-group class="text-nowrap">
                    	<slot name="additional-control"></slot>
                        <button v-if="withCreate" @click="createFunc" type="button" class="btn btn-outline-success">
                            <em class="fa fa-plus" />
                            <span>Create</span>
                        </button>
						<button v-if="withImport" @click="importFunc" type="button" class="btn btn-outline-success">
                            <em class="fa fa-upload" />
                            <span>Import</span>
                        </button>
                        <button v-if="withExport" @click="exportFunc" type="button" class="btn btn-outline-success">
                            <em class="fa fa-file-excel-o" />
                            <span>Export</span>
                        </button>
                        <button v-if="withUpload" @click="uploadFunc" type="button" class="btn btn-outline-success">
                            <em class="fa fa-upload" />
                            <span>Upload</span>
                        </button>
						<button v-if="withDelete" @click="deleteFunc" type="button" class="ml-2 btn btn-outline-danger">
							<em class="fa fa-trash-o" />
							<span>Delete</span>
						</button>
                    </b-button-group>
                </div>
	        </div>
		</div>
		<vuetable
			:ref="vuetableRef"
			:initial-page="initialPage"
			:apiUrl="apiUrl"
			:fields="fields"
			:pagination-path="paginationPath"
			:css="css.table"
			:sort-order="sortOrder"
			:multi-sort="multiSort"
			:append-params="appendParams"
			:http-options="httpOptions"
			@vuetable:cell-clicked="cellClickedFunc"
			@vuetable:pagination-data="onPaginationData"
			@vuetable:loaded="onDataLoaded"
			@vuetable:loading="onDataLoading"
			:detailRowComponent="detailRowComponent"
            :track-by="trackBy"
			:perPage="PAGINATION_LIMIT * 1"
	    >
		</vuetable>
	    <div class="vuetable-pagination">
			<vuetable-pagination-info
				ref="paginationInfo"
	        	info-class="pagination-info"
	      	/>
			<vuetable-pagination
				ref="pagination"
				:css="css.pagination"
				@vuetable-pagination:change-page="onChangePage"
	      	/>
	    </div>
    </div>
</template>
<script>
	import accounting from 'accounting'
	import moment from 'moment'
	import Vuetable from 'vuetable-2/src/components/Vuetable'
	import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
	import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

	import { DATE_FORMAT, PAGINATION_LIMIT } from 'configs/constants.js'
	import { 
		formatNumber,
		allCap,
		formatDate,
	} from 'utils/helpers';

	import _ from 'lodash';

	export default {
		mounted() {

		},
		components: {
		    Vuetable,
		    VuetablePagination,
		    VuetablePaginationInfo,
			FilterBar
		    // TableControl
	  	},
	  	props : {
			tableHeight : {
				type: String,
				default: '660px'
			},
			initialPage: {
				type: Number,
				default: 1
			},  
            trackBy: {
				type: String,
				default: 'id'
            },
	  		withAdvanceFilter: {
				type: Boolean,
				default: false
			},
			withCreate: {
				type: Boolean,
				default: true
			},
			withExport: {
				type: Boolean,
				default: true
			},
			withImport: {
	  			type: Boolean,
	  			default: false,
	  		},
			withDelete: {
				type: Boolean,
				default: true
            },
            withUpload: {
				type: Boolean,
				default: false
			},
	  		withControl: {
	  			type: Boolean,
	  			default: true,
	  		},
	  		datatableRef: {
	  			type: String,
	  			default: 'datatable'
	  		},
			appendParams: {
				type: Object,
				default: {}
			},
	  		withFilter : {
	  			type: Boolean,
	  			default: true
	  		},
	  		apiUrl: {
				type: String,
				required: true
			},
			fields: {
				type: Array,
				required: true
			},
			paginationPath: {
				type: String,
				default: ''
			},
			sortOrder: {
				type: Array,
				default: [
					{
						field: 'updated_at',
						sortField: 'updated_at',
						direction: 'asc'
					}
				]
			},
			multiSort: {
				type: Boolean,
				default: true
			},
			cellClickedFunc: {
				type: Function,
				default: () => {}
			},
			createFunc: {
				type: Function,
				default: () => {}
			},
			exportFunc: {
				type: Function,
				default: () => {}
			},
			importFunc: {
				type: Function,
				default: () => {}
			},
			deleteFunc: {
				type: Function,
				default: () => {}
            },
            uploadFunc: {
				type: Function,
				default: () => {}
            },
            advanceFilterFunc: {
				type: Function,
				default: () => {}
            },
			detailRowComponent: {
				type: String,
				required: false
			},
	  	},
	  	methods: {
	  		allCap,
			formatNumber,
			formatDate,
			getFromCollection(collection, searchProp) {
				if(collection) {
					return collection[searchProp];
				}
				return '';
			},
			getFromJSONCollection(json, searchProp) {
				if(json) {
					const parsed = JSON.parse(json);
					return parsed[searchProp] ? parsed[searchProp] : 'undefined';
				}

				return '';
			},
			formatBoolean(value, returnString = 'TRUE,FALSE') {
				const [trueString, falseString] = returnString.split(',');
				return value == 1 ? trueString : falseString;
			},
			getFromArrayCollection(collection, searchPropValue, searchProp, returnProp) {
				let values = [];
				collection.map(data => {
					if(data[searchProp] && data[searchProp] == searchPropValue) {
						values.push(data[returnProp]);
					}
				});

				return values.join(', ');
			},
			countCollection(collection) {
				if(collection) {
					return collection.length;
				}
				return '';
			},
			getPaginationData() {
				return this.paginationData;
			},
	  		onPaginationData (paginationData) {
				this.paginationData = paginationData;
	      		this.$refs.pagination.setPaginationData(paginationData)
	      		this.$refs.paginationInfo.setPaginationData(paginationData)
		    },
		    onChangePage (page) {
		      this.$refs[this.vuetableRef].changePage(page)
		    },
			onDataLoaded() {
				this.hideLoader();
			},
			onDataLoading() {
				this.showLoader();
			},
			refresh() {
				this.$refs[this.vuetableRef].refresh();
			},
			reload() {
				this.$refs[this.vuetableRef].reload();
			},
			normalizeFields() {	
				if(this.$refs[this.vuetableRef]) {
					this.$refs[this.vuetableRef].normalizeFields();
				}
			},
			showLoader() {
				this.loader = this.$loading.show({
					container: this.$refs[this.datatableRef]
				});
			},
			hideLoader() {
				if(this.loader) {
					this.loader.hide();
				}
			},
			toggleDetailRow(id) {
				if(id) {
					this.$refs[this.vuetableRef].toggleDetailRow(id)
				}
			},
	  	},
	  	data () {
	  		let httpOptions = {
	  			baseURL: this.$http.defaults.baseURL,
	            headers: {
	                ...this.$http.defaults.common,
	                'Authorization': 'Bearer ' +  this.$auth.token()
	            }
	  		}

	  		let vuetableRef = this.datatableRef + 'Vuetable';

	  		return {
				fieldLength: '20%',  
				PAGINATION_LIMIT,
				paginationData: null,
		  		vuetableRef,
	  			httpOptions,
	  			loader: null,
	  			css : {
		  			table: {
						tableClass: 'table table-bordered table-striped table-hover',
						ascendingIcon: 'fa fa-sort-alpha-asc',
						descendingIcon: 'fa fa-sort-alpha-desc'
		        	},
		        	pagination: {
						wrapperClass: 'pagination',
						activeClass: 'active',
						disabledClass: 'disabled',
						pageClass: 'page',
						linkClass: 'link',
						icons: {
							first: '',
							prev: '',
							next: '',
							last: '',
						},
					},
					icons: {
						first: 'glyphicon glyphicon-step-backward',
						prev: 'glyphicon glyphicon-chevron-left',
						next: 'glyphicon glyphicon-chevron-right',
						last: 'glyphicon glyphicon-step-forward',
					},
		  		}
	  		}
	  	},
	  	computed: {
	  		selections() {
				return (this.$refs[this.vuetableRef].selectedTo) ?  this.$refs[this.vuetableRef].selectedTo : [];
            },
	  	}
	}
</script>
