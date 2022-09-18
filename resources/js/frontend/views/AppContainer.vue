<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <router-link class="navbar-brand" :to="{ name: 'home' }"
                    ><img
                        src="/images/logo-admin.png"
                        alt="logo"
                        width="24"
                        height="24"
                        class="d-inline-block align-text-top me-2"
                    />{{ $t("title.brand") }}</router-link
                >
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link
                                class="nav-link active"
                                aria-current="page"
                                :to="{ name: 'home' }"
                                >{{ $t("title.home") }}</router-link
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <header v-if="isPageDataLoaded">
            <div v-lazy-container="{ selector: 'img' }">
                <img
                    :data-src="
                        pageData.headerImage.path +
                        '/' +
                        pageData.headerImage.name
                    "
                    :alt="pageData.headerImage.name"
                />
            </div>
            <div class="d-flex justify-content-center">
                <h1>{{ pageData.headerData.header_title }}</h1>
                <h2>{{ pageData.headerData.header_text }}</h2>
            </div>
        </header>
        <div id="page-content">
            <router-view></router-view>
        </div>
        <footer v-if="isPageDataLoaded" class="text-center pt-4">
            <img
                src="/images/logo-admin.png"
                alt="logo"
                width="24"
                height="24"
                class="d-inline-block align-text-top mb-4"
            />
            <h3>
                {{ pageData.footerData.footer_contact_title_1 }}
            </h3>
            <p>{{ pageData.generalData.general_footer_phone_1 }}</p>
            <h3>
                {{ pageData.footerData.footer_contact_title_2 }}
            </h3>
            <p>
                {{ pageData.generalData.general_footer_phone_2 }}
            </p>
            <p class="mt-5 mb-0">
                &copy;{{ pageData.generalData.general_company_name }}
            </p>
        </footer>
    </div>
</template>
<script>
export default {
    data() {
        return {
            pageData: null,
            isPageDataLoaded: false,
        };
    },

    created() {
        this.getHomeData();
    },

    methods: {
        async getHomeData() {
            await axios
                .get("/getHomeData")
                .then((response) => {
                    this.pageData = response.data;
                    if (!response.data.headerImage) {
                        this.pageData.headerImage = {
                            path: "/images",
                            name: "error.webp",
                        };
                    }
                    this.isPageDataLoaded = true;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
