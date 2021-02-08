<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Notes</h4>
            <div class="right">
                <b-button :disabled="client.active != 1"  @click="openCreateUpdateModal()" variant="primary">Add New Note</b-button>
            </div>
        </b-card-header>
        <b-card-body> 
            <div v-if="this.client.notes && this.client.notes.length > 0" class="card-body border-top">
                <div class="note-list">
                    <div  class="card note-record mb-1" v-for="note in notes" :key="note.id">
                        <div class="note-body p-2 text-left"> {{note.note}} </div>
                        <div class="note-footer px-3 py-1">
                            <div class="float-right">
                                <small> {{ note.user.name }} </small>
                                <small> {{ note.au_created_at }} </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';


    import AddClientNote from 'modals/note/create';

    export default {
        mixins:[
            RequestMixin,
        ],
        mounted() {
            const { notes } = this.client;
            if(notes && notes.length > 0) {
                this.notes = notes;
            }
        },
        props: {
            client: {
                required: false,
                default: null
            }
        },
        data() {
            return {
                pageReference: 'clientDetailNotes',
                notes: [],
            }
        },
        methods: {
            openCreateUpdateModal() {
                this.$modal.show(AddClientNote, {
                    client_id: this.client.id,
                    confirmEvent: 'create-client-note',
                }, {
                    height: "auto"
                });
            },
        },
        events: {
            'create-client-note' (note) {
                if(note) {
                    this.notes.push(note)
                }
            },
        }
    }
</script>