<template>
    <canvas id="chart"></canvas>
</template>

<script>
    import Chart from 'chart.js';

    export default {
        props: ['chartData', 'chartOptions'],
        data() {
            return {
                chart: null,
            }
        },
        methods: {
            init() {
                let ctx = 'chart';
                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: this.chartData,
                    options: this.chartOptions
                });
            },
        },
        watch: {
            chartData: function (data) {
                this.chart.data = data;
                this.chart.update();
            },
        },
        mounted () {
            this.init();
        },
    }
</script>
