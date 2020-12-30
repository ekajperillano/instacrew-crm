<script>
    import { refreshDatatable } from 'utils/helpers'

    export default {
        mounted() {
            this.fields.map((field) => {
                if(field.clickable) {
                    this.clickableFields.push(field.title)
                }
            });
        },
        data () {
            const { append, page } = this.$route.params;
            const appendParams = (append) ? append : {
                search : null,
                filters: null
            };
            const initialPage = (page) ? page : 1;

            return {
                appendParams,
                initialPage,
                detailPageRoute: null,
                clickableFields: [],
                fields: [],
            }
        },
        methods: {
            onCellClicked(data, field) {
                if(
                    !this.detailPageRoute ||
                    !this.clickableFields.includes(field.title)
                ){
                    return false;
                }

                const paginationData = this.$refs[this.datatableRef].getPaginationData();
                const currentPage = (paginationData && paginationData.current_page) ? paginationData.current_page : this.initialPage;

                this.$router.push({
                    name: this.detailPageRoute,
                    params: {
                        id: data.id,
                        listParams: {
                            sort: this.sortOrder,
                            append: this.appendParams,
                            page: currentPage,
                        }
                    }
                });
            },
            getExportQuery() {
                const appendParams = this.appendParams;
                let sort = null
                if(this.sortOrder && this.sortOrder[0]) {
                    const {direction, field} = this.sortOrder[0];
                    if(direction && field) {
                        sort = `${field}|${direction}`;
                    }
                }

                let query = []
                if(appendParams.search) query.push(`search=${appendParams.search}`);
                if(appendParams.filters) query.push('filters=' + JSON.stringify(appendParams.filters));
                if(sort) query.push(`sort=${sort}`);
                if(this.fields && this.fields.length > 0) query.push('fields=' + JSON.stringify(this.fields));

                return query.join('&');
            },
        },
    }
</script>