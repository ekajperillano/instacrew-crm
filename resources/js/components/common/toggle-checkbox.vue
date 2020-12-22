<template>
    <div 
        class="checkbox-toggle" 
        role="checkbox" 
        @keydown="toggle" 
        @click.stop="toggle" 
        tabindex="0" 
        :aria-checked="toggled"
    >
        <div class="checkbox-slide"  :class="classes">
            <div class="checkbox-switch" :class="classes"></div>
        </div>
        <div v-show="showLabels" class="checkbox-label" v-html="label"></div>
    </div>
</template>
<script>
export default {
    mounted() {
        this.toggled = this.value;
    },
    computed: {
        classes: function() {
            return {
                checked: this.toggled,
                unchecked: !this.toggled,
                disabled: this.disabled
            };
        },

        label: function() {
            return this.toggled && this.showLabels
                ? this.labelChecked
                : this.labelUnchecked;
        }
    },

    data() {
        return {
            toggled: false,
        };
    },

    methods: {
        toggle: function(e) {
            
            if(this.disabled) {
                return;
            }

            if (e.keyCode === 9) {
                e.stop();
            }

            this.toggled = ! this.toggled;

            this.$emit("input", this.toggled);
        }
    },

    props: {
        disabled: {
            type: Boolean ,
            default: false
        },

        value: {
            type: Boolean | Number,
            default: false
        },

        showLabels: {
            type: Boolean,
            default: false
        },

        labelChecked: {
            type: String,
            default: ""
        },

        labelUnchecked: {
            type: String,
            default: ""
        },

        emitData: {
            type: String | Object,
            default: null
        }
    },
    watch: {
        'value'(val) {
            this.toggled = val;
        }
    }
}
</script>