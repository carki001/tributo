<template>
    <div v-if="isSectionDataLoaded" class="container">
        <h2 class="text-center mb-5">{{ galleryContentTitle }}</h2>
        <Tinybox v-model="index" :images="section.gallery"></Tinybox>
        <div
            class="section-gallery d-flex flex-wrap justify-content-center"
            v-lazy-container="{ selector: 'img' }"
        >
            <img
                v-for="(image, thumbnailIndex) in section.gallery"
                :data-src="
                    '/uploads/' + image.path + '/' + 'small_' + image.name
                "
                :alt="image.name"
                class="img-thumbnail mb-3 me-3"
                @click="index = thumbnailIndex"
            />
        </div>
    </div>
</template>
<script>
import Tinybox from "vue-tinybox";
export default {
    data() {
        return {
            section: {},
            isSectionDataLoaded: false,
            galleryContentTitle: "",
            index: null,
        };
    },

    components: {
        Tinybox,
    },

    created() {
        this.getSectionDetails();
    },

    methods: {
        async getSectionDetails() {
            await axios
                .get(
                    "/getSectionDetails" + "/" + this.$route.params.sectionAlias
                )
                .then((response) => {
                    this.section = response.data.section
                        ? response.data.section
                        : {};
                    this.galleryContentTitle =
                        response.data.galleryContentTitle;
                    this.isSectionDataLoaded = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
