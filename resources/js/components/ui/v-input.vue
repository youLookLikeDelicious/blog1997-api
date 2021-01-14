<template>
  <div v-if="theme === 'inline'" class="v-input-inline relative-position">
    <input
      :name="name"
      :type="currentType"
      :placeholder="placeholder"
      v-model="currentValue"
      :autocomplete="autocomplete"
      :readonly="readonly"
      @blur="blurInput($event)"
      @focus="focus($event)"
    />
    <!-- <span
      v-if="this.type === 'password'"
      class="icofont-eye eye-btn"
      @mousedown="currentType = 'text'"
      @mouseup="currentType = type"
    ></span> -->
    <div class="hr-wrap">
      <hr />
    </div>
  </div>
  <div v-else :class="['v-input-box', 'relative-position', boxInputClass]">
    <span v-if="!currentValue" class="v-input-placeholder absolute-position">{{
      placeholder
    }}</span>
    <input
      v-model="currentValue"
      :readonly="readonly"
      :type="currentType"
      :name="name"
      @focus="focused = true"
      @blur="focused = false"
      :autocomplete="autocomplete"
    />
  </div>
</template>

<script>
export default {
  name: 'VInput',
  props: {
    placeholder: {
      type: String,
      default() {
        return ''
      },
    },
    val: {
      type: [String, Number],
      default() {
        return ''
      },
    },
    readonly: {
      type: Boolean,
      defautl() {
        return false
      },
    },
    type: {
      type: String,
      default() {
        return 'text'
      },
    },
    theme: {
      type: String,
      default() {
        return 'inline'
      },
    },
    name: {
      type: String,
      default () {
        return ''
      }
    },
    autocomplete: {
      type: String,
      default () {
        return 'on'
      }
    }
  },
  data() {
    return {
      currentValue: this.$attrs.vaue || this.val || '',
      currentType: this.type,
      focused: false,
      boxInputClass: '',
    }
  },
  created() {
    this.syncCurrentValue()
  },
  watch: {
    currentValue() {
      this.$emit('input', this.currentValue)
    },
    '$attrs.value'() {
      this.syncCurrentValue()
    },
    focused() {
      if (this.theme !== 'box') {
        return
      }
      this.boxInputClass = 'v-input-box-span-invisible'
      window.setTimeout(() => {
        this.boxInputClass = this.focused
          ? 'v-input-box-focused'
          : 'v-input-box-unfocused'
      }, 0)
    },
  },
  methods: {
    /**
     * input失去焦点事件
     * 
     * @param {Event} event
     */
    blurInput(event) {
      this.blur(event)
      this.$emit('blur', event)
    },
    /**
     * in-line theme 失去焦点动画
     * 
     * @param {Event} event
     */
    blur(event) {
      const hr = event.target.parentElement.querySelector('hr')
      this.$animate(hr, {
        width: '0px',
        duration: 230,
        easing: 'bezier(0.2, 0.2)',
        finish: true,
      })
    },
    /**
     * in-line theme input聚焦动画
     * 
     * @param {Event} event
     */
    focus(event) {
      const hr = event.target.parentElement.querySelector('hr')
      this.$animate(hr, { width: '100%', duration: 230, easing: 'bezier(0.2, 0.2)', finish: true })
    },
    syncCurrentValue() {
      if (this.$attrs.value !== this.currentValue) {
        this.currentValue = this.$attrs.value
      }
    },
  },
}
</script>

<style lang="scss">
%v-input {
  width: 100%;
  height: 2.7rem;
  padding-left: 0.3rem;
}
.v-input-inline {
  width: 23rem;
  input {
    @extend %v-input;
    border-bottom: 0;
    background-color: transparent;
    border: 0;
    border-bottom: 0.1rem solid #c6c6c6;
    box-sizing: border-box;
    outline: none;
  }
  .hr-wrap {
    width: 100%;
    margin-top: -0.1rem;
    hr {
      width: 0;
      background-color: #6cacff;
      border: 0;
      height: 0.2rem;
      margin: 0 auto;
    }
  }
  .eye-btn {
    position: absolute;
    top: 1rem;
    right: 2rem;
    color: #666;
    font-size: 1.6rem;
    cursor: pointer;
    &:hover {
      color: #999;
    }
    &:active {
      color: #333;
    }
  }
}
.v-input-box {
  border: 0.1rem solid #c6c6c6;
  box-sizing: border-box;
  border-radius: 0.3rem;
  width: 23rem;
  transition: border-color 0.3s;
  input {
    @extend %v-input;
    border: 0;
    box-sizing: border-box;
  }
  .v-input-placeholder {
    color: #999;
  }
  span {
    background-color: #fff;
    left: 0.5rem;
    top: 0.3rem;
    padding: 0, 0.3rem;
  }
}
.v-input-box-unfocused {
  span {
    animation: transform-placeholder 0.3s reverse forwards;
  }
}
.v-input-box-focused {
  border-color: #3490dc;
  span {
    animation: transform-placeholder 0.3s forwards;
  }
}
.v-input-box-span-invisible {
  span {
    visibility: hidden;
  }
}
@keyframes transform-placeholder {
  to {
    top: -1.2rem;
    left: -0.5rem;
    z-index: 2;
    transform: scale(0.7, 0.7);
  }
}
</style>