<template>
    <v-dialog v-model="show" max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline" v-if="isEdited">{{
                    $t("sections.edit_section")
                }}</span>
                <span class="headline" v-else>{{
                    $t("sections.add_section")
                }}</span>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12">
                                <v-text-field
                                    v-model="section.title"
                                    :label="$t('sections.title')"
                                    autofocus
                                    :rules="rules"
                                ></v-text-field>
                                <v-text-field
                                    v-model="section.alias"
                                    :label="$t('sections.alias')"
                                    disabled
                                ></v-text-field>
                                <v-textarea
                                    v-model="section.description"
                                    :label="$t('sections.description')"
                                    :rules="rulesTextLength"
                                >
                                </v-textarea>
                                <v-text-field
                                    v-model="section.position"
                                    type="number"
                                    min="1"
                                    step="1"
                                    style="width: 25%"
                                    :label="$t('sections.position')"
                                    :rules="numberRules"
                                ></v-text-field>
                                <v-file-input
                                    chips
                                    prepend-icon="mdi-camera"
                                    accept="image/*"
                                    ref="attachFileInput"
                                    v-model="section.file"
                                    :label="$t('sections.main_image')"
                                ></v-file-input>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark @click="submit">{{
                    $t("general.save")
                }}</v-btn>
                <v-btn @click.stop="show = false">{{
                    $t("general.cancel")
                }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "SectionForm",
    props: {
        section: Object,
        visible: Boolean,
        isEdited: Boolean,
        maxPosition: Number,
    },

    data() {
        return {
            valid: true,
            showEye: false,
            rules: [(value) => !!value || this.$t("general.required")],

            numberRules: [
                (value) =>
                    (!!value && /^[1-9][0-9]*$/.test(value)) ||
                    this.$t("general.number_must_be_natural"),
                (value) =>
                    (this.isEdited
                        ? parseInt(value) <= this.maxPosition
                        : parseInt(value) <= this.maxPosition + 1) ||
                    this.$t("general.position_too_large"),
            ],

            rulesTextLength: [
                (value) => !!value || this.$t("general.required"),
                (value) =>
                    /^.{0,500}$/.test(value) ||
                    this.$t("general.text_should_be_less_500"),
            ],
            file: null,
        };
    },

    watch: {
        show(val) {
            !val && this.$refs.form.reset();
        },

        alias: function (val) {
            this.section.alias = val;
        },
    },

    computed: {
        show: {
            get() {
                return this.visible;
            },
            set(value) {
                if (!value) {
                    this.section.file = null;
                    this.$emit("close");
                }
            },
        },
        alias: function () {
            return this.section.title
                ? this.section.title
                      .toString() // Cast to string (optional)
                      .normalize("NFKD") // The normalize() using NFKD method returns the Unicode Normalization Form of a given string.
                      .toLowerCase() // Convert the string to lowercase letters
                      .trim() // Remove whitespace from both sides of a string (optional)
                      .replace(/\s+/g, "-") // Replace spaces with -
                      .replace(/_/g, "-") // Replace _ with -
                      .replace(/[^\w\-]+/g, "") // Remove all non-word chars
                      .replace(/\-\-+/g, "-")
                : ""; // Replace multiple - with single -
        },
    },

    methods: {
        async submit() {
            if (this.$refs.form.validate()) {
                let formData = new FormData();
                Object.entries(this.section).forEach(([key, value]) => {
                    if (value) {
                        formData.append(key, value);
                    } else {
                        formData.append(key, "");
                    }
                });
                if (this.section.file instanceof File) {
                    formData.append("model_name", "home-section-image");
                    formData.append("file", this.section.file);
                } else if (this.section.file && this.section.file.id) {
                    formData.append("file", "keep_same_file");
                } else {
                    formData.append("file", "none");
                }
                if (this.isEdited) {
                    await axios
                        .post(`/api/updateSection`, formData, {
                            headers: {
                                Authorization:
                                    "Bearer " +
                                    this.$store.state.tokenData.user
                                        .access_token,
                            },
                        })
                        .then((response) => {
                            if (response.data.isError) {
                                this.$emit(
                                    "snackMessage",
                                    response.data.message,
                                    "warning"
                                );
                                this.$refs.email.focus();
                            } else {
                                this.$emit("updateSection", response.data);
                                this.$emit(
                                    "snackMessage",
                                    "sections.section_saved",
                                    "success"
                                );

                                this.section.file = null;
                                this.$refs.form.reset();
                                this.$emit("close");
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$emit(
                                "snackMessage",
                                "general.error",
                                "error"
                            );
                        });
                } else {
                    await axios
                        .post(`/api/storeSection`, formData, {
                            headers: {
                                Authorization:
                                    "Bearer " +
                                    this.$store.state.tokenData.user
                                        .access_token,
                            },
                        })
                        .then((response) => {
                            if (response.data.isError) {
                                this.$refs.email.focus();
                                this.$emit(
                                    "snackMessage",
                                    response.data.message,
                                    "warning"
                                );
                            } else {
                                this.$emit("addSection", response.data);
                                this.$emit(
                                    "snackMessage",
                                    "sections.section_added",
                                    "success"
                                );
                                this.$refs.form.reset();
                                this.$emit("close");
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$emit(
                                "snackMessage",
                                "general.error",
                                "error"
                            );
                        });
                }
            }
        },
    },
};
</script>
