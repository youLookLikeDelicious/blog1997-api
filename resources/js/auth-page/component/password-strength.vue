<template>
  <div class="password-strength">
    <span :class="'strength-' + strength"></span>
    <span
      :class="{
        'strength-2': strength === 2,
        'strength-3': strength === 3,
      }"
    ></span>
    <span :class="{ 'strength-3': strength === 3 }"></span>
  </div>
</template>

<script>
export default {
  name: "PasswordStrength",
  props: {
    strength: {
      type: Number,
      default() {
        return 1;
      },
    },
  },
};
</script>

<style lang="scss">
.password-strength {
  margin-top: 1rem;
  span {
    width: 4rem;
    height: 1rem;
    display: inline-block;
    box-sizing: border-box;
    border-radius: 0.3rem;
    border: 0.1rem solid #999;
  }
  .strength-1 {
    border: 0;
    background-color: rgb(230, 30, 30);
    box-shadow: 0rem 0rem 0.2rem 0.1rem rgba($color: #e91313, $alpha: 0.3);
  }
  .strength-2 {
    border: 0;
    background-color: rgb(240, 140, 26);
    box-shadow: 0rem 0rem 0.2rem 0.1rem rgba($color: #f38c16, $alpha: 0.3);
  }
  .strength-3 {
    border: 0;
    background-color: rgb(19, 236, 30);
    box-shadow: 0rem 0rem 0.2rem 0.1rem rgba($color: #34f316, $alpha: 0.3);
  }
}
</style>