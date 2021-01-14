export default {
    data() {
        return {
            strength: 0
        }
    },
    methods: {
        /**
         * 计算密码的强度
         */
        checkPasswordStrength() {
            if (!this.model.password.length) {
                return this.strength = 0
            }
            if (this.model.password.length < 8) {
                return (this.strength = 1);
            }

            const type = this.calculateStringType(this.model.password);

            this.strength = this.calculateLevel(type);
        },
        /**
         * 计算字符串包含的特殊字符的种类
         *
         * @param {string} str
         * @return {integer}
         */
        calculateStringType(str) {
            let type = 0;
            for (let i = 0, len = str.length; i < len; i++) {
                const charCode = str.charCodeAt(i);

                if (48 <= charCode && charCode <= 57) {
                    type |= 1;
                } else if (
                    (65 <= charCode && charCode <= 90) ||
                    (97 <= charCode && charCode <= 122)
                ) {
                    type |= 2;
                } else {
                    type |= 4;
                }
            }
            return type;
        },
        /**
         * 计算密码的强度
         *
         * @param {int} type
         * @return {int}
         */
        calculateLevel(type) {
            let level = 0;
            do {
                if (type & 1) {
                    ++level;
                }
                type = type >>> 1;
            } while (type);

            return level;
        },
        /**
         * 比较两次输入的密码
         */
        comparePassword() {
            const model = this.model;
            if (model.password && this.model.password_confirmation &&
                model.password !== model.password_confirmation
            ) {
                this.passwordConfirmError = "输入的密码不一致";
            } else if (this.passwordConfirmError) {
                this.passwordConfirmError = "";
            }
        },
    },
    watch: {
        "model.password_confirmation"() {
            this.comparePassword();
        },
        "model.password"() {
            this.comparePassword();
            this.checkPasswordStrength()
        },
    },
}
