<template>
    <el-row>
        <el-header>
            <router-link to="/">Home</router-link>
            <router-link to="/about">About</router-link>
        </el-header>
        <el-col :span="24" class="main">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </el-col>
    </el-row>
</template>
