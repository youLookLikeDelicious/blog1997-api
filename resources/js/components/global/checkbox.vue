<template>
  <!-- 三种状态的checkbox
   props clickEvent 单击事件
   props selectedState
   全部未选中 -1
   全选     true
   部分选中false
  -->
  <div class="xy-checkbox">
    <label>
      <input
        ref="xy-checkbox"
        type="checkbox"
        :value="value"
        @click="clickEvent($event)"
      />
      <!-- <input ref="xy-checkbox" type="checkbox" @click="clickCheckbox($event)"> -->
      <div v-if="selectedState !== -1" class="mat-chekbox-background">
        <svg
          v-if="selectedState === true"
          xml:space="preserve"
          class="mat-checkbox-checkmark"
          focusable="false"
          version="1.1"
          viewBox="0 0 24 24"
        >
          <path
            class="mat-checkbox-checkmark-path"
            d="M4.1,12.7 9,17.6 20.3,6.3"
            stroke-width="3px"
            fill="none"
            stroke="white"
          />
        </svg>
        <svg
          v-else
          xml:space="preserve"
          class="mat-checkbox-partial-checkmark"
          focusable="false"
          version="1.1"
          viewBox="0 0 24 24"
        >
          <path
            class="mat-checkbox-checkmark-path"
            d="M4.1,12.7 20.1,12.7Z"
            stroke-width="3px"
            fill="none"
            stroke="white"
          />
        </svg>
      </div>
    </label>
  </div>
</template>

<script>
export default {
  name: "XyChckbox",
  props: {
    clickEvent: {
      type: Function,
      default() {
        return () => {};
      },
    },
    selectedState: {
      type: [Number, Boolean],
      default() {
        return -1;
      },
    },
    value: {
      type: [String, Number],
      default() {
        return -1;
      },
    },
  },
  watch: {
    selectedState() {
      if (this.selectedState === -1 && this.$refs["xy-checkbox"].checked) {
        this.$refs["xy-checkbox"].checked = false;
      } else if (
        typeof this.selectedState === "boolean" &&
        !this.$refs["xy-checkbox"].checked
      ) {
        this.$refs["xy-checkbox"].checked = true;
      }
    },
  },
};
</script>

<style lang="scss">
.xy-checkbox {
  width: 3rem;
  height: 3rem;
  overflow: hidden;
  line-height: 3rem;
  box-sizing: border-box;
  text-align: center;
  vertical-align: middle;
  display: inline-block;
  font-family: Roboto, "Helvetica Neue", sans-serif;
  label {
    width: 1.4rem;
    height: 1.4rem;
    border: 0.1rem solid #787878;
    box-sizing: border-box;
    display: inline-block;
    border-radius: 0.2rem;
    position: relative;
    &:hover {
      border-color: #333;
      &::before {
        $before-box-size: 3rem;
        content: "";
        width: $before-box-size;
        height: $before-box-size;
        border-radius: $before-box-size;
        background-color: #1a8cff;
        position: absolute;
        left: -0.9rem;
        bottom: -1rem;
        opacity: 0.1;
      }
    }
    input {
      width: 0.1rem;
      height: 0.1rem;
      display: none;
    }
  }
  .mat-checkbox-checkmark,
  .mat-checkbox-partial-checkmark {
    position: absolute;
    top: 0;
    left: 0;
    fill: #fafafa;
  }
  .mat-chekbox-background {
    width: 100%;
    height: 100%;
    left: 0;
    top: 0rem;
    line-height: 100%;
    position: absolute;
    background-color: #1a8cff;
    text-align: center;
  }
}
</style>
