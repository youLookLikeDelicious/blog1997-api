<template>
  <transition appear @enter="enter" @leave="leave">
    <div class="create-friend-link">
      名称:&nbsp;&nbsp;
      <div class="input-wrapper">
        <v-input placeholder="网站名称" v-model="model.name"></v-input>
      </div>地址:&nbsp;&nbsp;
      <div class="input-wrapper">
        <v-input 
            v-model="model.url" placeholder="网站地址"></v-input>
      </div>
      <p>
        <a href="/" @click.stop.prevent @click="toggleComponent" class="link-btn-primary">取消</a>
        <submit-btn @submit="submit" :allow-submit="allowSubmit" theme="inline"></submit-btn>
      </p>
    </div>
  </transition>
</template>

<script>
import createNewModelMixin from "~/mixin/create-new-model";
import createOriginModel from "~/mixin/compare-origin-model";

export default {
  name: "CreateFriendLink",
  props: {
    originModel: {
      type: Object,
      default() {
        return {
          id: "",
          name: "",
          url: "",
        };
      },
    }
  },
  data() {
    return {
      model: Object.assign({}, this.originModel)
    };
  },
  computed: {
    allowSubmit () {
      return this.checkModelIsDirty() && this.model.name && this.model.url
    }
  },
  mixins: [createNewModelMixin, createOriginModel],
};
</script>

<style lang="scss">
.create-friend-link {
  display: flex;
  width: 100%;
  opacity: 0;
  left: -3rem;
  top: -2rem;
  align-items: baseline;
  position: absolute;
  p {
    margin-left: 1.2rem;
    a {
      margin-right: 0.7rem;
      position: relative;
    }
  }
}
</style>
