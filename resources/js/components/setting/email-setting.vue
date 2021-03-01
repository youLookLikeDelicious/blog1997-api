<template>
  <div class="email-setting">
    <table>
      <tr>
        <td>驱动</td>
        <td>
          <v-input placeholder="smtp" readonly val="smpt" />
        </td>
      </tr>
      <tr>
        <td>邮箱服务器地址</td>
        <td>
          <v-input placeholder="smtp.163.com" v-model="model.email_server" />
        </td>
      </tr>
      <tr>
        <td>服务器端口</td>
        <td><v-input placeholder="465" v-model="model.port" /></td>
      </tr>
      <tr>
        <td>邮箱地址</td>
        <td class="relative-position">
            <v-input
              name="email"
              placeholder="blog1997@163.com"
              v-model="model.email_addr"
            />
          <span class="error absolute-position email-error">{{ errors.email }}</span>
        </td>
      </tr>
      <tr>
        <td>加密方式</td>
        <td>
          <label class="encryption-label">
            <input
              type="radio"
              name="encryption"
              v-model="model.encryption"
              value="none"
            />无
          </label>
          <label class="encryption-label">
            <input
              type="radio"
              name="encryption"
              v-model="model.encryption"
              value="ssl"
            />SSL
          </label>
          <label class="encryption-label">
            <input
              type="radio"
              name="encryption"
              v-model="model.encryption"
              value="tsl"
            />TSL
          </label>
        </td>
      </tr>
      <tr>
        <td>发件人人</td>
        <td><v-input placeholder="blog1997" v-model="model.sender" /></td>
      </tr>
      <tr>
        <td><v-helper :content="authorizePasswordHelper"/> 授权码</td>
        <td><v-input type="password" v-model="model.password" /></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <a
            @click.prevent.stop
            @click="submit"
            :class="[
              'btn-enable',
              {'btn-disable': !allowSubmit },
            ]"
            href="/"
            >提交</a
          >
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import SettingMixin from '~/mixin/setting'
import CompareModelMixin from '~/mixin/compare-origin-model'
const authorizePasswordHelper = `
<p>授权码是用于登录第三方邮件客户端的专用密码。</p>
<p>授权码更新之后,不会再次显示。</p>
`
export default {
  name: "EmailSetting",
  mixins: [SettingMixin, CompareModelMixin],
  data() {
    return {
      model: {
        driver: "smtp",
        email_server: "",
        port: "",
        email_addr: "",
        encryption: "none",
        sender: "",
        password: ''
      },
      originModel: {},
      errors: {
        email: "",
      },
      authorizePasswordHelper
    };
  },
  computed: {
    allowSubmit() {
      return this.checkModelIsDirty() && !this.errors.email
    },
  },
  created () {
    this.getEmailConfig()
  },
  watch: {
    "model.email_addr"() {
      if (!this.model.email_addr.length) {
        this.errors.email = "";
        return;
      }
      const result = this.$verify("email", this.model.email_addr);

      if (!result && !this.errors.email) {
        this.errors.email = "未识别的邮箱格式";
      } else if (result && this.errors.email) {
        this.errors.email = "";
      }
    },
  },
  methods: {
    submit() {
      if (! this.allowSubmit) {
        return
      }

      const data = { ...this.model }
      if (data.id) {
        data._method = 'PUT'
      }
      
      this.$axios.post('/admin/email-config/' +( this.model.id ? this.model.id : ''), data)
        .then(response => response.data.data)
        .then(data => {
          this.configSetting(data)
          this.setOriginModel(data)
        })
        .catch(this.restore)
    },
    /**
     * 设置初始的模型
     * 
     * @param {json} origin
     */
    setOriginModel(origin) {
      this.originModel = { ...origin }
    },
    /**
     * 获取Email的配置信息
     */
    getEmailConfig () {
      this.$axios.get('/admin/email-config')
        .then(response => response.data.data)
        .then(data => {
          this.configSetting(data)
          this.setOriginModel(data)
        })
    }
  },
};
</script>

<style lang="scss">
.email-setting {
  table {
    width: 97%;
    margin: 0 auto;
    table-layout: fixed;
    border-collapse: collapse;
    tr{
      height: 8rem;
      box-sizing: border-box;
      overflow: hidden;
    }
    td {
      &:first-child {
        text-align: right;
        width: 12rem;
      }
    }
  }
  .email-error {
    right: 1rem;
    top: 1rem;
  }
  .encryption-label{
    margin-right: 1rem;
  }
}
</style>