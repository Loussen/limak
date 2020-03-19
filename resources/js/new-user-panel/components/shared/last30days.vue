<template>
    <div>
        <button type="button" class="pull-right" data-toggle="tooltip" data-placement="top"
                title="" :data-original-title="ExWindow ? ExWindow.translator('panel-errors.last_30_days_text') : ''">
            <img src="/front/new/img/question-mark.png" alt="question-mark" class="img-responsive">
        </button>
        <div class="row">
            <div class="col-xs-12">
                <h4>{{ExWindow ? ExWindow.translator('panel-errors.last_30_days') : '' }}</h4>
            </div>
            <div class="col-xs-12">
                <div class="spin-div relative">
                    <canvas id="canvas" width="90" height="90"></canvas>
                    <span id="procent">{{amount}}</span>
                </div>
                <p>{{User.last30days}}<sup>$</sup></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "last30days",

        mounted(){
            this.ExWindow = window;
            this.getUserData();
            Vue.nextTick(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        },
        data: function (){
            return {
                User: Object,
                ExWindow: null,
                amount: 0,
                canvas : {
                    id: '',
                    span: '',
                    context: '',
                    percent: 0,
                    onePercent: 3.6,
                    positions: {
                        x: '',
                        y: ''
                    },
                    timeOut: 5,
                    result: '',
                    degree: 0,
                    interval: '',
                }
            }
        },
        methods: {
            getUserData() {
                axios.get('/user-panel/get-user-data').then((data) =>  {
                    this.User = data.data;
                    this.canvas.id = document.getElementById('canvas');
                    this.canvas.context = this.canvas.id.getContext('2d');
                    this.canvas.positions.x = this.canvas.id.width /2;
                    this.canvas.positions.y = this.canvas.id.height /2;
                    this.canvas.interval = setInterval(() => {
                        this.canvas.degree += 1;
                        this.canvas.context.clearRect(0, 0, this.canvas.id.width, this.canvas.id.height);
                        this.canvas.percent = this.canvas.degree / this.canvas.onePercent;

                        this.amount = this.canvas.percent.toFixed();

                        this.canvas.context.beginPath();
                        this.canvas.context.arc(this.canvas.positions.x, this.canvas.positions.y, 35, (Math.PI / 180) * 270, (Math.PI / 180) * (270 + 360));
                        this.canvas.context.strokeStyle = '#b2e7f9';
                        this.canvas.context.lineWidth = '6';
                        this.canvas.context.stroke();

                        this.canvas.context.beginPath();
                        this.canvas.context.strokeStyle = '#00b9eb';
                        this.canvas.context.lineWidth = '8';
                        this.canvas.context.arc(this.canvas.positions.x, this.canvas.positions.y, 35, (Math.PI / 180) * 270, (Math.PI / 180) * (270 + this.canvas.degree));
                        this.canvas.context.stroke();
                        if (this.canvas.degree >= this.canvas.onePercent * this.User.last30days /10) clearInterval(this.canvas.interval);
                    }, this.canvas.timeout)
                });

            },
            loading() {

            }
        }
    }
</script>

<style scoped>

</style>
