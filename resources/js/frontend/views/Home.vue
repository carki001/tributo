<template>
    <div v-if="isSectionsDataLoaded" class="container">
        <h2 class="text-center mb-5">{{ contentTile }}</h2>
        <div
            class="card home-section mb-5"
            v-lazy-container="{ selector: 'img' }"
            v-for="(section, index) in sections"
        >
            <img
                :data-src="
                    '/uploads/' + section.image.path + '/' + section.image.name
                "
                :alt="section.image.name"
                class="card-img-top"
            />
            <div class="card-body">
                <h5 class="text-center card-title">{{ section.title }}</h5>
                <p class="card-text">
                    {{ section.description }}
                </p>
                <div class="d-flex justify-content-center">
                    <router-link
                        :to="{
                            name: 'sectionDetail',
                            params: {
                                sectionAlias: section.id + '-' + section.alias,
                            },
                        }"
                        class="btn btn-primary"
                        >{{ $t("general.see_more") }}</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            sections: [],
            isSectionsDataLoaded: false,
            contentTile: "",
        };
    },

    created() {
        this.getHomeData();
    },

    methods: {
        async getHomeData() {
            await axios
                .get("/getSections")
                .then((response) => {
                    this.sections = response.data.sections;
                    this.contentTile = response.data.contentTile;
                    this.isSectionsDataLoaded = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
