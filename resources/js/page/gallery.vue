<template>
  <base-component requestApi="/admin/gallery" @updated-data="updatedData">
    <template v-slot:header="{ create }">
      <header>
        <a
          href="/"
          class="icofont-upload-alt gallery-upload-btn"
          @click.stop.prevent
          @click="$showDialog"
          >上传图片</a
        >
      </header>
      <upload-image
        v-if="$store.state.globalState.showDialog"
        @create="create"
      />
    </template>
    <template v-slot:default="{ deleteRecord }">
      <div class="sub-container">
        <ul class="gallery-image-list">
          <li
            v-for="(item, index) in imageList"
            :key="index"
            class="relative-position"
            @mouseenter="showToolBar"
            @mouseleave="hiddenToolBar"
          >
            <img :src="item.url + '?t=min'" @click="showPreImage(index)" alt />
            <div class="tool-bar">
              <a
                href="/"
                @click.prevent
                @click="deleteRecord(index)"
                class="icofont-ui-delete"
              ></a>
            </div>
          </li>
        </ul>
      </div>
      <big-image
        v-if="showPreImageDialog"
        @next="next"
        @previous="previous"
        @hidden-dialog="hiddenPreImage"
        :image-src="imageList[currentImageIndex].url"
      />
    </template>
  </base-component>
</template>

<script>
import uploadImage from '~/components/gallery/upload-image'
import BigImage from '../components/gallery/big-image.vue'
export default {
  name: 'Index',
  components: {
    uploadImage,
    BigImage,
  },
  data() {
    return {
      useUploadBox: false,
      pageInfo: undefined,
      imageList: [],
      currentImageIndex: 0,
      showPreImageDialog: false,
    }
  },
  methods: {
    /**
     * 显示大图对话框
     */
    showPreImage(index) {
      if (!isNaN(index)) {
        this.currentImageIndex = index
      }
      this.showPreImageDialog = true
    },
    /**
     * 关闭大图对话框
     */
    hiddenPreImage() {
      this.showPreImageDialog = false
    },
    /**
     * 获取下一张图片
     *
     * @return {void}
     */
    next() {
      if (this.currentImageIndex >= this.imageList.length - 1) {
        this.$setMessage({ msg: '已经是最后一张了' })
        return
      }
      ++this.currentImageIndex
    },
    /**
     * 获取前一张图片
     *
     * @return {void}
     */
    previous() {
      if (this.currentImageIndex === 0) {
        this.$setMessage({ msg: '这已经是第一张了' })
        return
      }
      --this.currentImageIndex
    },
    /**
     * 获取相册列表列表
     */
    updatedData(data) {
      this.imageList = data.records
    },
    /**
     * 显示工具栏
     */
    showToolBar(event) {
      this.$animate(event.target.querySelector('div'), {
        bottom: '0.7rem',
        duration: 128,
      })
    },
    /**
     * 隐藏工具栏
     */
    hiddenToolBar() {
      this.$animate(event.target.querySelector('div'), {
        bottom: '-3rem',
        duration: 128,
      })
    },
  },
}
</script>

<style lang="scss">
.gallery-upload-btn {
  width: 9rem;
  color: #5c9dff !important;
  position: relative;
  text-align: center;
  padding: 0.5rem 0rem;
  border-radius: 0.3rem;
  display: inline-block;
  border: 0.1rem solid #5c9dff;
  input {
    width: 100%;
    height: 100%;
    opacity: 0;
    position: absolute;
    left: 0;
    top: 0;
    cursor: pointer;
  }
}
.gallery-image-list {
  list-style: none;
  display: flex;
  margin-top: 1.2rem;
  margin-bottom: 2.4rem;
  justify-content: flex-start;
  flex-wrap: wrap;
  li {
    width: 24rem;
    margin: 1rem 1.3rem;
    overflow: hidden;
    box-sizing: content-box;
  }
  img {
    width: 24rem;
    border-radius: 0.5rem;
    cursor: pointer;
  }
  .tool-bar {
    left: 0;
    width: 100%;
    bottom: -3rem;
    color: #fff;
    position: absolute;
    padding-right: 2rem;
    box-sizing: border-box;
    background-color: rgba(6, 6, 6, 0.7);
  }
}
</style>