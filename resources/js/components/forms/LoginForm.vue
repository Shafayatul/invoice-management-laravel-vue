<template>
  <v-form
    ref="loginForm"
    v-model="isValid"
    :disabled="loading"
    @submit.prevent="handleLogin"
  >
    <v-text-field
      label="Email Address"
      v-bind="fieldAttrs"
      :rules="[rules.required('Email'), rules.email]"
      v-model="user.email"
      class="focus-bg-none"
      prepend-inner-icon="mdi-email"
    />

    <v-text-field
      label="Password"
      v-bind="fieldAttrs"
      v-model="user.password"
      :rules="[rules.required('Password'), rules.password]"
      class="mt-4 focus-bg-none"
      prepend-inner-icon="mdi-lock"
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      @click:append="password.show = !password.show"
    />
    <router-link
      to="/forgot-password"
      class="secondary--text d-block text-right p pt-2"
      >Forgot Password?</router-link
    >

    <v-checkbox
      v-model="remember_me"
      label="Remember me"
      v-bind="fieldAttrs"
      class="mt-1 pt-0"
    />

    <div class="text-center mt-4">
      <v-btn
      block
        type="submit"
        class="shadow"
        color="primary"
        :loading="loading"
        >Login</v-btn
      >
    </div>
  </v-form>
</template>

<script>
import { createFormMixin } from "@/mixins/form-mixin";
export default {
  name: "LoginForm",
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
      user: {
        email: "",
        password: "",
      },
      remember_me: false,
      password: {
        show: false,
        confirmed: false,
      },
    };
  },
  methods: {
    handleLogin() {
      this.$refs.loginForm.validate();
      if (!this.isValid) return;
      this.$emit("login", {
        ...this.user,
        remember_me: this.remember_me ? 1 : 0,
      });
    },
  },
};
</script>
