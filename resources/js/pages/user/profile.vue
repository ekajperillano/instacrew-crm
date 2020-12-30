<template>
    <fragment>
        <basic/>
        <password/>    
        <email-account/>
    </fragment>
</template>
<script>
    import Basic from 'components/user/profile-basic';
    import Password from 'components/user/profile-password';
    import EmailAccount from 'components/user/profile-email-account';

    export default {
        components: {
            Basic,
            Password,
            EmailAccount
        },
    }
</script>