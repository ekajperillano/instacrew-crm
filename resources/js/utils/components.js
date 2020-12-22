export function successToast(app, message = '', title = 'Success') {
    return app.$toast.success(message);
}

export function errorToast(app, errors, title='Something went wrong.') {

    if(typeof errors == 'object' ) {
        errors = _.values(errors);
    }

    const vNodesMsg = app.$createElement({
        props: {
            errors : Array,
            title: {
                default: null
            }
        },
        data () {
            return {
                builtErrors : [],
            }
        },
        mounted() {
            if(this.errors) {
                this.parseError(this.errors)
            }
        },
        methods : {
            parseError(error) {
                if(typeof error == 'object') {
                    for (let index = 0; index < error.length; index++) {
                        const element = error[index];
                        this.parseError(element)
                    }
                } else {
                    this.builtErrors.push(error);
                }
            }
        },
        template: `
            <div>
                <span v-if="title">{{ title }}</span>
                <ul>
                    <li class="error-message text-capitalize" v-for="(builtError, index) in builtErrors" :key="index">{{ builtError }}</li>
                </ul>
            </div>
        `
    }, {props: { errors, title } });

    return app.$toast.error(vNodesMsg);
}