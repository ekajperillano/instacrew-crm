<template>
    <b-input-group class="general-search">
        <b-input-group-prepend>
            <span class="input-group-text search-tools" id="search-icon" @click.prevent="doFilter">
                <b-icon icon="search"/>
            </span>
        </b-input-group-prepend>
        <b-form-input
            type="text" 
            class="form-control"  
            :placeholder="searchPlaceholder"
            aria-label="name, nickname, or email"
            aria-describedby="search-icon"
            v-model="filterText"  
            @keyup.enter="doFilter" 
        >
        </b-form-input>
        <b-input-group-append v-if="filterText">
            <span class="input-group-text  search-tools" id="eraser-icon" @click.prevent="resetFilter">
                <b-icon icon="x"/>
            </span>
        </b-input-group-append>
    </b-input-group>
</template>

<script>
    export default {
        data () {
            return {
                filterText: null
            }
        },
        props : {
            searchPlaceholder: String,
            parentClass : {
                type: String,
                default: ''
            },
        },
        methods: {
            doFilter () {
                this.$events.fire('search-list', this.filterText || null)
            },
            resetFilter () {
                this.filterText = null;
                this.$events.fire('search-list', null)
            }
        }
    }
</script>