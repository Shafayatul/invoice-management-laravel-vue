<template>
  <v-form
    ref="loginForm"
    v-model="isValid"
    :disabled="loading"
    @submit.prevent="resetPassword"
  >
    <!-- <v-text-field
      label="Email Address"
      v-bind="fieldOptions"
      :rules="rules.email"
      v-model="user.email"
      prepend-inner-icon="mdi-email"
    /> -->

    <v-text-field
      :rules="[rules.required('Password'), rules.password]"
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      prepend-inner-icon="mdi-lock"
      @click:append="password.show = !password.show"
      dense
      outlined
      label="Passowrd"
      v-model="user.password"
    />

    <v-text-field
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      prepend-inner-icon="mdi-lock"
      dense
      outlined
      @click:append="password.show = !password.show"
      v-model="user.password_confirmation"
      :rules="[(v) => confirmPassword(v, user.password)]"
      label="Confirm Password"
    />

    <div class="text-center">
      <v-btn
        block
        type="submit"
        class="shadow"
        color="primary"
        small
        :disabled='!!user.password && !!user.password_confirmation'
        :loading="loading"
        >Reset Password</v-btn
      >
    </div>
  </v-form>
</template>

<script>
import { createFormMixin } from "@/mixins/form-mixin";
// import { LoginValidations } from "@/mixins/validations-mixin";

export default {
  name: "ForgotPasswordForm",
 mixins: [
    createFormMixin({
      rules: ["required", "email", "password"],
    }),
  ],
  props: {
    loading: Boolean,
  },
  data() {
    return {
      isValid: false,
      password: {
        show: false,
        confirmed: false,
      },
      user: {
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    resetPassword() {
      this.$refs.loginForm.validate();
      if (!this.isValid) return;
      this.$emit("resetPassword", this.user);
    },
    confirmPassword(cPass, pass) {
      return cPass === pass || "Password not matched";
    },
  },
};
</script>
