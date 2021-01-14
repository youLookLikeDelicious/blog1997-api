<template>
  <div
    :class="[
      'v-switch',
      'relative-position',
      { 'v-switch-off': !selected },
      { 'v-switch-disable': disable },
    ]"
    :title="currentValue"
    @click="toggleSwitch"
  ></div>
</template>

<script>
export default {
  name: "VSwitch",
  props: {
    leftValue: {
      type: [String, Boolean],
      default () {
        return true
      }
    },
    rightValue: {
      type: [String, Boolean],
      default () {
        return false
      }
    },
    disable: {
      type: Boolean,
      default() {
        return false;
      },
    },
  },
  data() {
    return {
      currentValue: this.$attrs.value
    };
  },
  computed: {
    selected () {
      return this.currentValue === this.leftValue
    }
  },
  watch: {
    "$attrs.value"() {
      if (this.$attrs.value !== this.currentValue) {
        this.currentValue = this.$attrs.value;
      }
    },
  },
  methods: {
    toggleSwitch() {
      if (this.disable) {
        return;
      }
      
      this.currentValue = this.currentValue === this.leftValue ? this.rightValue : this.leftValue
      this.$emit("input", this.currentValue);
      this.$emit("click");
    },
  },
};
</script>

<style lang="scss">
.v-switch {
  width: 5rem;
  height: 2rem;
  border-radius: 1.7rem;
  background-color: #409eff;
  box-sizing: border-box;
  &:after {
    $size: 1.6rem;
    content: "";
    top: 0.2rem;
    width: $size;
    height: $size;
    border-radius: $size;
    box-sizing: border-box;
    position: absolute;
    left: 0.3rem;
    background-color: #f7fafb;
    transition: left 0.3s;
  }
}
.v-switch-off {
  background-color: #dcdfe6;
  &:after {
    left: 3rem;
  }
}
.v-switch-disable {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>