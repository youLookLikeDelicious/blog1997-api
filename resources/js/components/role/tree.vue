<template>
  <transition v-on:enter="enter" v-on:leave="leave">
    <ul class="tree-list" v-show="visibility">
      <li
        v-for="(leave, index) in tree"
        :key="index"
        :style="{
          'padding-left': deepth * 2 + 'em',
        }"
      >
        <div class="tree-node">
          <a
            v-if="leave.children"
            @click.prevent.stop
            @click="toggleLeave(index)"
            href="/"
          >
            <i
              :class="[
                'icon',
                'icofont-caret-down',
                { 'list-slide-down': !isVisible(index) },
                { 'list-slide-up': isVisible(index) },
              ]"
            ></i
          ></a>
          <span v-else class="no-children"></span>
          <v-checkbox
            :value="leave.auth_path"
            :clickEvent="clickCheckbox"
            :selected-state="computeSelectState(leave)"
          />
          <span>{{ leave.name }}</span>
        </div>

        <auth-tree
          v-if="leave.children"
          :tree="leave.children"
          :visibility="isVisible(index)"
          :deepth="deepth + 1"
        ></auth-tree>
      </li>
    </ul>
  </transition>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: "AuthTree",
  props: {
    tree: {
      type: Array,
      default() {
        return [];
      },
    },
    /**
     * 递归的深度
     */
    deepth: {
      type: [Number, String],
      default() {
        return 0;
      },
    },
    /**
     * 是否显示子元素
     */
    visibility: {
      type: Boolean,
      default() {
        return true;
      },
    },
  },
  data() {
    return {
      visibleLeaveStack: [], // 保存子树的显隐状态
    };
  },
  computed: {
    ...mapState({
      selectedList: state => state.auth.selectedList
    })
  },
  methods: {
    /**
     * 展开或隐藏子元素
     * 
     * @param {int} index
     * @return {void}
     */
    toggleLeave(index) {
      const stackIndex = this.visibleLeaveStack.indexOf(index);
      // 加入链表
      if (stackIndex === -1) {
        this.visibleLeaveStack.push(index);
      } else {
        // 从链表中删除
        this.visibleLeaveStack.splice(stackIndex, 1);
      }
    },
    /**
     * 判断是否显示子元素
     * 
     * @return {boolearn}
     */
    isVisible(index) {
      return this.visibleLeaveStack.indexOf(index) >= 0;
    },
    /**
     * 显示子树的动画
     * 
     * @param {HTMLElement} el
     * @param {Function} done
     * @return {void}
     */
    enter(el, done) {
      this.$animate(el, { ani: "slideDown", duration: 168 }, (el) => {
        el.style.height = "fit-content";
        done();
      });
    },
    /**
     * 隐藏子树的动画
     * 
     * @param {HTMLElement} el
     * @param {Function} done
     * @return {void}
     */
    leave(el, done) {
      this.$animate(el, { ani: "slideUp", duration: 168 }, done);
    },

    /**
     * 点击选中事件
     * 
     * @param {Event} event
     */
    clickCheckbox(event) {
      this.$store.commit("auth/toggleAuth", event.target.value);
    },

    /**
     * 判断子元素是否是选中状态
     */
    computeSelectState (leave) {
      const exists = this.selectedList.indexOf(leave.id) >= 0
      
      if (exists) {
        return exists
      }

      const subAuthIds = this.$store.getters['auth/getSubAuthIds'](leave.auth_path)
      
      for (let i = 0, len = subAuthIds.length; i < len; i++) {
        if (this.selectedList.includes(subAuthIds[i])) {
          return false
        }
      }

      return -1
    }
  },
};
</script>

<style lang="scss">
.tree-list {
  list-style: none;
  overflow: hidden;
  li {
    margin: 0.3rem 0;
  }
  .tree-node {
    padding: 0.3rem;
    height: 3rem;
    box-sizing: border-box;
    line-height: 3rem;
    &:hover {
      background-color: $hover-color;
    }
  }
  .icon {
    display: inline-block;
    transition: transform 0.5s;
    width: 2rem;
    height: 2rem;
    line-height: 2rem;
    font-size: 1.6rem;
    text-align: center;
    box-sizing: border-box;
    color: #c0c4cc;
  }
  .list-slide-down {
    transform: rotate(-90deg);
  }
  .list-slide-up {
    transform: rotate(0);
  }
  .no-children {
    width: 2rem;
    height: 2rem;
    margin-right: 0.7rem;
    display: inline-block;
  }
}
</style>