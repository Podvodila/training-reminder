<template>
    <div>
        <div class="filters-wrap">
            <el-form :inline="true" size="mini" class="filters">
                <el-form-item>
                    <el-select v-model="filters.activity_id" placeholder="Activity" :loading="activitiesLoading">
                        <el-option
                            :key="activity.id"
                            :label="activity.name"
                            :value="activity.id"
                            v-for="activity in activities">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-date-picker
                        v-model="dateRange"
                        type="daterange"
                        range-separator="To"
                        value-format="yyyy-MM-dd"
                        :clearable="false"
                        start-placeholder="Date from"
                        end-placeholder="Date to">
                    </el-date-picker>
                </el-form-item>
            </el-form>
        </div>
        <div class="chart-wrap" v-loading="statisticsLoading">
            <chart :chart-data="chartData" :chart-options="chartOptions"></chart>
        </div>
    </div>
</template>

<script>
    import Chart from "@/js/components/statistics/Chart";
    import 'chartjs-plugin-colorschemes/src/plugins/plugin.colorschemes';
    import { Classic20 } from 'chartjs-plugin-colorschemes/src/colorschemes/colorschemes.tableau';
    import { statisticsList, activityAll } from "@/js/includes/endpoints";

    export default {
        data() {
            return {
                chartData: {},
                chartOptions: {
                    plugins: {
                        colorschemes: {
                            scheme: Classic20,
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Repetitions'
                            }
                        }],
                    },
                    maintainAspectRatio: false,
                },
                filters: {
                    date_from: moment().subtract(1, 'month').format('YYYY-MM-DD'),
                    date_to: moment().format('YYYY-MM-DD'),
                    activity_id: null,
                },
                activities: [],
                statisticsLoading: false,
                activitiesLoading: false,
            }
        },
        methods: {
            getStatistics() {
                this.statisticsLoading = true;
                statisticsList(this.filters).then(response => {
                    this.chartData = response.data;
                }).finally(() => {
                    this.statisticsLoading = false;
                });
            },
            getActivities() {
                this.activitiesLoading = true;
                return activityAll().then(response => {
                    this.activities = response.data;
                }).finally(() => {
                    this.activitiesLoading = false;
                });
            },
            init() {
                this.getActivities().then(() => {
                    if (!_.isEmpty(this.activities)) {
                        this.filters.activity_id = this.activities[0].id;
                    }
                });
            },
        },
        computed: {
            dateRange: {
                get() {
                    return [
                        this.filters.date_from,
                        this.filters.date_to
                    ];
                },
                set(dates) {
                    this.filters.date_from = dates ? dates[0] : null;
                    this.filters.date_to = dates ? dates[1] : null;
                },
            },
        },
        watch: {
            filters: {
                handler: function () {
                    this.getStatistics();
                },
                deep: true,
            }
        },
        mounted() {
            this.init();
        },
        components: {Chart},
    }
</script>

<style scoped lang="scss">
    .chart-wrap {
        height: calc(100vh - 127px);
        width: 100vw;
        position: relative;
    }

    .filters-wrap {
        .filters {
            margin-top: 20px;
            margin-left: 30px;
        }
    }
</style>
