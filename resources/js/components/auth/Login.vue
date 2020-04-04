<template>
    <section class="login-page">
        <div class="login-form-container">
            <div class="login-form-wrap">
                <el-form label-width="100px" class="login-form">
                    <el-form-item label="Email" :error="errors.get('email')">
                        <el-input v-model="form.email" @input="errors.clear('email')"></el-input>
                    </el-form-item>
                    <el-form-item label="Password" :error="errors.get('password')">
                        <el-input v-model="form.password"
                                  type="password"
                                  autocomplete="off"
                                  @input="errors.clear('password')"
                                  @keyup.enter.native="login">
                        </el-input>
                    </el-form-item>
                </el-form>
                <el-button @click="login" type="success" class="login-btn" :loading="formLoading">Log in</el-button>
                <el-button @click="redirectToRegister" type="primary" class="register-btn">Sign Up</el-button>
            </div>
        </div>
    </section>
</template>

<script>
    import { csrfCookie } from "@/js/includes/endpoints";
    import Errors from "@/js/includes/Errors";

    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },
                formLoading: false,
                errors: new Errors(),
            }
        },
        methods: {
            login() {
                this.formLoading = true;
                csrfCookie().then(response => {
                    this.$auth.login({
                        data: this.form,
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
                }).catch(e => {
                    this.formLoading = false;
                });
            },
            redirectToRegister() {
                this.$router.push({name: 'Register'});
            },
        },
    }
</script>

<style lang="scss" scoped>
    .login-page {
        height: 100vh;
        .login-form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            .login-form-wrap {
                width: 400px;

                .login-btn {
                    width: 100%;
                }

                .register-btn {
                    width: 100%;
                    margin-top: 10px;
                    margin-left: 0;
                }
            }
        }
    }
</style>
