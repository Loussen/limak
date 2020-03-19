<template>
    <div id="app">
        <router-view :roles="roles" ></router-view>
    </div>
</template>

<script>
export default {
  name: 'app',
  data () {
    return {
        roles: []
    }
  },
    mounted(){
        axios.get('/admin/depot/check/role').then(data => {
            this.roles = data.data;
        })
    },
    methods:{
        check(){
            console.log('adasd dajndja');
        }
    }
}
</script>

<style>

</style>
