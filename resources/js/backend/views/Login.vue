<template>
    <v-container class="fill-height admin-bg" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="4" xl="3">
                <div class="text-center mb-4">
                    <img
                        align="middle"
                        src="/images/logo-admin.png"
                        height="50"
                    />
                </div>
                <v-card
                    class="elevation-12"
                    :loading="loading"
                    :disabled="loading"
                >
                    <v-form ref="form" v-model="valid">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>{{
                                $t("title.login")
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-text-field
                                v-model="user.email"
                                :label="$t('general.e_mail')"
                                placeholder="..."
                                name="email"
                                prepend-icon="mdi-account"
                                type="text"
                                :rules="emailRules"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="user.password"
                                :label="$t('general.password')"
                                placeholder="..."
                                name="password"
                                prepend-icon="mdi-lock"
                                type="password"
                                :rules="[(v) => !!v || $t('general.required')]"
                                required
                            ></v-text-field>
                            <v-container v-if="!showLoginButton">
                                <v-container v-if="isReadyAfterCaptchaFail">
                                    <p>
                                        {{
                                            $t("general.click_on_number") +
                                            " " +
                                            validCaptcha.key
                                        }}
                                    </p>
                                    <v-btn
                                        class="mr-2"
                                        v-for="captcha in captchaPool"
                                        :key="captcha.key"
                                        @click="checkCaptcha(captcha.key)"
                                        :loading="isCaptchaLoading"
                                        ><v-icon
                                            >mdi-numeric-{{
                                                String(captcha.value)[0]
                                            }}-circle-outline</v-icon
                                        >
                                        <v-icon v-if="captcha.value > 9"
                                            >mdi-numeric-{{
                                                String(captcha.value)[1]
                                            }}-circle-outline</v-icon
                                        >
                                    </v-btn>
                                </v-container>
                                <p v-else>
                                    {{
                                        $t("general.please_wait") +
                                        " " +
                                        captchaTries +
                                        " " +
                                        (captchaTries > 1
                                            ? $t("general.seconds")
                                            : $t("general.second"))
                                    }}
                                </p>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                v-if="showLoginButton"
                                color="primary"
                                @click="validateAndLogin"
                                :loading="loading"
                                class="mr-2"
                                >{{ $t("general.login") }}</v-btn
                            >
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>

        <SnackMessage ref="SnackMessage" />
    </v-container>
</template>
<script>
// import SnackMessage from "@/backend/components/SnackMessage.vue";
import SnackMessage from "../components/SnackMessage";

import Jwt from "jsonwebtoken";

export default {
    data() {
        return {
            user: {
                email: "",
                password: "",
                remember_me: false,
            },
            valid: true,
            loading: false,
            emailRules: [
                (v) => !!v || this.$t("general.required"),
                (v) =>
                    /.+@.+\..+/.test(v) || this.$t("general.email_not_valid"),
            ],
            validCaptcha: {},
            captchaPool: [],
            isCaptchaValid: false,
            captchaTries: 0,
            isReadyAfterCaptchaFail: true,
            isCaptchaLoading: false,
            showLoginButton: false,
        };
    },

    components: {
        SnackMessage,
    },
    mounted() {
        this.generateCaptcha();
    },

    methods: {
        snackMessage(msg, type) {
            this.$refs.SnackMessage.showMessage(msg, type);
        },
        validateAndLogin() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                axios
                    .post("/api/auth/login", this.user)
                    .then((response) => {
                        if (response.data.status_code == 201) {
                            this.$refs.SnackMessage.showMessage(
                                "general.error_login",
                                "error",
                                5000
                            );
                            this.loading = false;
                            this.captchaTries = this.captchaTries + 1;
                            this.generateCaptcha();
                            this.showLoginButton = false;
                        } else {
                            localStorage.setItem(
                                "own-spa-token",
                                Jwt.sign({ user: response.data }, "itbjwttoken")
                            );

                            this.$store.dispatch(
                                "authenticate",
                                response.data.user
                            );
                            this.$router.push("/admin");
                        }
                    })
                    .catch((error) => {
                        this.$refs.SnackMessage.showMessage(
                            "general.error_message",
                            "error"
                        );
                        this.loading = false;
                        this.generateCaptcha();
                        this.showLoginButton = false;
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            console.log("Error", error.message);
                        }
                        console.log(error.config);
                    });
            }
        },
        generateCaptcha() {
            var numbers = [
                { key: "One", value: 1 },
                { key: "Two", value: 2 },
                { key: "Three", value: 3 },
                { key: "Four", value: 4 },
                { key: "Five", value: 5 },
                { key: "Six", value: 6 },
                { key: "Seven", value: 7 },
                { key: "Eight", value: 8 },
                { key: "Nine", value: 9 },
                { key: "Ten", value: 10 },
                { key: "Eleven", value: 11 },
                { key: "Twelve", value: 12 },
                { key: "Thirteen", value: 13 },
                { key: "Fourteen", value: 14 },
                { key: "Fifteen", value: 15 },
                { key: "Sixteen", value: 16 },
                { key: "Seventeen", value: 17 },
                { key: "Eighteen", value: 18 },
                { key: "Nineteen", value: 19 },
                { key: "Twenty", value: 20 },
                { key: "Twenty-one", value: 21 },
                { key: "Twenty-two", value: 22 },
                { key: "Twenty-three", value: 23 },
                { key: "Twenty-four", value: 24 },
                { key: "Twenty-five", value: 25 },
                { key: "Twenty-six", value: 26 },
                { key: "Twenty-seven", value: 27 },
                { key: "Twenty-eight", value: 28 },
                { key: "Twenty-nine", value: 29 },
                { key: "Thirty", value: 30 },
                { key: "Thirty-one", value: 31 },
                { key: "Thirty-two", value: 32 },
                { key: "Thirty-three", value: 33 },
                { key: "Thirty-four", value: 34 },
                { key: "Thirty-five", value: 35 },
                { key: "Thirty-six", value: 36 },
                { key: "Thirty-seven", value: 37 },
                { key: "Thirty-eight", value: 38 },
                { key: "Thirty-nine", value: 39 },
                { key: "Forty", value: 40 },
                { key: "Forty-one", value: 41 },
                { key: "Forty-two", value: 42 },
                { key: "Forty-three ", value: 43 },
                { key: "Forty-four", value: 44 },
                { key: "Forty-five", value: 45 },
                { key: "Forty-six", value: 46 },
                { key: "Forty-seven", value: 47 },
                { key: "Forty-eight", value: 48 },
                { key: "Forty-nine", value: 49 },
                { key: "Fifty", value: 50 },
            ];
            if (this.captchaTries > 0) {
                this.captchaPool = [];
            }
            for (var i = 0; i < 4; i++) {
                var numberIndex = Math.floor(Math.random() * numbers.length);
                this.captchaPool.push(numbers[numberIndex]);
                numbers.splice(numberIndex, 1);
            }
            var validCaptchaIndex = Math.floor(Math.random() * 4);
            this.validCaptcha = this.captchaPool[validCaptchaIndex];
        },
        checkCaptcha(key) {
            if (key == this.validCaptcha.key) {
                this.isCaptchaValid = true;
                this.isCaptchaLoading = true;
                setTimeout(() => {
                    this.isCaptchaLoading = false;
                    this.showLoginButton = true;
                }, 1000 * this.captchaTries);
            } else {
                this.captchaTries = this.captchaTries + 1;
                this.isReadyAfterCaptchaFail = false;
                setTimeout(() => {
                    this.generateCaptcha();
                    this.isReadyAfterCaptchaFail = true;
                }, 1000 * this.captchaTries);
            }
        },
    },
};
</script>
