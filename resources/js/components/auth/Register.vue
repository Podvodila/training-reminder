<template>
    <section>
        <el-form>
            <el-form-item label="Email">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="Name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Password">
                <el-input v-model="form.password"></el-input>
            </el-form-item>
        </el-form>
        <el-button @click="submit">SUBMIT</el-button>
    </section>
</template>

<script>

    export default {
        data() {
            return {
                form: {
                    email: '',
                    name: '',
                    password: '',
                },
            }
        },
        methods: {
            submit() {
                this.$auth.register({
                    params: this.form,
                    autoLogin: true,
                    rememberMe: true,
                    redirect: null, //todo: redirect
                });
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>
