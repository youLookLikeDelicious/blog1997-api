export default {
    methods: {
        /**
         * 加载动画
         */
        enter($el, done) {
            this.$animate($el, {
                opacity: 1,
                "left": "2rem",
                "top": "1rem",
                easing: "bezier(0.6)",
                duration: 200,
            }, done);
        },
        /**
         * 注销动画
         */
        leave($el, done) {
            this.$animate(
                $el, {
                    opacity: 0,
                    "left": "-3rem",
                    "top": "-2rem",
                    easing: "bezier(0.6)",
                    duration: 200,
                },
                done
            );
        },
        /**
         * 提交数据
         */
        submit() {
            if (this.allowSubmit) {
                this.$emit("toggleComponent");
                this.$emit('create', this.$json2FormData(this.model))
            }
        },
        /**
         * 注销该组件
         */
        toggleComponent() {
            this.$emit("toggleComponent");
        },
    },
}
