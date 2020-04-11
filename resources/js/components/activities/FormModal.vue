<template>
    <el-dialog :title="modalTitle" :visible.sync="modalVisible">
        <el-form label-width="150px" ref="form" @submit.native.prevent="save" size="small" v-loading="formLoading">
            <el-form-item label="Name" :error="errors.get('name')">
                <el-input placeholder="Name" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Interval (minutes)" :error="errors.get('interval_minutes')">
                <el-input-number v-model="form.interval_minutes" :min="1" :max="1000"></el-input-number>
            </el-form-item>
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
            <el-form-item label="Exercises per Interval" :error="errors.get('exercises_per_time')">
                <el-input-number v-model="form.exercises_per_time" :min="1" :max="10"></el-input-number>
            </el-form-item>
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
    import {showActivity, storeActivity, updateActivity} from "@/js/includes/endpoints";

    export default {
        data() {
            const form = {
                id: null,
                name: '',
                interval_minutes: null,
                available_time_from: null,
                available_time_to: null,
                exercises_per_time: null,
            };

            return {
                form: _.cloneDeep(form),
                originForm: _.cloneDeep(form),
                modalVisible: false,
                errors: new Errors(),
                formLoading: false,
                submitLoading: false,
            }
        },
        methods: {
            ...methods,
            fetchActivity() {
                this.formLoading = true;

                showActivity(this.form).then(response => {
                    this.form = this.mergeExistingFields(this.form, response.data);
                }).finally(() => {
                    this.formLoading = false;
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
    }
</script>

<style scoped lang="scss">

</style>
