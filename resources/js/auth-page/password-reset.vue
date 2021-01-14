<template>
  <div class="password-reset form-box">
    <header>重置密码</header>
    <table class="auth-table">
      <tr>
        <td>
          <div class="relative-position">
            <i class="icofont-email"></i>
            <v-input readonly v-model="model.email"></v-input>
          </div>
        </td>
      </tr>
      <tr>
        <td class="relative-position">
          <div class="relative-position">
            <i class="icofont-lock"></i>
            <v-input
              type="password"
              v-model="model.password"
              placeholder="设置新密码"
            ></v-input>
          </div>
          <password-strength :strength="strength" v-if="strength" />
        </td>
      </tr>
      <tr>
        <td class="relative-position">
          <div class="relative-position">
            <i
              :class="['icofont-lock', { 'red-lock': passwordConfirmError }]"
            ></i>
            <v-input
              type="password"
              v-model="model.password_confirmation"
              placeholder="确认密码"
            ></v-input>
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
</template>

<script>
import passwordMixin from "./mixin/password";
import PasswordStrength from "./component/password-strength";
export default {
  name: "PasswordReset",
  data() {
    return {
      model: {
        email: "",
        password: "",
        password_confirmation: "",
        token: "",
      },
      passwordConfirmError: "",
    };
  },
  mixins: [passwordMixin],
  computed: {
    allowSubmit () {
      return this.model.password && this.model.password_confirmation && this.strength === 3
    }
  },
  components: {
    PasswordStrength,
  },
  methods: {
    submit () {
      if (! this.allowSubmit) {
        return
      }

      this.$axios.post('/admin/password/update', this.model)
        .then(response => {
          window.setTimeout(() => {
            this.$router.push({name: 'Login'})
          }, 200)
        })
    }
  },
  mounted() {
    const query = this.$route.query;
    this.model.email = query.email;
    this.model.token = query.token;
  },
};
</script>

<style lang="scss">
.password-reset {
  width: 80%;
  max-width: 65rem;
}
.red-lock {
  color: rgb(246, 90, 90) !important;
}
</style>