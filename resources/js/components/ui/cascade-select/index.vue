<template>
  <div class="cascade-select">
    <div>
      <div
        ref="input-wrapper"
        :class="['input-wrapper', { focus: focus }]"
        @click="focusSelect"
        :style="{ width }"
      >
        <div
          type="text"
          class="selected-option"
          tabindex="0"
          @focus="focusSelect"
          @blur="blurSelect"
        >
          {{ inputValue }}
        </div>
      </div>
    </div>
    <div class="relative-position max-width">
      <div
        data-display="flex"
        ref="options"
        class="inline-block options-wrapper"
      >
        <ul v-for="(options, index) in visibleOptions" :key="index">
          <li
            v-for="(item, index) in options"
            :data-path="item.path"
            :key="index"
            :class="{
              'no-pointer': item.id === false && !allowCreate,
              on: selectPathStack.indexOf(item.id) !== -1,
            }"
            @click="select"
            @mouseenter="enterSelect"
          >
            {{ item.name }} <span>{{ item.children ? "&gt;" : "" }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CascadeSelect",
  props: {
    /**
     * 选项列表
     */
    options: {
      type: Array,
      default() {
        return [];
      },
    },
    width: {
      type: String,
      default() {
        return "21rem";
      },
    },
    placeholder: {
      type: String,
      default() {
        return "";
      },
    },
  },
  data() {
    return {
      selectPathStack: [],
      focus: false,
      inputValue: this.placeholder,
    };
  },
  computed: {
    /**
     * 显示的选项列表
     */
    visibleOptions() {
      const result = [];
      const topLevel = this.options.map(this.optionMapper);

      result.push(topLevel);
      if (!this.selectPathStack.length) {
        return result;
      }

      let i = 0;
      let currentOption = this.options.find(
        (item) => item.id === this.selectPathStack[i]
      );
      while (currentOption && currentOption.children) {
        ++i;
        result.push(currentOption.children.map(this.optionMapper));
        currentOption = currentOption.children.find(
          (item) => item.id === this.selectPathStack[i]
        );
      }

      return result;
    },
  },
  watch: {
    /**
     * 监听绑定的数值
     */
    "$attrs.value"() {
      if (isNaN(this.$attrs.value)) {
        this.inputValue = this.placeholder;
        return;
      }
      
      this.calculateSelectedItem();
    },
    options() {
      if (!this.selectPathStack.length && !isNaN(this.$attrs.value)) {
        this.calculateSelectedItem();
      }
    },
  },
  methods: {
    /**
     * 选中选项操作
     * @param {string} path
     */
    select(event) {
      const currentId = this.selectPathStack[this.selectPathStack.length - 1]
      this.$emit(
        "input",
        currentId
      );
    },
    /**
     * 鼠标移入Li (选项的事件)
     */
    enterSelect(event) {
      const path = event.target.dataset.path;
      // if (path === this.selectPath) {
      //   return
      // }
      const arr = this.splitPath(path);
      this.selectPathStack = this.splitPath(path);
    },
    /**
     * 分离路径
     * @param {string} path
     * @return {array}
     */
    splitPath(path) {
      const arr = path.slice(0, -1).split("_");
      return arr.map((item) => parseInt(item));
    },
    /**
     * 遍历option
     */
    optionMapper(option) {
      return {
        id: option.id,
        name: option.name,
        path: option.auth_path,
        children: "undefined" !== typeof option.children,
      };
    },
    /**
     * 聚焦组件事件
     */
    focusSelect() {
      if (this.focus) {
        return;
      }
      this.focus = true;
      this.showOptions();
    },
    /**
     * 取消组件的选中状态
     */
    blurSelect(event) {
      if (
        this.$checkElementAncestor(event.target, this.$refs.options) ||
        this.$checkElementAncestor(event.target, this.$refs["input-wrapper"]) ||
        !this.focus
      ) {
        return;
      }
      this.focus = false;
      this.$animate(
        this.$refs.options,
        { ani: "slideUp", duration: 160 },
        (el) => (el.style.display = "none")
      );
      window.removeEventListener("click", this.blurSelect);
    },
    /**
     * 显示选项列表
     */
    showOptions() {
      this.$animate(
        this.$refs.options,
        { ani: "slideDown", duration: 160 },
        () => window.addEventListener("click", this.blurSelect)
      );
    },
    /**
     * 生成被选中的元素
     */
    calculateSelectedItem() {
      let currentOption = this.getCurrentOption(this.options)
      if (!currentOption) {
        this.inputValue = this.placeholder
        return
      }
      
      let inputValue = []
      while (currentOption) {
        inputValue.push(currentOption.name)
        currentOption = currentOption.parent
      }

      inputValue.reverse()
      
      this.inputValue = inputValue.join(' / ')
    },
    /**
     * 递归算法
     * 获取父级权限
     */
    getCurrentOption (options) {
      let target
      for (let i = 0, len = options.length; i < len; i++) {
        if (options[i].id === this.$attrs.value) {
          return options[i]
        }

        if (options[i].children) {
          target = this.getCurrentOption(options[i].children)
        }
        if (target) {
          return target
        }
      }

      return false
    }
  },
};
</script>

<style lang="scss">
.cascade-select {
  box-sizing: border-box;
  .input-wrapper {
    border: 0.1rem solid #e6e6e6;
  }
  .options-wrapper {
    margin-top: 1rem;
    display: none;
    width: fit-content;
    position: absolute;
    border-radius: 0.3rem;
    border: 0.1rem solid #e6e6e6;
    z-index: 99;
    background-color: #fff;
    box-shadow: 0rem 0rem 0.5rem 0.2rem rgba(200, 200, 200, 0.3);
    ul {
      display: block;
      width: auto;
      overflow-y: auto;
      list-style: none;
      border-radius: 0.3rem;
      border-right: 0.1rem solid #e6e6e6;
      @extend %scroll-bar-min;
      &:last-child {
        border-right: 0;
      }
    }
    li {
      display: flex;
      cursor: pointer;
      justify-content: space-between;
      padding: 1rem 0rem 1rem 2rem;
    }
    span {
      margin-left: 5rem;
      margin-right: 1rem;
    }
    .on {
      color: #409eff;
      background-color: #f5f7fa;
    }
  }
  .focus {
    border-color: #409eff;
  }
  .selected-option {
    padding: 0.5rem 0.7rem;
    box-sizing: border-box;
    width: 100%;
    height: 2.5rem;
    line-height: 1.5rem;
    color: #777;
    outline: none;
    white-space: nowrap;
  }
}
</style>