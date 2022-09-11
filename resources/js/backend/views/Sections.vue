<template>
    <div>
        <header>
            <v-container>
                <v-row no-gutters>
                    <h1 class="font-weight-light headline">
                        {{ $t("title.sections") }}
                    </h1>
                    <v-spacer></v-spacer>
                    <v-lazy
                        v-if="isSectionsLoaded"
                        transition="fade-transition"
                    >
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            :label="searchLabel"
                            single-line
                            dense
                            hide-details
                            style="max-width: 200px"
                            class="mr-5 pr-5"
                        ></v-text-field>
                    </v-lazy>
                    <v-lazy
                        v-if="isSectionsLoaded"
                        transition="fade-transition"
                    >
                        <v-btn
                            color="success"
                            dark
                            small
                            @click.stop="prepareSection"
                            >{{ $t("general.add") }}</v-btn
                        >
                    </v-lazy>
                </v-row>
            </v-container>
        </header>
        <section class="py-7">
            <v-container>
                <v-sheet class="px-3 pt-3 pb-3" v-if="!sections.length">
                    <v-skeleton-loader
                        class="mx-auto"
                        type="table"
                    ></v-skeleton-loader>
                </v-sheet>
                <v-card v-else>
                    <v-data-table
                        :headers="headers"
                        :items="sections"
                        :search="search"
                        :items-per-page="15"
                    >
                        <template v-slot:item.action="{ item }">
                            <div style="display: flex">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-tr-class-on-hover="'blue'"
                                            size="20px"
                                            color="blue"
                                            class="mr-2"
                                            @click="editSection(item)"
                                            v-on="on"
                                            >mdi-pencil-outline</v-icon
                                        >
                                    </template>
                                    <span>{{ $t("general.edit") }}</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            size="22px"
                                            color="red"
                                            v-tr-class-on-hover="'red'"
                                            class="mr-2"
                                            @click="confirmDelete(item)"
                                            v-on="on"
                                            >mdi-delete-outline</v-icon
                                        >
                                    </template>
                                    <span>{{ $t("general.delete") }}</span>
                                </v-tooltip>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>

                <SectionForm
                    :section="selectedSection"
                    :visible="dialog.sectionForm"
                    :isEdited="isSectionEdited"
                    :maxPosition="maxPosition"
                    @addSection="addSection"
                    @updateSection="updateSection"
                    @close="dialog.sectionForm = false"
                    @snackMessage="snackMessage"
                />

                <ConfirmationDialog
                    :name="selectedSection.alias"
                    :text="'sections.delete_confirmation'"
                    v-if="dialog.confirmation"
                    @confirm="deleteSection"
                    @cancel="cancelDelete"
                />

                <SnackMessage ref="SnackMessage" />
            </v-container>
        </section>
    </div>
</template>

<script>
import SectionForm from "../components/SectionForm";
import ConfirmationDialog from "../components/ConfirmationDialog";
import SnackMessage from "../components/SnackMessage";

export default {
    data() {
        return {
            sections: [],
            isSectionsLoaded: false,
            search: "",
            dialog: {
                confirmation: false,
                sectionForm: false,
            },

            selectedSection: {
                title: null,
                alias: null,
                description: null,
                position: null,
            },
            defaultSection: {
                title: null,
                alias: null,
                description: null,
                position: null,
            },
            isSectionEdited: false,
            maxPosition: 1,
        };
    },

    components: {
        SectionForm,
        ConfirmationDialog,
        SnackMessage,
    },

    computed: {
        headers() {
            return [
                {
                    text: this.$t("general.actions"),
                    value: "action",
                    sortable: false,
                },
                {
                    text: this.$t("sections.title"),
                    align: "left",
                    sortable: false,
                    value: "title",
                },
                {
                    text: this.$t("sections.alias"),
                    align: "left",
                    sortable: false,
                    value: "alias",
                },
                {
                    text: this.$t("sections.description"),
                    align: "left",
                    sortable: false,
                    value: "short_description",
                },
                {
                    text: this.$t("sections.position"),
                    align: "left",
                    sortable: false,
                    value: "position",
                },
                {
                    text: this.$t("general.created_at"),
                    align: "left",
                    sortable: false,
                    value: "created_at",
                },
            ];
        },
        searchLabel() {
            return this.$t("general.search");
        },
    },

    created() {
        this.getSections();
    },

    methods: {
        snackMessage(msg, type) {
            this.$refs.SnackMessage.showMessage(msg, type);
        },

        async getSections() {
            await axios
                .get("/api/sectionList", {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    this.sections = response.data.sections
                        ? response.data.sections
                        : [];
                    this.maxPosition = response.data.maxPosition;
                    this.defaultSection.position = this.maxPosition + 1;
                    this.isSectionsLoaded = true;
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        },

        prepareSection() {
            var newObject = JSON.stringify(this.defaultSection);
            this.selectedSection = JSON.parse(newObject);

            this.dialog.sectionForm = true;
            this.isSectionEdited = false;
        },
        addSection(section) {
            this.getSections();
        },
        editSection(item) {
            this.selectedSection = Object.assign({}, item);
            this.dialog.sectionForm = true;
            this.isSectionEdited = true;
        },
        updateSection(section) {
            this.getSections();
        },
        confirmDelete(item) {
            this.selectedSection = item;
            this.dialog.confirmation = true;
        },
        cancelDelete() {
            this.dialog.confirmation = false;
        },
        async deleteSection() {
            this.dialog.confirmation = false;
            let index = this.sections.indexOf(this.selectedSection);

            await axios
                .delete(`/api/deleteSection/${this.selectedSection.id}`, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    var newObject = JSON.stringify(this.defaultSection);
                    this.selectedSection = JSON.parse(newObject);

                    this.getSections();

                    this.$refs.SnackMessage.showMessage(
                        "sections.deleted_successfully",
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
        getMaxPosition() {
            axios
                .get("/api/getMaxPosition", {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    this.maxPosition = response.data;
                    this.defaultSection.position = this.maxPosition + 1;
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        },
    },
};
</script>
