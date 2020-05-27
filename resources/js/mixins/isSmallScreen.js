import {debounce} from "lodash";

export const isSmallScreen = {
    data: {
        configServer: {
            isSmallScreen: false,
        }
    },
    methods: {
        checkIsSmallScreen: debounce(function() {
            this.isSmallScreen = window.innerWidth <= 768;
        }, 200),
    },
    computed: {
        isSmallScreen: {
            set(val) {
                this.$root.configServer.isSmallScreen = val;
            },
            get() {
                return this.$root.configServer.isSmallScreen;
            },
        },
    },
    mounted: function() {
        this.checkIsSmallScreen();
        window.addEventListener('resize', this.checkIsSmallScreen);
    },
    beforeDestroy: function() {
        window.removeEventListener('resize', this.checkIsSmallScreen);
    },
};
