<template>
    <div class="activities-list">
        <el-table :data="activities" v-loading="activitiesLoading">
            <el-table-column label="Name" prop="name"></el-table-column>
            <el-table-column label="Interval (minutes)" prop="interval_minutes"></el-table-column>
            <el-table-column label="Activity Range">
                <template slot-scope="scope">
                    <div>
                        <span>{{scope.row.available_time_from}}</span>
                        <span>to</span>
                        <span>{{scope.row.available_time_to}}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="Status">
                <template slot-scope="scope">
                    <div>
                        <div :class="{
                                'color-warning': scope.row.status === laravel.Activity.STATUS_INACTIVE,
                                'color-success': scope.row.status === laravel.Activity.STATUS_ACTIVE }">
                            {{laravel.Activity.STATUSES[scope.row.status].title}}
                        </div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="150">
                <template slot-scope="scope">
                    <el-tooltip placement="top">
                        <template slot="content">
                            {{scope.row.status === laravel.Activity.STATUS_ACTIVE ? 'Pause' : 'Start'}}
                        </template>
                        <span>
                            <el-button :loading="isToggleLoading(scope.row.id)"
                                       @click="toggleStatus(scope.row.id)"
                                       :type="scope.row.status === laravel.Activity.STATUS_ACTIVE ? 'info' : 'success'"
                                       :icon="scope.row.status === laravel.Activity.STATUS_ACTIVE ? 'el-icon-video-pause' : 'el-icon-video-play'"
                                       circle>
                            </el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip placement="top" content="Edit">
                        <span>
                            <el-button @click="editActivity(scope.row.id)" type="warning" icon="el-icon-edit" circle></el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip placement="top" content="Delete">
                        <span>
                            <el-button @click="onDeleteActivity(scope.row.id)" type="danger" icon="el-icon-delete" circle></el-button>
                        </span>
                    </el-tooltip>
                </template>
            </el-table-column>
        </el-table>
        <div class="pagination-wrap">
            <el-pagination
                layout="prev, pager, next"
                :current-page.sync="page"
                :page-size.sync="pageSize"
                :total="total"
                class="pull-right">
            </el-pagination>
        </div>
        <activity-form-modal ref="modal" @submitted="fetchActivities"></activity-form-modal>
    </div>
</template>

<script>
    import ActivityFormModal from "@/js/components/activities/FormModal";
    import {activityList, destroyActivity, toggleActivityStatus} from "@/js/includes/endpoints";

    export default {
        data() {
            return {
                laravel: Laravel,
                activities: [],
                activitiesLoading: false,
                page: 1,
                pageSize: 10,
                total: 0,
                toggleLoading: [],
            }
        },
        methods: {
            fetchActivities() {
                this.activitiesLoading = true;
                activityList({
                    page: this.page,
                    pageSize: this.pageSize,
                }).then(response => {
                    this.total = response.data.total;
                    this.activities = response.data.data;
                }).finally(() => {
                    this.activitiesLoading = false;
                });
            },
            editActivity(id) {
                this.$refs.modal.open(id);
            },
            onDeleteActivity(id) {
                this.$confirm('This will permanently delete the Activity. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.deleteActivity(id);
                }).catch(() => {
                    //
                });
            },
            deleteActivity(id) {
                destroyActivity(id).then(response => {
                    this.fetchActivities();
                });
            },
            toggleStatus(id) {
                this.toggleLoading.push(id);
                toggleActivityStatus(id).then(response => {
                    this.fetchActivities();
                }).catch(error => {
                    if (error.response.data.message) {
                        this.$message.error(error.response.data.message);
                    } else {
                        this.$message.error('Unknown server error');
                    }
                }).finally(() => {
                    _.remove(this.toggleLoading, (el) => el === id);
                });
            },
            isToggleLoading(id) {
                return this.toggleLoading.includes(id);
            },
        },
        watch: {
            page: function () {
                this.fetchActivities();
            },
        },
        mounted() {
            this.fetchActivities();
        },
        components: {
            ActivityFormModal,
        },
    }
</script>

<style scoped>

</style>
