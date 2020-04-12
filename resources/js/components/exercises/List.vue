<template>
    <div class="exercises-list">
        <el-table :data="exercises" v-loading="exercisesLoading">
            <el-table-column label="Name" prop="name"></el-table-column>
            <el-table-column label="Type">
                <template slot-scope="scope">
                    <div>{{laravel.Exercise.TYPES[scope.row.type].title}}</div>
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="150">
                <template slot-scope="scope">
                    <el-tooltip placement="top" content="Edit">
                        <span>
                            <el-button @click="() => editExercise(scope.row.id)" type="warning" icon="el-icon-edit" circle></el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip placement="top" content="Delete">
                        <span>
                            <el-button @click="() => onDeleteExercise(scope.row.id)" type="danger" icon="el-icon-delete" circle></el-button>
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
        <exercise-form-modal ref="modal" @submitted="fetchExercises"></exercise-form-modal>
    </div>
</template>

<script>
    import ExerciseFormModal from "@/js/components/exercises/FormModal";
    import { exercisesList, destroyExercise } from "@/js/includes/endpoints";

    export default {
        data() {
            return {
                laravel: Laravel,
                exercises: [],
                exercisesLoading: false,
                page: 1,
                pageSize: 10,
                total: 0,
            }
        },
        methods: {
            fetchExercises() {
                this.exercisesLoading = true;
                exercisesList({
                    page: this.page,
                    pageSize: this.pageSize,
                }).then(response => {
                    this.total = response.data.total;
                    this.exercises = response.data.data;
                }).finally(() => {
                    this.exercisesLoading = false;
                });
            },
            editExercise(id) {
                this.$refs.modal.open(id);
            },
            onDeleteExercise(id) {
                this.$confirm('This will permanently delete the Exercise. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.deleteExercise(id);
                }).catch(() => {
                    //
                });
            },
            deleteExercise(id) {
                destroyExercise(id).then(response => {
                    this.fetchExercises();
                });
            },
        },
        watch: {
            page: function () {
                this.fetchExercises();
            },
        },
        mounted() {
            this.fetchExercises();
        },
        components: {
            ExerciseFormModal,
        },
    }
</script>

<style scoped lang="scss">
    .exercises-list {
        .pagination-wrap {
            display: flex;
            justify-content: center;
        }
    }
</style>
