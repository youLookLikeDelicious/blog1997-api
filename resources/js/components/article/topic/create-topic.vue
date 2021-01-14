<template>
  <!--  新建专题、修改专题-->
  <transition appear @enter="enter" @leave="leave">
    <div class="create-topic absolute-position">
      <div class="display-inline-block">
        <v-input v-model="model.name" placeholder="专题名称"></v-input>
      </div>
      <div class="display-inline-block">
        <span>
          <a href="/" @click.prevent @click="toggleComponent" class="link-btn-primary icofont-undo">取消</a>
          <a href="/" @click.prevent @click="submit" class="link-btn-primary icofont-upload-alt">提交</a>
        </span>
      </div>
    </div>
  </transition>
</template>

<script>
import createNewModelMixin from "~/mixin/create-new-model";
import checkModelIsDirty from "~/mixin/compare-origin-model";

export default {
  name: "CreateTopic",
  props: {
    originModel: {
      type: Object,
      default() {
        return { name: '' }
      },
    },
  },
  data() {
    return {
      model: {
        ...this.originModel
      },
    };
  },
  computed: {
    allowSubmit() {
      return this.checkModelIsDirty() && this.model.name
    }
  },
  mixins: [createNewModelMixin, checkModelIsDirty],
};
</script>

<style lang="scss">
.create-topic {
  display: flex;
  justify-content: space-between;
  align-items: center;
  opacity: 0;
  left: -2rem;
  top: -1rem;
  box-sizing: border-box;
  span {
    margin-left: 1rem;
    a {
      margin-right: 0.7rem;
      position: relative;
    }
  }
}
</style>
