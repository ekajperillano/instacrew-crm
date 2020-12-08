<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                Bienvenue
                <button  @click.prevent="$auth.logout()" class="btn btn-outline-info" >
                  <i class="fa fa-sign-out" aria-hidden="true"></i>
                  <span class="ml-2">LOGOUT</span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
            //
            }
        },
        components: {
            //
        }
    }
</script>