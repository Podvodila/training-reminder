<template>
    <div>
        <div v-if="$auth.ready()">
            <router-view></router-view>
        </div>
        <div v-if="!$auth.ready()">
            Loading...
        </div>
    </div>
</template>

<script>
    export default {}
</script>

<style lang="scss">
    @import "~@/sass/_variables";

    body {
        margin: 0;
    }

    @media (max-width: $--lg) {
        .el-dialog {
            width: calc(100% - 40px);
            margin-left: 20px;
            margin-right: 20px;
        }
    }
</style>
