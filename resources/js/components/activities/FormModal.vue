<template>
    <el-dialog :title="modalTitle" :visible.sync="modalVisible" class="activity-form-modal">
        <el-form label-width="150px" ref="form" @submit.native.prevent="save" size="small" v-loading="formLoading">
            <el-form-item label="Name" :error="errors.get('name')">
                <el-input placeholder="Name" v-model="form.name"></el-input>
            </el-form-item>
            <el-row>
                <el-col :span="11">
                    <el-form-item label="Interval (minutes)" :error="errors.get('interval_minutes')">
                        <el-input-number v-model="form.interval_minutes" :min="1" :max="1000"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col :span="12" :offset="1">
                    <el-form-item label="Exercises per Interval" :error="errors.get('exercises_per_time')">
                        <el-input-number v-model="form.exercises_per_time" :min="1" :max="10"></el-input-number>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="Activity Range" :error="errors.get('available_time_from') || errors.get('available_time_to')">
                <el-time-picker
                    is-range
                    v-model="availabilityRange"
                    value-format="HH:mm:ss"
                    range-separator="To"
                    start-placeholder="Start time"
                    end-placeholder="End time">
                </el-time-picker>
            </el-form-item>
            <el-divider content-position="left">Exercises</el-divider>
            <div class="exercises-wrap">
                <el-collapse v-model="activeExercise" accordion>
                    <el-collapse-item v-for="(exercise, index) in form.exercises"
                                      :key="index"
                                      :name="index">
                        <template slot="title">
                            <div class="exercise-item-title-wrap">
                                <div class="exercise-item-title">{{exerciseTitle(exercise, index)}}</div>
                                <i v-if="index !== 0"
                                   @click.stop="removeExercise(index)"
                                   class="el-icon-delete color-danger remove-exercise-btn">
                                </i>
                            </div>
                        </template>
                        <el-row>
                            <el-col :span="11">
                                <el-form-item label="Exercise" :error="errors.get('exercises.' + index + '.exercise_id')">
                                    <el-select v-model="exercise.exercise_id">
                                        <el-option
                                            :key="index"
                                            :label="exercise.name"
                                            :value="exercise.id"
                                            :disabled="!isExerciseAvailable(exercise.id)"
                                            v-for="(exercise, index) in exercises">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12" :offset="1">
                                <el-form-item v-if="isSportExercise(exercise.exercise_id)"
                                              label="Progression Type"
                                              :error="errors.get('exercises.' + index + '.progression_type')">
                                    <el-select v-model="exercise.progression_type">
                                        <el-option
                                            :key="key"
                                            :label="type.title"
                                            :value="key"
                                            v-for="(type, key) in laravel.Activity.PROGRESSION_TYPES">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row v-if="exercise.progression_type === laravel.Activity.PROGRESSION_TYPE_AUTO">
                            <el-col :span="11">
                                <el-form-item label="Default Sets">
                                    <el-input-number v-model="exercise.default_sets" :min="1" :max="100"></el-input-number>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12" :offset="1">
                                <el-form-item label="Default Repetitions">
                                    <el-input-number v-model="exercise.default_repetitions" :min="1" :max="500"></el-input-number>
                                </el-form-item>
                            </el-col>
                            <el-col :span="11">
                                <el-form-item label="Max Reps Per Set">
                                    <el-input-number v-model="exercise.max_reps_per_set" :min="1" :max="100"></el-input-number>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-collapse-item>
                </el-collapse>
                <el-button @click="addExercise" class="add-exercise-btn" icon="el-icon-circle-plus-outline">Add Exercise</el-button>
            </div>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click.native="modalVisible = false" size="small">Cancel</el-button>
            <el-button type="success" @click.native="save" size="small" :loading="submitLoading">Save</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import Errors from "@/js/includes/Errors";
    import methods from "@/js/includes/utils";
    import {showActivity, storeActivity, updateActivity, getExercises} from "@/js/includes/endpoints";

    export default {
        data() {
            const exercise = {
                exercise_id: null,
                progression_type: Laravel.Activity.PROGRESSION_TYPE_STATIC,
                default_sets: null,
                default_repetitions: null,
                max_reps_per_set: null,
            };

            const form = {
                id: null,
                name: '',
                interval_minutes: null,
                available_time_from: null,
                available_time_to: null,
                exercises_per_time: null,
                exercises: [_.cloneDeep(exercise)],
            };

            return {
                laravel: Laravel,
                form: _.cloneDeep(form),
                originForm: _.cloneDeep(form),
                originExercise: _.cloneDeep(exercise),
                modalVisible: false,
                errors: new Errors(),
                formLoading: false,
                submitLoading: false,
                activeExercise: 0,
                exercises: [],
            }
        },
        methods: {
            ...methods,
            fetchActivity() {
                this.formLoading = true;

                showActivity(this.form).then(response => {
                    let form = this.mergeExistingFields(this.form, response.data);
                    response.data.exercises.forEach((exercise, ind) => {
                        form.exercises[ind] = this.mergeExistingFields(this.originExercise, exercise.pivot);
                    });
                    this.form = form;
                }).finally(() => {
                    this.formLoading = false;
                });
            },
            fetchExercises() {
                getExercises().then(response => {
                    this.exercises = response.data;
                });
            },
            save() {
                this.submitLoading = true;

                const action = this.form.id ? updateActivity : storeActivity;
                action(this.form).then(response => {
                    this.$message.success('Successfully Saved');
                    this.modalVisible = false;
                    this.$emit('submitted');
                }).catch(error => {
                    if (error.response.data.errors) {
                        this.errors.record(error.response.data.errors);
                    } else if (error.response.data.message) {
                        this.$message.error(error.response.data.message);
                    } else {
                        this.$message.error('Unknown server error');
                    }
                }).finally(() => {
                    this.submitLoading = false;
                });
            },
            open(id = null) {
                if (id) {
                    this.form.id = id;
                    this.fetchActivity();
                }
                this.modalVisible = true;
            },
            exerciseTitle(exercise, index) {
                let title = 'Exercise #' + (index + 1);
                if (exercise.exercise_id) {
                    title = _.find(this.exercises, {'id': exercise.exercise_id}).name;
                }
                return title;
            },
            isSportExercise(id) {
                if (!id) {
                    return false;
                }

                const exercise = _.find(this.exercises, {id: id});
                return exercise.type === Laravel.Exercise.TYPE_SPORT;
            },
            removeExercise(index) {
                this.form.exercises.splice(index, 1);
            },
            addExercise() {
                this.form.exercises.push(_.cloneDeep(this.originExercise));
            },
            isExerciseAvailable(exerciseId) {
                return !_.map(this.form.exercises, 'exercise_id').includes(exerciseId);
            },
        },
        computed: {
            modalTitle() {
                return (this.form.id ? 'Edit' : 'Create') + ' Activity';
            },
            availabilityRange: {
                get() {
                    return [
                        this.form.available_time_from ? this.form.available_time_from : '',
                        this.form.available_time_to ? this.form.available_time_to : ''
                    ];
                },
                set(time) {
                    this.form.available_time_from = time ? time[0] : null;
                    this.form.available_time_to = time ? time[1] : null;
                },
            },
        },
        watch: {
            modalVisible: function (isVisible) {
                if (!isVisible) {
                    this.resetForm();
                }
            },
        },
        mounted() {
            this.fetchExercises();
        }
    }
</script>

<style scoped lang="scss">
    .activity-form-modal {
        .exercise-item-title-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;

            .remove-exercise-btn {
                font-size: 18px;
                margin-right: 15px;
            }
        }

        .add-exercise-btn {
            margin-top: 10px;
        }
    }
</style>
