<template>
  <div class="big-image">
      <a href="/" @click.prevent @click="$emit('hidden-dialog')"><i class="icofont-ui-close"></i></a>
      <a @click.prevent @click="$emit('previous')" href="/"><i class="icofont-rounded-double-left left-arrow"></i></a>
      <img :src="imageSrc" alt="">
      <a @click.prevent @click="$emit('next')" href="/"><i class="icofont-rounded-double-left right-arrow"></i></a>
  </div>
</template>

<script>
export default {
  name: 'BitImage',
  props: {
      imageSrc: {
          type: String,
          default () {
              return ''
          }
      }
  }
}
</script>

<style lang="scss">
.big-image {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(5, 5, 5, .7);
    z-index: 2;
    img{
        width: 80rem;
        margin: 5rem auto 0 auto;
        display: block;
    }
    .icofont-rounded-double-left{
        color: #fff;
        font-size: 6rem;
        position: absolute;
        top: 50%;
    }
    .left-arrow{
        left: 7rem;
        &:hover{
            &::after{
                content: "\EA97";
                font-size: 6rem;
                color: #fff;
                position: absolute;
                right: 0rem;
                animation: arrow-fade .8s infinite;
            }
        }
    }
    .right-arrow{
        right: 7rem;
        transform: rotateZ(180deg);
        &:hover{
            &::after{
                content: "\EA97";
                font-size: 6rem;
                color: #fff;
                position: absolute;
                right: 0rem;
                animation: arrow-fade .8s infinite;
            }
        }
    }
    @keyframes arrow-fade {
        to{
            right: 3rem;
            opacity: 0;
        }
    }
    .icofont-ui-close{
        position: absolute;
        right: 2rem;
        top: 2rem;
        font-size: 3rem;
        color: #fff;
        transition: font-size .3s;
        &:hover{
            font-size: 4rem;
        }
    }
}
</style>