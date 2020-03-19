export default {
    methods: {
        checkPermission(permission){
            return this.roles.indexOf(permission) != -1
        },
        hasAccess(accesses){
            if(accesses == 'all') return true
            return accesses.some(this.checkPermission)
        },
        ifNoAccessRedirect(accesses){
            if(this.hasAccess(accesses) === false){
                return this.$router.push('/')
            }
        }
    },
    data: function(){
        return {
            roles: []
        }
    },
    mounted(){
        axios.get('/cp/admin/getRole')
            .then((response) => {
                this.roles = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
    }
};