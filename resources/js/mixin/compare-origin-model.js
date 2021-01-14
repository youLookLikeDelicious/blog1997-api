export default {
    methods: {
        checkModelIsDirty() {
            // 是创建操作，无需进行判断
            if (!this.originModel.id) {
                return true
            }

            for (const key in this.model) {
                const [originValue, currentValue] = [this.originModel[key], this.model[key]]
                if (currentValue instanceof Array) {
                    if (! this.$compareArray(currentValue, originValue)) {
                        return true
                    }
                } else if (currentValue !== originValue) {
                    return true;
                }
            }

            return false;
        }
    }
}
