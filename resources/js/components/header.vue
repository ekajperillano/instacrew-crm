<template>
    <div id="header"  :class="expanded == true ? 'expanded' : 'minimized'" >
        <div class="header">
            <!-- <span class="datetime">{{ datetime.date + ' ' + datetime.time }}</span> -->
            <div class="header-controls">
                <div class="text-nowrap">
                    <button  @click.prevent="$auth.logout()" class="btn btn-outline-info" >
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <span class="ml-2">LOGOUT</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        async mounted() {
            this.updateTime();
        },
        props: {
            expanded: {
                default: true,
            }
        },
        data() {
            return {
                datetime: {
                    date: '', 
                    time: ''
                },
                timerInterval: null,
            }
        },
        methods: {
            updateTime() {
                var week = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];
                if(this.timerInterval == null) {
                    this.timerInterval = setInterval(this.updateTime, 1000);
                }
                
                const zeroPadding = (num, digit) => {
                    var zero = '';
                    for(var i = 0; i < digit; i++) {
                        zero += '0';
                    }
                    return (zero + num).slice(-digit);
                }

                var cd = new Date();
                this.datetime.time = zeroPadding(cd.getHours(), 2) + ':' + zeroPadding(cd.getMinutes(), 2) + ':' + zeroPadding(cd.getSeconds(), 2);
                this.datetime.date =  zeroPadding(cd.getDate(), 2) + '-' + zeroPadding(cd.getMonth()+1, 2) + '-' + zeroPadding(cd.getFullYear(), 4) + ' ' + week[cd.getDay()] ;
            },
        },
        destroyed() {
            clearInterval(this.timerInterval);
        }
    }
</script>