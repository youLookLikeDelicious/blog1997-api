<template>
  <!-- 友情链接的列表信息 -->
  <div class="sub-container">
    <div class=" system-setting">
      <ul class="setting-menu">
        <li
          :class="{ 'menu-on': currentComponent.name === 'BasicSetting' }"
          data-name="BasicSetting"
          @click="selectTag"
        >
          基本设置
        </li>
        <li
          :class="{ 'menu-on': currentComponent.name === 'EmailSetting' }"
          data-name="EmailSetting"
          @click="selectTag"
        >
          邮箱设置
        </li>
      </ul>

      <div class="setting-container">
        <keep-alive>
          <component :is="currentComponent"></component>
        </keep-alive>
      </div>
    </div>
  </div>
</template>

<script>
import BasicSetting from "~/components/setting/basic-setting";
import EmailSetting from "~/components/setting/email-setting";

export default {
  name: "Setting",
  data() {
    return {
      currentComponent: BasicSetting,
    };
  },
  methods: {
    /**
     * 点击选项卡事件
     *
     * @param {HTMLEvent} event
     * @return {void}
     */
    selectTag(event) {
      const componentName = event.target.dataset.name;

      if (componentName === this.currentComponent.name) {
        return;
      }

      switch (componentName) {
        case "BasicSetting":
          this.currentComponent = BasicSetting;
          break;
        case "EmailSetting":
          this.currentComponent = EmailSetting;
          break;
      }
    },
  },
};
</script>

<style lang="scss">
.system-setting {
  width: 93%;
  overflow: hidden;
  border: .1rem solid #e4e7ed;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  margin: 3rem auto;
  box-sizing: border-box;
  box-shadow: 0 2px 4px 0 rgba(0,0,0,.12), 0 0 6px 0 rgba(0,0,0,.04);
  .setting-menu {
    display: flex;
    list-style: none;
    background-color: #f5f7fa;
    li {
      height: 4rem;
      font-size: 1.4rem;
      line-height: 2rem;
      box-sizing: border-box;
      padding: 1rem 0.7rem;
      display: inline-block;
      color: #909399;
      cursor: pointer;
      &:hover {
        color: #409eff;
      }
      &:first-child{
        border-left: 0 ;
      }
    }
  }
  .menu-on {
    background-color: #fff;
    color: #409eff !important;
    border-left: .1rem solid #e4e7ed;
    border-right: .1rem solid #e4e7ed;
    border-bottom-color: #fff;
  }
  .setting-container{
    padding: 2rem;
  }
  td{
      padding: 2rem;
      white-space: nowrap;
  }
}
</style>
