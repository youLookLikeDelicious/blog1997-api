<template>
  <v-dialog width="75rem" height="32rem" title="上传图片">
    <div class="upload-image-wrap" @dragover="preventDefaultEvent($event)" @drop="catchFile($event)">
      <div class="upload-image-box">
        <ul>
          <li v-for="(item, index) in imgList" :key="index">
            <div class="img-wrap" @mouseenter="setDeleteTag($event, true)" @mouseleave="setDeleteTag($event, false)">
              <div class="img-box">
                <img :src="item" alt="">
              </div>
              <span><a href="/" @click.stop.prevent @click="removeImage(index)">х</a></span>
            </div>
          </li>
        </ul>
        <a v-if="!imgList.length" href="" class="icofont-image upload-btn">
          <input type="file" multiple accept="image/x-png,image/gif,image/jpeg,image/webp" @change="getImage($event)">
        </a>
      </div>
      <div v-if="imgList.length" class="upload-image-foot">
        <a href="/"> <b>+</b> 继续上传<input type="file" multiple accept="image/x-png,image/gif,image/jpeg,image/webp" @change="getImage($event)"></a>
        <a href="/" class="icofont-upload-alt" @click.stop.prevent @click="upload">
          提交
        </a>
      </div>
    </div>
  </v-dialog>
</template>

<script>
export default {
  name: 'UploadImage',
  data () {
    return {
      fileList: []
    }
  },
  computed: {
    imgList () {
      return this.$getObjectURL(this.fileList)
    }
  },
  methods: {
    getImage ($e) {
      this.fileList = this.fileList.concat(Array.prototype.slice.call($e.target.files, 0))
    },
    /**
     * 阻止默认的行为
     * 阻止行为的传播
     */
    preventDefaultEvent (e) {
      if (e.repventDefault) {
        e.preventDefault()
      } else {
        e.returnValue = false
      }
      if (e.stopPropagation) {
        e.stopPropagation()
      } else {
        e.cancelBubble = true
      }
    },
    /**
     * 获取拖动进来的图片
     */
    catchFile ($e) {
      this.preventDefaultEvent($e)
      this.fileList = this.fileList.concat(Array.prototype.slice.call($e.dataTransfer.files, 0))
    },
    /**
     * 显示删除图片的标签
     */
    setDeleteTag ($e, state) {
      // 获取span标签
      const span = $e.target.querySelector('span')
      if (state) {
        this.$animate(span, { top: '0rem', duration: 170 })
      } else {
        this.$animate(span, { top: '-2.1rem', duration: 170 })
      }
    },
    /**
     * 移除上传的图片
     * @param index
     */
    removeImage (index) {
      this.fileList.splice(index, 1)
    },
    // 上传图片
    upload () {
      // 没有文件需要上传
      if (!this.imgList.length) {
        return
      }
      
      const formData = new FormData()
      this.fileList.forEach((item) => {
        formData.append('upfile[]', item)
      })
      // 开始提交
      this.$emit('create', formData)
      this.$children[0].close()
    }
  }
}
</script>

<style lang="scss">
  .upload-image-wrap{
    height: 97%;
    box-sizing: border-box;
    .upload-image-foot{
      width: 96%;
      height: 3.7rem;
      line-height: 3.7rem;
      position: absolute;
      bottom: 0;
      left: 2%;
      border-top: .1rem solid #c6c6c6;
      background-color: #fafafa;
      a{
        height: 2.1rem;
        line-height: 2.1rem;
        color: #666;
        border: .1rem solid #666;
        padding: 0 .7rem;
        border-radius: .3rem;
        position: relative;
        display: inline-block;
        text-align: center;
        margin-right: .7rem;
      }
      input{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
      }
    }
  }
  .upload-image-box{
    width: 100%;
    overflow-y: auto;
    padding-top: 1.2rem;
    text-align: left;
    ul {
      list-style: none;
      display: flex;
      justify-content: flex-start;
      flex-wrap: wrap;
      li{
        margin-bottom: 1.6rem;
      }
    }
    .upload-btn{
      width: 4.5rem;
      height: 4.5rem;
      font-size: 5.3rem;
      position: relative;
      input{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
      }
    }
    .img-wrap{
      position: relative;
      padding: 0 .7rem;
      overflow: hidden;
      span{
        height: 2.1rem;
        width: 25.2rem;
        line-height: 2.1rem;
        top: -2.1rem;
        left: .7rem;
        padding: .3rem .4em;
        position: absolute;
        text-align: right;
        font-size: 1.4rem;
        background-color: rgba(9, 9, 9, .7);
        box-sizing: border-box;
        color: #fff;
        border-top-left-radius: .5rem;
        border-top-right-radius: .5rem;
      }
    }
    .img-box{
      border-radius: .5rem;
      $width: 25rem;
      $height: 13rem;
      height: $height;
      width: $width;
      text-align: center;
      overflow: hidden;
      border: .1rem solid #c6c6c6;
      padding: .5rem 0;
      img{
        height: $height;
      }
    }
  }
</style>
