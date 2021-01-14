<template>
  <v-dialog
    width="54rem"
    height="35rem"
    title="创建标签"
    @close="toggleCreateNewModel"
  >
    <table class="create-tag">
      <tr>
        <td class="required-feild">名称:</td>
        <td>
          <v-input v-model="model.name" placeholder="标签名称"></v-input>
        </td>
      </tr>
      <tr>
        <td class="required-feild">父标签</td>
        <td>
          <v-select
            v-model="model.parent_id"
            placeholder="请选择父标签"
            :options="topTag"
          >
          </v-select>
        </td>
      </tr>
      <tr>
        <td class="required-feild">封面:</td>
        <td>
          <img class="cover" v-if="coverUrl" :src="coverUrl" alt="封面" />
          <input @change="changeCover" type="file" />
        </td>
      </tr>
      <tr>
        <td>描述:</td>
        <td>
          <textarea
            name="description"
            v-model="model.description"
            placeholder="请添加标签的描述"
          ></textarea>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <submit-btn @submit="submit" :allowSubmit="allowSubmit" />
        </td>
      </tr>
    </table>
  </v-dialog>
</template>

<script>
import checkModelIsDirty from '~/mixin/compare-origin-model';

export default {
  name: "CreateTag",
  props: {
    toggleCreateNewModel: {
      default() {
        return () => {};
      },
    },
    create: {
      type: Function,
      default() {
        return () => {};
      },
    },
    // 被修改的模型
    originModel: {
      type: Object,
      default() {
        return {
          name: "",
          cover: "",
          parent_id: "",
          description: "",
        };
      },
    },
  },
  mixins: [checkModelIsDirty],
  data() {
    return {
      model: {
        ...this.originModel
      },
      coverUrl: this.originModel.cover,
      topTag: [], // 顶级标签
    };
  },
  computed: {
    allowSubmit() {
      return this.checkModelIsDirty() && this.model.parent_id !== '' && this.model.cover
    },
  },
  created() {
    this.getTopTag();
  },
  methods: {
    /**
     * 创建表单
     */
    submit() {
      if (!this.allowSubmit) {
        return;
      }

      const formData = this.$json2FormData(this.model)

      this.create(formData).then(() => {
        this.$children[0].close();
      });
    },
    /**
     * 修改封面的事件
     *
     * @param {HTMLEvent} event
     */
    changeCover(event) {
      if (!event.target.value) {
        this.coverUrl = "";
        window.URL.revokeObjectURL(this.model.cover);
        return (this.model.cover = "");
      }

      this.model.cover = event.target.files[0];

      this.coverUrl = window.URL.createObjectURL(this.model.cover);
    },
    /**
     * 获取顶级标签
     */
    getTopTag() {
      this.$axios.get("/admin/tag/create").then((response) => {
        const topTag = response.data.data;
        topTag.unshift({ id: 0, name: "--顶级标签--" });
        this.topTag = topTag;
      });
    },
  },
};
</script>

<style lang="scss">
.create-tag {
  width: 80%;
  box-shadow: unset !important;
  tr {
    &:hover {
      background-color: inherit !important;
    }
  }
  td {
    text-align: left !important;
    &:first-child {
      text-align: center;
      width: 7rem !important;
    }
  }
  textarea {
    height: 7rem;
    outline: transparent;
    padding: 0.7rem;
  }
  // 封面的样式
  .cover {
    display: inline-block;
    width: 7rem;
  }
}
</style>