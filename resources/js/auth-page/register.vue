<template>
  <div class="login-background">
    <header>Blog1997</header>
    <div class="register-wrap form-box">
      <table class="auth-table">
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-email"></i>
              <div>
                <v-input
                  v-model="model.email"
                  placeholder="请填写管理员邮箱"
                ></v-input>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-lock"></i>
              <div>
                <v-input
                  v-model="model.password"
                  placeholder="必须包含数字字母和其他字符"
                  type="password"
                ></v-input>
              </div>
              <password-strength :strength="strength" v-if="strength" />
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-lock"></i>
              <div class="">
                <v-input
                  v-model="model.password_confirmation"
                  placeholder="请再次确认密码"
                  type="password"
                ></v-input>
              </div>
              <div v-if="passwordConfirmError" class="error-inof">
                {{ passwordConfirmError }}
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <a
              href="/"
              @click.prevent.stop
              @click="submit"
              :class="[{'btn-disable': !allowSubmit}, 'register-btn', 'btn-enable']"
              >提交</a
            >
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import passwordMixin from "./mixin/password";
import PasswordStrength from "./component/password-strength";
export default {
  name: "Register",
  data() {
    return {
      model: {
        email: "",
        password: "",
        password_confirmation: "",
        _method: "put",
      },
      id: "",
      postUrl: "",
      passwordConfirmError: "",
    };
  },
  computed: {
    allowSubmit() {
      return (
        this.strength === 3 &&
        this.model.password_confirmation === this.model.password
      );
    },
  },
  mixins: [passwordMixin],
  components: {
    PasswordStrength,
  },
  created() {
    this.getUserEmail();
  },
  methods: {
    /**
     * 提交表单数据
     */
    submit() {
      if (!this.id || !this.postUrl) {
        console.error("Undefined paramter identifier");
      }

      this.$axios.post(this.postUrl, this.model).then((resonse) => {
        // window.location.replace(`${process.env.MIX_SENTRY_DSN_PUBLIC}/admin/login`)
        this.$router.push({
          path: "/login",
          query: { email: this.model.email },
        });
      });
    },

    /**
     * 获取注册用户的邮箱
     */
    getUserEmail() {
      const ssr = window.__vue__;
      this.model.email = ssr.manager.email;
      this.id = ssr.manager.id;
      this.postUrl = ssr.url;
    },
  },
};
</script>

<style lang="scss">
.register-wrap {
  .error-inof {
    color: rgb(243, 79, 38);
    font-size: 1.2rem;
  }
}
</style>