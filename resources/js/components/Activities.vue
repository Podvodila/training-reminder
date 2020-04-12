<template>
    <div class="activities-page">
        <el-row>
            <el-col :span="24">
                <el-button type="primary" size="small" @click="openModal">Create</el-button>
            </el-col>
        </el-row>
        <activities-list ref="list"></activities-list>
        <activities-form-modal ref="modal" @submitted="refreshList"></activities-form-modal>
    </div>
</template>

<script>
    import ActivitiesFormModal from "@/js/components/activities/FormModal";
    import ActivitiesList from "@/js/components/activities/List";

    export default {
        data() {
            return {}
        },
        methods: {
            openModal() {
                this.$refs.modal.open();
            },
            refreshList() {
                this.$refs.list.fetchActivities();
            },
        },
        components: {
            ActivitiesFormModal,
            ActivitiesList,
        },
    }
</script>

<style scoped lang="scss">
    .activities-page {
        max-width: 1200px;
        margin: 20px auto 0;
    }
</style>
