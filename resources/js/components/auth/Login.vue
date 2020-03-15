<template>
    <section>
        <el-form>
            <el-form-item label="Email">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="Password">
                <el-input v-model="form.password"></el-input>
            </el-form-item>
        </el-form>
        <el-button @click="login">LOGIN</el-button>
    </section>
</template>

<script>
    import { csrfCookie } from "@/js/includes/endpoints";

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },
            }
        },
        methods: {
            login() {
                csrfCookie().then(response => {
                    this.$auth.login({
                        data: this.form,
                        rememberMe: true,
                        redirect: null, //todo: redirect
                    });
                });
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>
