<template>
    <div>
        <header>
            <v-container>
                <v-row no-gutters>
                    <h1 class="font-weight-light headline">
                        {{ $t("title.settings") }}
                    </h1>
                </v-row>
            </v-container>
        </header>
        <section class="py-7">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" xl="4">
                        <v-card>
                            <v-card-title>{{
                                $t("settings.general_data")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="
                                        settings.general_company_name.value
                                    "
                                    :label="$t('settings.general_company_name')"
                                    @input="
                                        saveSetting(
                                            settings.general_company_name
                                        )
                                    "
                                ></v-text-field>

                                <v-text-field
                                    v-model="
                                        settings.general_company_street.value
                                    "
                                    :label="
                                        $t('settings.general_company_street')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.general_company_street
                                        )
                                    "
                                ></v-text-field>
                                <v-text-field
                                    v-model="
                                        settings.general_company_city.value
                                    "
                                    :label="$t('settings.general_company_city')"
                                    @input="
                                        saveSetting(
                                            settings.general_company_city
                                        )
                                    "
                                ></v-text-field>

                                <v-text-field
                                    v-model="settings.general_company_vat.value"
                                    :label="$t('settings.general_company_vat')"
                                    @input="
                                        saveSetting(
                                            settings.general_company_vat
                                        )
                                    "
                                ></v-text-field>
                            </v-card-text>
                        </v-card>

                        <v-card class="mt-5">
                            <v-card-title>{{
                                $t("settings.other_data")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="settings.general_instagram.value"
                                    :label="$t('settings.general_instagram')"
                                    @input="
                                        saveSetting(settings.general_instagram)
                                    "
                                ></v-text-field>
                                <v-textarea
                                    v-model="
                                        settings.general_instagram_token.value
                                    "
                                    :label="
                                        $t('settings.general_instagram_token')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.general_instagram_token
                                        )
                                    "
                                ></v-textarea>
                                <v-row no-gutters>
                                    <v-tooltip top>
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <div
                                                class="ap-wrapper d-inline-block"
                                                style="width: 193px"
                                                v-bind="attrs"
                                                v-on="on"
                                            >
                                                <v-switch
                                                    color="green"
                                                    v-model="
                                                        settings.is_on_frontend
                                                            .value
                                                    "
                                                    :label="
                                                        settings.is_on_frontend
                                                            .value
                                                            ? $t(
                                                                  'settings.is_on_frontend'
                                                              )
                                                            : $t(
                                                                  'settings.is_off_frontend'
                                                              )
                                                    "
                                                    :loading="
                                                        settings.is_on_frontend
                                                            .loading
                                                    "
                                                    @change="
                                                        saveSetting(
                                                            settings.is_on_frontend
                                                        )
                                                    "
                                                ></v-switch>
                                            </div>
                                        </template>
                                        <span>{{
                                            $t(
                                                "settings.will_turn_off_pages_for_users"
                                            )
                                        }}</span>
                                    </v-tooltip>
                                    <v-text-field
                                        class="ml-3"
                                        v-if="!settings.is_on_frontend.value"
                                        v-model="
                                            settings.frontend_ip_addresses.value
                                        "
                                        outlined
                                        dense
                                        hide-details
                                        :label="$t('settings.ip_addresses')"
                                        style="
                                            max-width: 300px;
                                            margin-top: 11px;
                                        "
                                        @input="
                                            saveSetting(
                                                settings.frontend_ip_addresses
                                            )
                                        "
                                    ></v-text-field>
                                </v-row>
                            </v-card-text>
                        </v-card>

                        <v-card class="mt-5">
                            <v-card-title>{{
                                $t("settings.email")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="settings.form_email_footer.value"
                                    :label="$t('settings.email_footer')"
                                    @input="
                                        saveSetting(settings.form_email_footer)
                                    "
                                ></v-text-field>
                            </v-card-text>
                        </v-card>
                        <v-card>
                            <v-card-title>
                                {{ $t("settings.hader_data") }}
                            </v-card-title>
                            <v-card-text>
                                <div class="d-flex">
                                    <v-file-input
                                        small-chips
                                        dense
                                        prepend-icon="mdi-camera"
                                        accept="image/*"
                                        v-model="headerImage"
                                        @change="
                                            uploadHeaderImage('header-image')
                                        "
                                        :label="
                                            $t('settings.home_header_image')
                                        "
                                        :disabled="isHeaderImageLoading"
                                        :clearable="false"
                                    >
                                    </v-file-input>
                                    <v-btn
                                        small
                                        class="ml-3 mr-3"
                                        @click="deleteImage('header-image')"
                                        :disabled="
                                            isHeaderImageLoading || !headerImage
                                        "
                                    >
                                        <v-icon color="red"
                                            >mdi-trash-can-outline
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div class="d-flex">
                                    <v-file-input
                                        small-chips
                                        dense
                                        prepend-icon="mdi-camera"
                                        accept="image/*"
                                        v-model="headerSectionImage"
                                        @change="
                                            uploadHeaderImage(
                                                'section-header-image'
                                            )
                                        "
                                        :label="
                                            $t('settings.section_header_image')
                                        "
                                        :disabled="isHeaderImageLoading"
                                        :clearable="false"
                                    >
                                    </v-file-input>
                                    <v-btn
                                        small
                                        class="ml-3 mr-3"
                                        @click="
                                            deleteImage('section-header-image')
                                        "
                                        :disabled="
                                            isHeaderImageLoading ||
                                            !headerSectionImage
                                        "
                                    >
                                        <v-icon color="red"
                                            >mdi-trash-can-outline
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <v-text-field
                                    v-model="settings.header_title.value"
                                    :label="$t('settings.header_title')"
                                    @input="saveSetting(settings.header_title)"
                                ></v-text-field>
                                <v-text-field
                                    v-model="settings.header_text.value"
                                    :label="$t('settings.header_text')"
                                    @input="saveSetting(settings.header_text)"
                                ></v-text-field>
                                <v-text-field
                                    v-model="settings.content_title.value"
                                    :label="$t('settings.content_title')"
                                    @input="saveSetting(settings.content_title)"
                                ></v-text-field>
                                <v-text-field
                                    v-model="
                                        settings.gallery_content_title.value
                                    "
                                    :label="
                                        $t('settings.gallery_content_title')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.gallery_content_title
                                        )
                                    "
                                ></v-text-field>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="12" sm="6" xl="4">
                        <v-card class="mt-5 mt-sm-0">
                            <v-card-title>{{
                                $t("settings.contact_data")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="settings.general_email.value"
                                    :label="$t('settings.general_email')"
                                    @input="saveSetting(settings.general_email)"
                                ></v-text-field>
                                <v-text-field
                                    v-model="settings.general_phone.value"
                                    :label="$t('settings.general_phone')"
                                    @input="saveSetting(settings.general_phone)"
                                ></v-text-field>
                            </v-card-text>
                        </v-card>

                        <v-card class="mt-5">
                            <v-card-title>{{
                                $t("settings.bank_data")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="
                                        settings.general_company_bank_account
                                            .value
                                    "
                                    :label="
                                        $t(
                                            'settings.general_company_bank_account'
                                        )
                                    "
                                    @input="
                                        saveSetting(
                                            settings.general_company_bank_account
                                        )
                                    "
                                ></v-text-field>

                                <v-text-field
                                    v-model="settings.general_swift.value"
                                    :label="$t('settings.general_swift')"
                                    @input="saveSetting(settings.general_swift)"
                                ></v-text-field>
                                <v-text-field
                                    v-model="settings.general_iban.value"
                                    :label="$t('settings.general_iban')"
                                    @input="saveSetting(settings.general_iban)"
                                ></v-text-field>
                            </v-card-text>
                        </v-card>

                        <v-card class="mt-5">
                            <v-card-title>{{
                                $t("settings.contact_form")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="
                                        settings.contact_form_account_number
                                            .value
                                    "
                                    :label="
                                        $t(
                                            'settings.contact_form_account_number'
                                        )
                                    "
                                    @input="
                                        saveSetting(
                                            settings.contact_form_account_number
                                        )
                                    "
                                ></v-text-field>
                                <v-text-field
                                    v-model="
                                        settings.contact_form_account_specific
                                            .value
                                    "
                                    :label="
                                        $t(
                                            'settings.contact_form_account_specific'
                                        )
                                    "
                                    @input="
                                        saveSetting(
                                            settings.contact_form_account_specific
                                        )
                                    "
                                ></v-text-field>
                                <v-text-field
                                    v-model="settings.contact_form_price.value"
                                    :label="$t('settings.contact_form_price')"
                                    @input="
                                        saveSetting(settings.contact_form_price)
                                    "
                                ></v-text-field>
                            </v-card-text>
                        </v-card>
                        <v-card class="mt-5">
                            <v-card-title>{{
                                $t("settings.footer_contact_data")
                            }}</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    v-model="
                                        settings.footer_contact_title_1.value
                                    "
                                    :label="
                                        $t('settings.footer_contact_title_1')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.footer_contact_title_1
                                        )
                                    "
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="
                                        settings.general_footer_phone_1.value
                                    "
                                    :label="
                                        $t('settings.general_footer_phone_1')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.general_footer_phone_1
                                        )
                                    "
                                ></v-text-field>
                            </v-card-text>
                            <v-card-text>
                                <v-text-field
                                    v-model="
                                        settings.footer_contact_title_2.value
                                    "
                                    :label="
                                        $t('settings.footer_contact_title_2')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.footer_contact_title_2
                                        )
                                    "
                                ></v-text-field>
                                <v-text-field
                                    v-model="
                                        settings.general_footer_phone_2.value
                                    "
                                    :label="
                                        $t('settings.general_footer_phone_2')
                                    "
                                    @input="
                                        saveSetting(
                                            settings.general_footer_phone_2
                                        )
                                    "
                                ></v-text-field>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <SnackMessage ref="SnackMessage" />
            </v-container>
        </section>
    </div>
</template>

<script>
import SnackMessage from "../components/SnackMessage";

export default {
    data() {
        return {
            headerImage: null,
            headerSectionImage: null,
            isHeaderImageLoading: false,
            isHeaderSectionImageLoading: false,
            settings: {
                general_email: {
                    key: "general_email",
                    value: "",
                    loading: true,
                },
                general_phone: {
                    key: "general_phone",
                    value: "",
                    loading: true,
                },
                general_company_name: {
                    key: "general_company_name",
                    value: "",
                    loading: true,
                },
                general_company_street: {
                    key: "general_company_street",
                    value: "",
                    loading: true,
                },
                general_company_city: {
                    key: "general_company_city",
                    value: "",
                    loading: true,
                },

                general_company_vat: {
                    key: "general_company_vat",
                    value: "",
                    loading: true,
                },

                general_company_bank_account: {
                    key: "general_company_bank_account",
                    value: "",
                    loading: true,
                },

                general_swift: {
                    key: "general_swift",
                    value: "",
                    loading: true,
                },
                general_iban: {
                    key: "general_iban",
                    value: "",
                    loading: true,
                },

                contact_form_account_number: {
                    key: "contact_form_account_number",
                    value: "",
                    loading: true,
                },
                contact_form_account_specific: {
                    key: "contact_form_account_specific",
                    value: "",
                    loading: true,
                },
                contact_form_price: {
                    key: "contact_form_price",
                    value: "",
                    loading: true,
                },

                general_instagram: {
                    key: "general_instagram",
                    value: "",
                    loading: true,
                },
                general_instagram_token: {
                    key: "general_instagram_token",
                    value: "",
                    loading: true,
                },
                footer_contact_title_1: {
                    key: "footer_contact_title_1",
                    value: "",
                    loading: true,
                },
                general_footer_phone_1: {
                    key: "general_footer_phone_1",
                    value: "",
                    loading: true,
                },

                footer_contact_title_2: {
                    key: "footer_contact_title_2",
                    value: "",
                    loading: true,
                },
                general_footer_phone_2: {
                    key: "general_footer_phone_2",
                    value: "",
                    loading: true,
                },
                form_email_footer: {
                    key: "form_email_footer",
                    value: "",
                    loading: true,
                },
                is_on_frontend: {
                    key: "is_on_frontend",
                    value: false,
                    loading: true,
                },
                frontend_ip_addresses: {
                    key: "frontend_ip_addresses",
                    value: "",
                },
                header_title: {
                    key: "header_title",
                    value: "",
                    loading: true,
                },
                header_text: {
                    key: "header_text",
                    value: "",
                    loading: true,
                },
                content_title: {
                    key: "content_title",
                    value: "",
                    loading: true,
                },
                gallery_content_title: {
                    key: "gallery_content_title",
                    value: "",
                    loading: true,
                },
            },
        };
    },

    components: {
        SnackMessage,
    },

    created() {
        this.getSettings();
        this.getHeaderImages();
    },

    methods: {
        async getSettings() {
            await axios
                .get("/api/settings", {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    let settings = Object.keys(this.settings).map(
                        (i) => this.settings[i]
                    );

                    response.data.forEach(function (item, index) {
                        let setting = settings.find((s) => s.key === item.key);

                        if (setting) {
                            setting.value = item.value;
                            setting.loading = false;
                        }
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$refs.SnackMessage.showMessage(
                        "general.error",
                        "error"
                    );
                });
        },
        async saveSetting($item) {
            await axios
                .put(`/api/updateSetting`, $item, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    this.$refs.SnackMessage.showMessage(
                        "settings.setting_saved",
                        "success"
                    );
                })
                .catch((error) => {
                    console.log(error);
                    this.$refs.SnackMessage.showMessage(
                        "general.error",
                        "error"
                    );
                });
        },

        async getHeaderImages() {
            await axios
                .get(`/api/getHeaderImages`, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    if (response.data.headerImage) {
                        this.headerImage = response.data.headerImage;
                    }

                    if (response.data.headerSectionImage) {
                        this.headerSectionImage =
                            response.data.headerSectionImage;
                    }

                    this.$refs.SnackMessage.showMessage(
                        "settings.setting_saved",
                        "success"
                    );
                })
                .catch((error) => {
                    console.log(error);
                    this.$refs.SnackMessage.showMessage(
                        "general.error",
                        "error"
                    );
                });
        },

        async uploadHeaderImage(model_name) {
            this.isHeaderImageLoading = true;
            let formData = new FormData();
            if (model_name == "header-image") {
                if (this.headerImage instanceof File) {
                    formData.append("model_name", model_name);
                    formData.append("file", this.headerImage);
                }
            } else {
                if (this.headerSectionImage instanceof File) {
                    formData.append("model_name", model_name);
                    formData.append("file", this.headerSectionImage);
                }
            }

            await axios
                .post(`/api/uploadHeaderImage`, formData, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    if (model_name == "header-image") {
                        this.headerImage = response.data;
                    } else {
                        this.headerSectionImage = response.data;
                    }
                    this.isHeaderImageLoading = false;
                    this.$refs.SnackMessage.showMessage(
                        "settings.setting_saved",
                        "success"
                    );
                })
                .catch((error) => {
                    console.log(error);
                    this.$refs.SnackMessage.showMessage(
                        "general.error",
                        "error"
                    );
                });
        },

        async deleteImage(model_name) {
            await axios
                .delete("/api/deleteHeaderImage/" + model_name, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    if (model_name == "header-image") {
                        this.headerImage = null;
                    } else {
                        this.headerSectionImage = null;
                    }
                    this.isHeaderImageLoading = false;
                    this.$refs.SnackMessage.showMessage(
                        "settings.setting_saved",
                        "success"
                    );
                })
                .catch((error) => {
                    console.log(error);
                    this.$refs.SnackMessage.showMessage(
                        "general.error",
                        "error"
                    );
                });
        },
    },
};
</script>
