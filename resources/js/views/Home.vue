<template>
    <el-row class="home-page">
        <el-header class="header">
            <el-menu :default-active="$route.path" unique-opened router mode="horizontal" class="menu-wrap">
                <template v-for="(item, index) in menu">
                    <el-submenu :index="String(index)" v-if="item.children && item.children.length">
                        <template slot="title">
                            <span slot="title">{{item.name}}</span>
                        </template>
                        <el-menu-item v-for="child in item.children" :index="child.path" :key="child.name">
                            <span slot="title">{{child.name}}</span>
                        </el-menu-item>
                    </el-submenu>
                    <el-menu-item v-else :index="item.path" :key="item.name" :class="(item.component ? '' : 'is-disabled')">
                        <span slot="title">{{item.name}}</span>
                    </el-menu-item>
                </template>
            </el-menu>
            <div class="profile-section">
                <el-button v-if="$auth.check()" @click="logout" type="info" plain>Sign Out</el-button>
            </div>
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
                this.$auth.logout({
                    redirect: '/login',
                });
            },
        },
        computed: {
            menu() {
                const menu = _.find(this.$router.options.routes, { menu: true });
                return _.filter(menu.children, (menuItem) => {
                    return this.$auth.check() && menuItem.meta && menuItem.meta.authRequired || !this.$auth.check() && (!menuItem.meta || !menuItem.meta.authRequired);
                });
            },
        },
    }
</script>

<style lang="scss" scoped>
    .home-page {
        padding-top: 60px;

        .header {
            display: flex;
            justify-content: space-between;
            box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.4);
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            right: 0;

            .menu-wrap {
                border-bottom: 0;
            }

            .profile-section {
                display: flex;
                align-items: center;
            }
        }
    }
</style>
