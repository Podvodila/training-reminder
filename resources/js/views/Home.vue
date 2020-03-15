<template>
    <el-row>
        <el-header>
            <router-link to="/">Home</router-link>
            <router-link to="/about">About</router-link>
            <router-link v-if="!$auth.check()" to="/login">Login</router-link>
            <router-link v-if="!$auth.check()" to="/register">Register</router-link>
            <el-button v-if="$auth.check()" @click="logout">Sign Out</el-button>
        </el-header>
        <el-col :span="24" class="main">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        methods: {
            logout() {
                this.$auth.logout();
            },
        },
    }
</script>
