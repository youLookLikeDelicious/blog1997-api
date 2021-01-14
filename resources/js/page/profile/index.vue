<template>
  <base-component
    requestApi="/user/profile"
    :show-header="false"
    :force-render-content="true"
    :hiddenPagination="true"
  >
    <template v-slot:default>
      <div class="profile">
        <table>
          <tr>
            <td>头像</td>
            <td>
              <div class="flex avatar-setting">
                <avatar :user="{ avatar: user.avatar }" />
                <a class="reset-btn" href="/">
                  修改头像
                  <input
                    type="file"
                    @change="uploadAvatar"
                    accept=".png, .jpg, .jpeg"
                  />
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>用户名</td>
            <td>
              <div class="inline-block">
                <v-input
                  v-model="userName"
                  name="name"
                  @blur="blurUserName"
                  placeholder="请输入用户名"
                ></v-input>
              </div>
              <a
                v-if="userName && userName !== user.name"
                @click.prevent="resetName"
                class="reset-btn"
                name="submit-name"
                href="/"
                ><i class="icofont-upload-alt"></i> 提交</a
              >
            </td>
          </tr>
          <tr>
            <td>邮箱</td>
            <td>
              <div class="inline-block">
                <v-input
                  :val="user.email"
                  width="23rem"
                  name="email"
                  @blur="blurUserEmail"
                  v-model="userEmail"
                  autocomplete="off"
                  placeholder="请输入邮箱"
                ></v-input>
              </div>
              <a
                v-if="userEmail && userEmail !== user.email && emailIsValide"
                @click.prevent="resetEmail"
                class="reset-btn"
                name="submit-email"
                href="/"
                ><i class="icofont-upload-alt"></i> 提交</a
              >
              <span class="error" v-if="!emailIsValide">
                未识别的邮箱格式
              </span>
            </td>
          </tr>
          <tr>
            <td>社交账号</td>
            <td>
              <div class="bind flex">
                <i class="wechat social-account"></i>
                <a
                  v-if="checkBindState(1)"
                  class="reset-btn"
                  @click.prevent="unbind(1)"
                  href="/"
                  >解绑</a
                >
                <a v-else :href="wechatHref" target="_top">绑定</a>
              </div>
              <div class="bind flex">
                <i class="git social-account"></i>
                <a
                  v-if="checkBindState(2)"
                  class="reset-btn"
                  @click.prevent="unbind(2)"
                  href="/"
                  >解绑</a
                >
                <a v-else target="_top" :href="githubHref">绑定</a>
              </div>
              <div class="bind flex">
                <i class="qq social-account"></i>
                <a
                  v-if="checkBindState(3)"
                  class="reset-btn"
                  @click.prevent="unbind(3)"
                  href="/"
                  >解绑</a
                >
                <a v-else @click.prevent href="/">绑定</a>
              </div>
            </td>
          </tr>
          <tr>
            <td>密码</td>
            <td>
              <a
                @click.prevent
                @click="resetPassword"
                class="reset-btn"
                href="/"
                >重 置</a
              >
            </td>
          </tr>
        </table>
      </div>
      <re-bind-account
        v-if="showDialog"
        v-model="showDialog"
        :conflict-info="conflictInfo"
      ></re-bind-account>
    </template>
  </base-component>
</template>

<script>
import ReBindAccount from '~/components/profile/re-bind-account'

export default {
  name: 'Profile',
  data() {
    return {
      userName: '',
      userEmail: '',
      conflictInfo: {
        conflictAccount: { name: 'sdf' },
        socialAccount: { name: 'soc' },
      },
      showDialog: false,
    }
  },
  computed: {
    user() {
      return this.$store.state.user
    },
    emailIsValide() {
      if (!this.userEmail) {
        return true
      }

      return this.$verify('email', this.userEmail)
    },
    type() {
      return this.$route.params.type
    },
    wechatHref() {
      const baseurl = 'https://open.weixin.qq.com/connect/qrconnect?'
      const query = [
        `appid=${process.env.MIX_WECHAT_APP_ID}`,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/profile/wechat`,
        'response_type=code',
        'scope=snsapi_login',
        'state=state',
      ]
      return baseurl + query.join('&')
    },
    githubHref() {
      const baseUrl = 'https://github.com/login/oauth/authorize?'
      const query = [
        'client_id=' + process.env.MIX_GIT_CLIENT_ID,
        `redirect_uri=${process.env.MIX_APP_URL}/admin/profile/github`,
      ]

      return baseUrl + query.join('&')
    },
  },
  components: {
    ReBindAccount,
  },
  created() {
    if (!this.userName) {
      this.userName = this.user.name
    }
    if (!this.userEmail) {
      this.userEmail = this.user.email
    }
  },
  methods: {
    /**
     * 上传头像
     *
     * @param {HTMLEvent} event
     * @return {void}
     */
    uploadAvatar(event) {
      const file = event.target.files[0]
      if (!file) {
        return
      }

      const formData = new FormData()
      formData.append('avatar', file)
      this.$axios
        .post('/user/update/' + this.user.id, formData)
        .then((response) => {
          this.$store.commit('user/setAvatar', response.data.data.avatar)
        })
    },
    /**
     * reset user name
     */
    resetName() {
      this.$axios
        .post('/user/update/' + this.user.id, {
          name: this.userName,
        })
        .then((response) => {
          this.$store.commit('user/setName', response.data.data.name)
        })
    },
    /**
     * Reset email
     */
    resetEmail() {
      this.$axios
        .post('/user/update/' + this.user.id, {
          email: this.userEmail,
        })
        .then((response) => {
          this.$store.commit('user/setEmail', response.data.data.email)
        })
    },
    /**
     * 查看是否绑定社交账号
     * @param {int} type
     * @return {boolean}
     */
    checkBindState(type) {
      const data = this.$children[0].requestResult

      if (!(data instanceof Array)) {
        return false
      }
      const finder = (item) => item.type === type
      return data.find(finder)
    },
    /**
     * @param {int} type
     * @return {void}
     */
    unbind(type) {
      const finder = (item) => item.type === type
      const index = this.$children[0].requestResult.findIndex(finder)
      this.$axios
        .post('/user/unbind/' + this.$children[0].requestResult[index].id)
        .then((response) => {
          this.$children[0].requestResult.splice(index, 1)
        })
    },
    /**
     * 重置密码
     */
    resetPassword() {
      this.$axios.post('/user/password/reset', {
        email: this.user.email,
      })
    },
    /**
     * Handle name input blur event
     */
    blurUserName() {
      if (!this.userName) {
        this.userName = this.user.name
      }
    },
    /**
     * Handle email input blur event
     */
    blurUserEmail() {
      if (!this.userEmail) {
        this.userEmail = this.user.email
      }
    },
  },
  mounted() {
    // bind操作会跳转到该页面
    // 获取参数
    const params = window.location.search.match(/(code)=([^\&]+)/i)

    if (!params || !params.length) {
      return
    }

    const query = [params[0]]

    if (this.type && ['wechat', 'github', 'qq'].includes(this.type)) {
      query.push('type=' + this.$route.params.type)
    }

    const action = this.$route.query.action || 'bind'

    this.$axios
      .post(`/user/${action}?${query.join('&')}`)
      .then((response) => {
        this.$children[0].requestResult.push(response.data.data)
      })
      .catch((error) => {
        const message = error.response.data.message
        if (message === '该账号已被绑定') {
          this.showDialog = true
          this.conflictInfo = error.response.data.data
          return {}
        }
      })
      .finally(() => {
        let path = this.$route.path
        if (this.type) {
          path.replace(this.type, '')
        }
        this.$router.push(path)
      })
  },
}
</script>

<style lang="scss">
.profile {
  table {
    width: 97%;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 0 auto;
    overflow: hidden;
    tr {
      td {
        text-align: left;
        padding: 1.5rem 1rem 1.5rem 3rem;
        &:nth-child(1) {
          width: 12rem;
          vertical-align: top;
        }
      }
      &:hover {
        background-color: inherit !important;
      }
    }
  }
  .reset-btn {
    color: $blue;
  }
  .reset-btn {
    position: relative;
    transition: color 0.3s;
    &:hover {
      color: rgb(79, 188, 238);
    }
    &:active {
      color: rgb(8, 128, 184);
    }
    input {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      opacity: 0;
    }
  }
  .social-account {
    width: 3rem;
    height: 3rem;
    margin: 0.7rem 3rem 0.7rem 0;
    display: inline-block;
  }
  .bind {
    width: 23rem;
    align-items: center;
    border-bottom: 0.1rem solid #e6e6e6;
    &:last-child {
      border: 0;
    }
  }
  .avatar-setting {
    align-items: center;
    a {
      margin-left: 1.5rem;
    }
  }
}
</style>