<template>
    <div class="modal" name="confirm-modal">
        <div class="modal-header">
            <button  @click.prevent="close" class="right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <h1 class="text-center">{{ question }}</h1>
        </div>
        <div class="modal-footer">
            <button type="button" @click.prevent="confirm" class="btn btn-info">Ok</button>
            <button type="button" @click.prevent="close" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</template>
<script>
    export default {
        props : {
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