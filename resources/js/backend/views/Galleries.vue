<template>
    <div>
        <section>
            <v-container>
                <v-sheet class="px-3 pt-3 pb-3" v-if="!sections.length">
                    <v-skeleton-loader
                        class="mx-auto"
                        type="table"
                    ></v-skeleton-loader>
                </v-sheet>
                <v-container v-else class="d-flex flex-wrap">
                    <v-card
                        v-for="(section, index) in sections"
                        :key="section.id"
                        class="mr-5 mb-5"
                    >
                        <v-card-title>
                            {{ section.title }}
                        </v-card-title>
                        <v-card-text>
                            <p>
                                <span
                                    ><strong>{{
                                        $t("galleries.basic_uri") + ":"
                                    }}</strong>
                                </span>
                                <span>{{ " " + section.basic_uri }}</span>
                            </p>
                        </v-card-text>
                        <v-card-actions>
                            <span>{{
                                $t("galleries.add_image_to_gallery")
                            }}</span>
                            <v-btn
                                small
                                color="success"
                                class="ml-3"
                                @click="prepareImage(index)"
                                :disabled="isAddingImage"
                                >{{ $t("general.add") }}
                            </v-btn>
                        </v-card-actions>
                        <div
                            v-for="(image, imageIndex) in section.images"
                            :key="image.image_id"
                            class="d-flex"
                        >
                            <v-file-input
                                small-chips
                                dense
                                prepend-icon="mdi-camera"
                                accept="image/*"
                                v-model="image.file"
                                @change="
                                    uploadImage(
                                        section.id,
                                        image.id,
                                        image.file,
                                        index,
                                        imageIndex
                                    )
                                "
                                :label="$t('galleries.section_image')"
                                :clearable="false"
                                :disabled="!!image.id"
                            >
                            </v-file-input>
                            <v-btn
                                small
                                class="ml-3 mr-3"
                                @click="
                                    deleteImage(image.id, index, imageIndex)
                                "
                                :disabled="isAddingImage && !!image.id"
                            >
                                <v-icon color="red"
                                    >mdi-trash-can-outline
                                </v-icon>
                            </v-btn>
                        </div>
                    </v-card>
                </v-container>
            </v-container>
        </section>

        <SnackMessage ref="SnackMessage" />
    </div>
</template>
<script>
import SnackMessage from "../components/SnackMessage";
export default {
    data() {
        return {
            sections: [],
            isGalleriesLoaded: false,
            isAddingImage: false,
        };
    },

    components: {
        SnackMessage,
    },

    created() {
        this.getGalleries();
    },

    methods: {
        snackMessage(msg, type) {
            this.$refs.SnackMessage.showMessage(msg, type);
        },
        async getGalleries() {
            await axios
                .get("/api/getSectionGalleries", {
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
                    this.isGalleriesLoaded = true;
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        },

        prepareImage(index) {
            this.sections[index].images.push({
                file: null,
                image_id:
                    String(index) +
                    "." +
                    String(this.sections[index].images.length),
            });
            Vue.set(this.sections, index, this.sections[index]);
            this.isAddingImage = true;
        },
        async uploadImage(sectionId, imageId, file, index, imageIndex) {
            let formData = new FormData();
            formData.append("section_id", sectionId);
            if (imageId) {
                formData.append("attachment_id", imageId);
            }
            if (file instanceof File) {
                formData.append("model_name", "section-gallery");
                formData.append("file", file);
            }
            await axios
                .post("/api/uploadSectionGalleryImage", formData, {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then((response) => {
                    this.$refs.SnackMessage.showMessage(
                        "galleries.image_saved",
                        "success"
                    );
                    this.sections[index].images[imageIndex] = response.data;
                    this.sections[index].images[imageIndex].file =
                        response.data;
                    this.$forceUpdate();
                    this.isAddingImage = false;
                })
                .catch((error) => {
                    console.log(error);
                    this.$emit("snackMessage", "general.error", "error");
                });
        },
        async deleteImage(id, index, imageIndex) {
            this.sections[index].images.splice(imageIndex, 1);
            Vue.set(this.sections, index, this.sections[index]);

            if (id) {
                await axios
                    .delete(`/api/deleteSectionGalleryImage/${id}`, {
                        headers: {
                            Authorization:
                                "Bearer " +
                                this.$store.state.tokenData.user.access_token,
                        },
                    })
                    .then((response) => {
                        this.$refs.SnackMessage.showMessage(
                            "galleries.deleted_successfully",
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
            }
            this.isAddingImage = false;
        },
    },
};
</script>
