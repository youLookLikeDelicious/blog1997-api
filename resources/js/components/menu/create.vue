<template>
  <v-dialog
    width="54rem"
    height="37rem"
    title="创建标签"
    @close="toggleCreateNewModel"
  >
    <table class="create-auth">
      <tr>
        <td class="required-feild">名称</td>
        <td>
          <div class="with-fit-content">
            <input
              class="v-input"
              v-model="authModel.name"
              type="text"
              placeholder="请填写权限名称"
              @blur="$blur($event)"
              @focus="$focus($event)"
            />
            <div class="hr-wrap">
              <hr />
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td class="required-feild">父级权限</td>
        <td>
          <v-select
            v-model="authModel.parent_id"
            :options="parentAuth"
            placeholder="请选择父级权限"
          ></v-select>
        </td>
      </tr>
      <tr>
        <td>图标</td>
        <td class="relative-position">
          <div ref="icon-list" class="absolute-position icon-list">
            <ul>
              <li class="icofont-dashboard" @click="selectIcon"></li>
              <li class="icofont-diamond" @click="selectIcon"></li>
              <li class="icofont-memorial" @click="selectIcon"></li>
              <li class="icofont-pixels" @click="selectIcon"></li>
              <li class="icofont-friendfeed" @click="selectIcon"></li>
              <li class="icofont-google-talk" @click="selectIcon"></li>
              <li class="icofont-weibo" @click="selectIcon"></li>
              <li class="icofont-tags" @click="selectIcon"></li>
              <li class="icofont-restaurant-menu" @click="selectIcon"></li>
              <li class="icofont-refresh" @click="selectIcon"></li>
              <li class="icofont-ui-theme" @click="selectIcon"></li>
              <li class="icofont-ui-calendar" @click="selectIcon"></li>
              <li class="icofont-tick-mark" @click="selectIcon"></li>
              <li class="icofont-fire-burn" @click="selectIcon"></li>
              <li class="icofont-home" @click="selectIcon"></li>
              <li class="icofont-rss-feed" @click="selectIcon"></li>
              <li class="icofont-share" @click="selectIcon"></li>
              <li class="icofont-safety" @click="selectIcon"></li>
              <li class="icofont-logout" @click="selectIcon"></li>
              <li class="icofont-navigation-menu" @click="selectIcon"></li>
              <li class="icofont-cloud-upload" @click="selectIcon"></li>
              <li class="icofont-qr-code" @click="selectIcon"></li>
              <li class="icofont-search-2" @click="selectIcon"></li>
              <li class="icofont-speech-comments" @click="selectIcon"></li>
              <li class="icofont-dolphin" @click="selectIcon"></li>
              <li class="icofont-alarm" @click="selectIcon"></li>
              <li class="icofont-quill-pen" @click="selectIcon"></li>
              <li class="icofont-attachment" @click="selectIcon"></li>
              <li class="icofont-archive" @click="selectIcon"></li>
              <li class="icofont-notification" @click="selectIcon"></li>
              <li class="icofont-settings-alt" @click="selectIcon"></li>
              <li class="icofont-lock" @click="selectIcon"></li>
              <li class="icofont-warning" @click="selectIcon"></li>
              <li class="icofont-email" @click="selectIcon"></li>
              <li class="icofont-ui-delete" @click="selectIcon"></li>
              <li class="icofont-image" @click="selectIcon"></li>
              <li class="icofont-mathematical" @click="selectIcon"></li>
              <li class="icofont-computer" @click="selectIcon"></li>
              <li class="icofont-ui-video-play" @click="selectIcon"></li>
              <li class="icofont-page" @click="selectIcon"></li>
              <li class="icofont-user-alt-5" @click="selectIcon"></li>
            </ul>
          </div>
          <a
            @click.prevent.stop
            @click="iconActive = !iconActive"
            href="/"
            class="btn-green-fade relative-position"
            >添加</a
          >
          <i
            v-if="authModel.icon"
            :class="authModel.icon + ' font-size-15'"
          ></i>
        </td>
      </tr>
      <tr>
        <td>URL</td>
        <td>
          <div class="with-fit-content">
            <input
              placeholder="请填写对应的路由"
              class="v-input url-input"
              v-model="authModel.url"
              type="text"
              @blur="$blur($event)"
              @focus="$focus($event)"
            />
            <div class="hr-wrap">
              <hr />
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>api路由名称</td>
        <td>
          <div class="flex">
            <v-select
              :options="methods"
              width="8rem"
              v-model="authModel.method"
            ></v-select>
            <div class="with-fit-content">
              <input
                placeholder="请填写对应的路由"
                class="v-input"
                v-model="authModel.route_name"
                type="text"
                @blur="$blur($event)"
                @focus="$focus($event)"
              />
              <div class="hr-wrap">
                <hr />
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>是否显示菜单</td>
        <td>
          <label for="">
            <input
              type="radio"
              name="show_menu"
              value="yes"
              v-model="authModel.show_menu"
            />
            是
          </label>
          <label for="">
            <input
              type="radio"
              name="show_menu"
              value="no"
              v-model="authModel.show_menu"
            />
            否
          </label>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <a
            @click.stop.prevent
            @click="submit"
            :class="{ btn: allowSubmit, 'btn-disable': !allowSubmit }"
            href="/"
            >提交</a
          >
        </td>
      </tr>
    </table>
  </v-dialog>
</template>

<script>
export default {
  name: "CreateAuth",
  props: {
    originAuthModel: {
      type: Object,
      default() {
        return {
          name: "",
          parent_id: "",
          url: "",
          icon: "",
          method: "GET",
          route_name: "",
          show_menu: "yes",
        };
      },
    },
    create: {
      default() {
        return () => {};
      },
    },
    toggleCreateNewModel: {
      default() {
        return () => {};
      },
    },
  },
  data() {
    return {
      authModel: {
        ...this.originAuthModel,
      },
      parentAuth: [],
      iconActive: false, // icon列表的状态
      // 请求的方法
      methods: ["GET", "POST", "PUT", "DELETE"],
    };
  },
  computed: {
    /**
     * 是否允许提交
     */
    allowSubmit() {
      // 修改状态，判断是否能够提交
      if (this.originAuthModel.id) {
        for (const key in this.authModel) {
          if (this.originAuthModel[key] !== this.authModel[key]) {
            return true;
          }
        }
        return false;
      }
      return this.authModel.name && parseInt(this.authModel.parent_id) >= 0;
    },
  },
  watch: {
    iconActive() {
      // 显示列表
      if (!this.iconActive) {
        this.hiddenIconList();
        return;
      }
      this.$animate(
        this.$refs["icon-list"],
        {
          ani: "slideDown",
          duration: 200,
          top: "-11rem",
        },
        (el) => {
          el.style.overflow = "visible";
          window.addEventListener("click", this.hiddenIconList);
        }
      );
    },
  },
  created() {
    this.getParentAuth();
  },
  methods: {
    /**
     * 提交表单
     *
     * @reutrn void
     */
    submit() {
      this.create(this.authModel).then(() => {
        this.$children[0].close();
      });
    },
    /**
     * 图标的点击事件
     *
     * @param {HTMLEvent} e
     */
    selectIcon(e) {
      this.authModel.icon =
        this.authModel.icon === e.target.className ? "" : e.target.className;
    },
    /**
     * 隐藏icon字体列表
     *
     * @param {HTMLEvents} event
     */
    hiddenIconList(event) {
      if (
        event &&
        this.$checkElementAncestor(event.target, this.$refs["icon-list"])
      ) {
        return;
      }
      // 隐藏列表
      this.$refs["icon-list"].style.overflow = "hidden";
      this.$animate(
        this.$refs["icon-list"],
        {
          ani: "slideUp",
          top: "0px",
          duration: 186,
        },
        (el) => {
          el.style.display = "none";
          this.iconActive = false;
        }
      );
      window.removeEventListener("click", this.hiddenIconList);
    },
    /**
     * 获取父级权限
     *
     * @return {void}
     */
    getParentAuth() {
      this.$axios.get("/admin/auth/create").then((response) => {
        this.parentAuth = response.data.data;
      });
    },
  },
};
</script>

<style lang="scss">
.create-auth {
  box-shadow: unset !important;
  tr {
    &:hover {
      background-color: inherit !important;
    }
    td {
      &:first-child {
        width: 12rem;
        text-align: right !important;
      }
      &:last-child {
        text-align: left !important;
      }
    }
  }
  .icon-list {
    width: 40rem;
    top: 0rem;
    z-index: 99999;
    background-color: #fff;
    border: 0.1rem solid #666;
    box-sizing: border-box;
    display: none;
    overflow: hidden;
    &:after {
      content: "";
      width: 0;
      height: 0;
      z-index: 2;
      left: 2rem;
      bottom: -2rem;
      margin: 0 auto;
      border-style: solid;
      position: absolute;
      border-width: 1rem 1rem 1rem 1rem;
      border-color: #c6c6c6 transparent transparent transparent;
    }
    ul {
      width: 100%;
      list-style: none;
      display: flex;
      flex-wrap: wrap;
    }
    li {
      font-size: 1.8rem;
      color: #666;
      margin: 0.2rem 0.5rem;
      padding: 0.2rem 0.5rem;
      cursor: pointer;
      &:hover {
        background-color: #e6e6e6;
      }
    }
  }
  .url-input {
    padding-left: 0.7rem;
    margin-top: 0.2rem;
  }
}
</style>