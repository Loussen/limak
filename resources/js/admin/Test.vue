<template>
    <div>Test</div>
</template>

<script>
    export default {
        name: "Test",
        mounted(){
            axios.post('/cp/accounts/cash', {
                invoices : [1,2,3]
            })
                .then(function (response) {
                    console.log(response);
                })
        },
    }
</script>
