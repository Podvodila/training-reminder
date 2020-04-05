<template>
    <section class="register-page">
        <div class="register-form-container">
            <div class="register-form-wrap">
                <el-form label-width="100px" class="register-form">
                    <el-form-item label="Email" :error="errors.get('email')">
                        <el-input v-model="form.email" @input="errors.clear('email')"></el-input>
                    </el-form-item>
                    <el-form-item label="Name" :error="errors.get('name')">
                        <el-input v-model="form.name" @input="errors.clear('name')"></el-input>
                    </el-form-item>
                    <el-form-item label="Password" :error="errors.get('password')">
                        <el-input v-model="form.password" type="password" autocomplete="off" @input="errors.clear('password')"></el-input>
                    </el-form-item>
                </el-form>
                <el-button @click="submit" type="success" class="register-btn" :loading="formLoading">Register</el-button>
                <el-button @click="redirectToLogin" type="primary" class="login-btn">Sign In</el-button>
            </div>
        </div>
    </section>
</template>

<script>
    import Errors from "@/js/includes/Errors";

    export default {
        data() {
            return {
                form: {
                    email: '',
                    name: '',
                    password: '',
                },
                formLoading: false,
                errors: new Errors(),
            }
        },
        methods: {
            submit() {
                this.formLoading = true;
                this.$auth.register({
                    params: this.form,
                    autoLogin: true,
                    rememberMe: true,
                    redirect: '/',
                    error: function (error) {
                        if (error.response.data.errors) {
                            this.errors.record(error.response.data.errors);
                        } else if (error.response.data.message) {
                            this.$message.error(error.response.data.message);
                        } else {
                            this.$message.error('Unknown server error');
                        }
                    },
                }).finally(() => {
                    this.formLoading = false;
                });
            },
            redirectToLogin() {
                this.$router.push({name: 'Login'});
            },
        },
    }
</script>

<style lang="scss" scoped>
    .register-page {
        height: calc(100vh - 60px);

        .register-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            .register-form-wrap {
                width: 400px;

                .register-btn {
                    width: 100%;
                }

                .login-btn {
                    width: 100%;
                    margin-top: 10px;
                    margin-left: 0;
                }
            }
        }
    }
</style>
