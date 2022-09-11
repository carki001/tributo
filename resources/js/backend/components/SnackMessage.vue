<template>
    <v-snackbar v-model="show" :timeout="timeout" :color="color" top>
        {{ text }}
        <v-btn text @click.stop="show = false">{{ $t("general.close") }}</v-btn>
    </v-snackbar>
</template>

<script>
export default {
    name: "SnackMessage",

    data() {
        return {
            timeout: 3000,
            color: "",
            text: "",
            show: false
        };
    },

    methods: {
        showMessage(message, status, timeout = 3000) {
            this.timeout = timeout;
            this.color = status;
            this.text = this.$t(message);
            this.show = true;
        }
    }
};
</script>
