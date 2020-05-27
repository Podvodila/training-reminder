<template>
    <div class="exercises-page p-a-20">
        <el-row>
            <el-col :span="24">
                <el-button type="primary" size="small" @click="openModal">Create</el-button>
            </el-col>
        </el-row>
        <exercises-list ref="list"></exercises-list>
        <exercise-form-modal ref="modal" @submitted="refreshList"></exercise-form-modal>
    </div>
</template>

<script>
    import ExercisesList from "@/js/components/exercises/List";
    import ExerciseFormModal from "@/js/components/exercises/FormModal";

    export default {
        data() {
            return {}
        },
        methods: {
            openModal() {
                this.$refs.modal.open();
            },
            refreshList() {
                this.$refs.list.fetchExercises();
            },
        },
        components: {
            ExercisesList,
            ExerciseFormModal,
        },
    }
</script>

<style scoped lang="scss">
    .exercises-page {
        max-width: 1200px;
        margin: 0 auto 0;
    }
</style>
