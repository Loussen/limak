<template>
    <div>
        <button type="button" class="pull-right" data-toggle="tooltip" data-placement="top" title="" :data-original-title="ExWindow ? ExWindow.translator('panel-errors.last_30_days_text') : ''">
            <img src="/front/new/img/question-mark.png" alt="question-mark" class="img-responsive">
        </button>
        <div class="row">
            <div class="col-xs-6">
                <h4>{{ExWindow ? ExWindow.translator('panel-errors.last_30_days') : '' }}</h4>
                <p>{{User.last30days}}<sup>$</sup></p>
            </div>
            <div class="col-xs-6 relative">
                <canvas id="canvas" width="90" height="90"></canvas>
                <span id="procent">{{ User.last30days &&parseInt(User.last30days/10)}}</span>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "last30days2",
        mounted(){
            this.ExWindow = window;
            this.getUserData();
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
            }
        },
        methods: {
            async getUserData() {
                const test  = await this.requestUserData();
                this.User = test.data;
                this.$emit('User', this.User);
            },
            requestUserData() {
                var path = '/user-panel/get-user-data';
                return  axios.get(path);

            },
        }
    }
</script>

<style scoped>

</style>
