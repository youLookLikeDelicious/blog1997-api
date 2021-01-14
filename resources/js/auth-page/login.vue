<template>
  <!-- 登陆页面 -->
  <div class="login-background">
    <div class="form-box login-wrap">
      <header>Blog1997</header>
      <table class="auth-table">
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-email"></i>
              <div>
                <v-input v-model="model.email" placeholder="邮箱"></v-input>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-lock"></i>
              <div>
                <v-input v-model="model.password" type="password" placeholder="密码"></v-input>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="relative-position">
              <i class="icofont-qr-code"></i>
              <div>
                <v-input v-model="model.captcha" placeholder="验证码"></v-input>
              </div>
              <div class="capcha-wrapper">
                <img
                  :src="'/admin/captcha?' + captchaRand"
                  @click="refreshCaptcha"
                  alt="验证码"
                />
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <a
              :class="['auth-btn', { 'auth-btn-disable': !allowSubmit }]"
              @click.stop.prevent
              @click="login"
              href="/"
              >登 陆</a
            >
          </td>
        </tr>
      </table>
      <div class="login-box">
        <a
          :href="githubUrl"
          class="git"
        />
        <a :href="wechatUrl" class="wechat" />
        <a href="/" class="qq" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "login",
  name: "Login",
  data() {
    return {
      model: {
        email: "",
        password: "",
        captcha: "",
      },
      captchaRand: Math.random(),
    };
  },
  computed: {
    wechatUrl() {
      const baseUrl = 'https://open.weixin.qq.com/connect/qrconnect?'
      const query = [
        `appid=${process.env.MIX_WECHAT_APP_ID}`,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/login%3ftype=wechat%26redirect=admin`,
        'response_type=code',
        'scope=snsapi_login',
        'state=state'
      ]

      return baseUrl + query.join('&');
    },
    githubUrl () {
      const baseUrl = 'https://github.com/login/oauth/authorize?'
      const query = [
        'client_id=' + process.env.MIX_GIT_CLIENT_ID,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/login%3ftype=github%26redirect=admin`
      ]
      return baseUrl + query.join('&')
    },
    allowSubmit() {
      return this.model.email && this.model.password && this.model.captcha;
    },
  },
  mounted() {
    // 尝试获取email
    this.attempGetEmail();

    // 获取参数
    const params = window.location.search.match(/(code|type|redirect)=([^\&]+)/gi);
      
    if (!params) {
      return;
    }

    this.$axios
      .post("/oauth/authorize?" + params.join("&"))
      .then((response) => {
        window.location.replace(`${process.env.MIX_SENTRY_DSN_PUBLIC}/admin`);
      });
  },
  methods: {
    loginByQQ($e) {
      this.$preventEvent($e);
      this.$axios({
        url: `oauth/authorize`,
        method: "GET",
      }).then((response) => {
        this.toHome();
      });
    },
    /**
     * 通过账号密码登陆
     */
    login() {
      this.$axios
        .post("/auth/login", this.model)
        .then(() => this.toHome())
        .catch(() => {
          this.refreshCaptcha();
        });
    },
    /**
     * 跳转到管理员页面
     */
    toHome() {
      window.location.replace(`${process.env.MIX_SENTRY_DSN_PUBLIC}/admin`);
    },
    /**
     * 刷新验证码
     */
    refreshCaptcha() {
      this.captchaRand = Math.random();
    },
    /**
     * 尝试获取邮箱
     */
    attempGetEmail() {
      if (this.$route.query.email) {
        this.model.email = this.$route.query.email;
      }
    },
  },
};
</script>

<style lang="scss">
.login-wrap {
  .login-box {
    width: 100%;
    height: 100%;
    text-align: center;
    a {
      width: 3.5rem;
      height: 3.5rem;
      display: inline-block;
    }
  }
}
.capcha-wrapper {
  margin-top: 1rem !important;
  img {
    width: 21rem;
    cursor: pointer;
  }
}
</style>
