<template>
  <v-dialog width="35rem" height="16rem" :title="title" @close="close">
    <div class="un-bind-account flex">
      <p>
        {{ type }}
        <span>[{{ conflictInfo.socialAccount.name }}]</span>
        已被账号<span>[{{ conflictInfo.conflictAccount.name }}]</span
        >绑定, 是否解绑<span>[{{ conflictInfo.conflictAccount.name }}]</span
        >账号的微信，并绑定到当前的账号上
      </p>
      <a target="_top" @click="rebind" class="btn-enable inline-block text-align-center " :href="rebindUrl"
        >重 新 绑 定</a
      >
    </div>
  </v-dialog>
</template>

<script>
const typeMap = {
  1: "微信",
  2: "Github",
  3: "QQ",
};

export default {
  name: "RebindAccount",
  props: {
    conflictInfo: {
      type: Object,
      default() {
        return {
          socialAccount: {
          },
          conflictAccount: {
          }
        };
      },
    },
  },
  data() {
    return {};
  },
  computed: {
    type () {
      return typeMap[this.conflictInfo.socialAccount.type]
    },
    title() {
      return this.type + "账号已被绑定";
    },
    rebindUrl () {
      switch (this.type) {
        case '微信':
          return this.wechatRebindUrl()
        case 'Github': 
          return this.githubRebindUrl()
        default: 
          return ''
      }
    }
  },
  methods: {
    rebind() {},
    close () {
      this.$emit('input', false)
    },
    wechatRebindUrl () {
      const baseurl = 'https://open.weixin.qq.com/connect/qrconnect?'
      const query = [
        `appid=${process.env.MIX_WECHAT_APP_ID}`,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/profile/wechat%3faction=rebind`,
        'response_type=code',
        'scope=snsapi_login',
        'state=state'
      ]
      return baseurl + query.join('&');
    },
    githubRebindUrl() {
      const baseUrl = 'https://github.com/login/oauth/authorize?'
      const query = [
        'client_id=' + process.env.MIX_GIT_CLIENT_ID,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/profile/github%3faction=rebind`
      ]

      return baseUrl + query.join('&')
    }
  },
};
</script>

<style lang="scss">
.un-bind-account {
  height: 100%;
  flex-direction: column;
  box-sizing: border-box;
  justify-content: space-between;
  p{
    padding-top: 2rem;
    letter-spacing: .1rem;
  }
  span {
    color: rgb(7, 177, 219);
    font-size: 1.6rem;
  }
}
</style>