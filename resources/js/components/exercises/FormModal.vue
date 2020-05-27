<template>
    <el-dialog :title="modalTitle" :visible.sync="modalVisible">
        <el-form label-width="100px"
                 label-position="left"
                 ref="form"
                 @submit.native.prevent="save"
                 size="small"
                 v-loading="formLoading">
            <el-form-item label="Name" :error="errors.get('name')">
                <el-input placeholder="Name" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Type" :error="errors.get('type')">
                <el-select v-model="form.type">
                    <el-option
                        :key="key"
                        :label="type.title"
                        :value="key"
                        v-for="(type, key) in exerciseTypes">
                    </el-option>
                </el-select>
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
    import { storeExercise, updateExercise, showExercise } from "@/js/includes/endpoints";
    import methods from "@/js/includes/utils";

    export default {
        data() {
            const form = {
                id: null,
                name: '',
                type: Laravel.Exercise.TYPE_OTHER,
            };

            return {
                form: _.cloneDeep(form),
                originForm: _.cloneDeep(form),
                modalVisible: false,
                exerciseTypes: Laravel.Exercise.TYPES,
                errors: new Errors(),
                formLoading: false,
                submitLoading: false,
            }
        },
        methods: {
            ...methods,
            fetchExercise() {
                this.formLoading = true;

                showExercise(this.form).then(response => {
                    this.form = this.mergeExistingFields(this.form, response.data);
                }).finally(() => {
                    this.formLoading = false;
                });
            },
            save() {
                this.submitLoading = true;

                const action = this.form.id ? updateExercise : storeExercise;
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
                    this.fetchExercise();
                }
                this.modalVisible = true;
            },
        },
        computed: {
            modalTitle() {
                return (this.form.id ? 'Edit' : 'Create') + ' Exercise';
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
