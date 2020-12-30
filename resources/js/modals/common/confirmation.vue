<template>
    <div name="confirm-modal" class="advance-filter-wrapper">
        <div class="card card-default" :class="className">
            <div class="card-header"> 
                <span>{{ title }}</span>
                <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="card-body">
                <h1>{{ question }}</h1>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <button type="button" @click.prevent="confirm" class="btn btn-outline-success">Ok</button>
                    <button type="button" @click.prevent="close" class="btn btn-outline-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props : {
            title : {
                type: String,
                required: true
            },
            question : {
                type: String,
                required: true
            },
            modal_data: {
                required: false
            },
            className : {
                type: String,
                default: ''
            },
            confirmEvent :{
                type: String,
                default: 'confirm-modal'
            }
        },
        methods : {
            close() {
                this.$emit('close');
            },
            confirm() {
                this.$emit('close');
                this.$events.fire(this.confirmEvent, this.modal_data);
            },
        }
    }
</script>