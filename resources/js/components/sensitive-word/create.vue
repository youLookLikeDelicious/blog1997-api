<template>
  <transition appear @enter="enter" @leave="leave">
    <div class="absolute-position create-sensitive-word flex">
      <div class="relative-position inline-block mr-min">
        <v-input theme="box" v-model="model.word" placeholder="敏感词"></v-input>
      </div>
      <v-select v-model="model.category_id" :options="categoryList"></v-select>
      <div class="link-btn-wrapper">
        <a href="/" @click.stop.prevent class="link-btn-primary icofont-undo" @click="toggleComponent">取消</a>
        <submit-btn theme="inline" :allowSubmit="allowSubmit" @submit="submit"></submit-btn>
      </div>
    </div>
  </transition>
</template>

<script>
import createNewModelMixin from "~/mixin/create-new-model";
import compareOriginModel from '~/mixin/compare-origin-model'

export default {
  name: "CreateSensitiveWord",
  mixins: [compareOriginModel, createNewModelMixin],
  props: {
    categoryList: {
      type: Array,
      default() {
        return [];
      },
    },
    originModel: {
      type: Object,
      default () {
        return {
          category_id: 0,
          word: "",
        }
      }
    }
  },
  data() {
    return {
      model: {
        ...this.originModel
      },
    };
  },
  computed: {
    allowSubmit () {
      return this.checkModelIsDirty() && this.model.category_id && this.model.word
    }
  },
};
</script>

<style lang="scss">
.create-sensitive-word {
  align-items: center;
  opacity: 0;
  .link-btn-wrapper{
    padding-left: 1rem;
  }
  span{
    margin-right: 1rem;
  }
}
</style>