<template>
    <v-app id="app-content">
        <v-navigation-drawer :mini-variant="primaryDrawer.mini" app clipped>
            <v-list>
                <router-link
                    class="d-block text-decoration-none"
                    :to="{ name: 'sections' }"
                >
                    <v-tooltip right v-if="primaryDrawer.mini">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <div class="ap-list-button">
                                    <v-icon>mdi-format-indent-increase</v-icon>
                                </div>
                            </v-list-item>
                        </template>
                        <span>{{ $t("title.sections") }}</span>
                    </v-tooltip>
                    <v-list-item link v-else>
                        <v-list-item-action>
                            <v-icon>mdi-format-indent-increase</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{
                                $t("title.sections")
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
                <router-link
                    class="d-block text-decoration-none"
                    :to="{ name: 'galleries' }"
                >
                    <v-tooltip right v-if="primaryDrawer.mini">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <div class="ap-list-button">
                                    <v-icon
                                        >mdi-panorama-variant-outline</v-icon
                                    >
                                </div>
                            </v-list-item>
                        </template>
                        <span>{{ $t("title.galleries") }}</span>
                    </v-tooltip>
                    <v-list-item link v-else>
                        <v-list-item-action>
                            <v-icon>mdi-panorama-variant-outline</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{
                                $t("title.galleries")
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
                <label
                    class="py-2 pl-4 d-block mt-3"
                    v-if="$store.state.profile.role === 'admin'"
                >
                    <v-icon v-if="primaryDrawer.mini">mdi-cog-outline</v-icon>
                    <span v-else>{{ $t("title.settings") }}</span>
                </label>

                <router-link
                    class="d-block text-decoration-none"
                    :to="{ name: 'users' }"
                    v-if="$store.state.profile.role === 'admin'"
                >
                    <v-tooltip right v-if="primaryDrawer.mini">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <div class="ap-list-button">
                                    <v-icon>mdi-account-multiple</v-icon>
                                </div>
                            </v-list-item>
                        </template>
                        <span>{{ $t("title.users") }}</span>
                    </v-tooltip>
                    <v-list-item link v-else>
                        <v-list-item-action>
                            <v-icon>mdi-account-multiple</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{
                                $t("title.users")
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
                <router-link
                    class="d-block text-decoration-none"
                    :to="{ name: 'settings' }"
                    v-if="$store.state.profile.role === 'admin'"
                >
                    <v-tooltip right v-if="primaryDrawer.mini">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <div class="ap-list-button">
                                    <v-icon
                                        >mdi-order-bool-ascending-variant</v-icon
                                    >
                                </div>
                            </v-list-item>
                        </template>
                        <span>{{ $t("title.settings_general") }}</span>
                    </v-tooltip>
                    <v-list-item link v-else>
                        <v-list-item-action>
                            <v-icon>mdi-order-bool-ascending-variant</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{
                                $t("title.settings_general")
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app clipped-left dark color="primary">
            <v-app-bar-nav-icon
                @click.stop="primaryDrawer.mini = !primaryDrawer.mini"
            />
            <v-toolbar-title>
                <img align="middle" src="/images/logo-admin.png" height="25" />
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip bottom v-if="$store.state.profile.role === 'admin'">
                <template v-slot:activator="{ on, attrs }">
                    <v-icon v-bind="attrs" v-on="on" color="yellow" class="mr-2"
                        >mdi-crown-outline</v-icon
                    >
                </template>
                <span>{{ $t("general.wau_you_are_admin") }}</span>
            </v-tooltip>
            {{ $store.state.profile.email }}
            <v-spacer></v-spacer>
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        to="/"
                        target="_blank"
                        v-bind="attrs"
                        v-on="on"
                        icon
                        class="text-decoration-none"
                    >
                        <v-icon>mdi-laptop</v-icon>
                    </v-btn>
                </template>
                <span>{{ $t("general.frontend") }}</span>
            </v-tooltip>
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                        @click="logout"
                        color="deep-orange accent-3"
                    >
                        <v-icon>mdi-logout-variant</v-icon>
                    </v-btn>
                </template>
                <span>{{ $t("general.sign_out") }}</span>
            </v-tooltip>
        </v-app-bar>

        <v-main>
            <router-view></router-view>
        </v-main>

        <v-footer app color="white">
            <span>
                &copy; 2022
                <a
                    href="https://www.crunchbase.com/organization/sv388-situs-judi-live-sabung-ayam-online-sv388-terpercaya"
                    target="_blank"
                    >Fake Co.</a
                >
            </span>
        </v-footer>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        primaryDrawer: {
            mini: false,
        },
    }),

    methods: {
        logout() {
            axios
                .get("/api/auth/logout", {
                    headers: {
                        Authorization:
                            "Bearer " +
                            this.$store.state.tokenData.user.access_token,
                    },
                })
                .then(() => {
                    localStorage.removeItem("own-spa-token");
                    this.$store.dispatch("resetState");
                    this.$router.push("/login");
                });
        },
        test() {
            console.log("testtt");
        },
    },
};
</script>
