<template>
  <v-form
    ref="updatePassword"
    v-model="isValid"
    :disabled="loading"
    @submit.prevent="resetPassword"
  >
    <v-text-field
      :rules="[rules.required('Password'), rules.password]"
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      prepend-inner-icon="mdi-lock"
      @click:append="password.show = !password.show"
      dense
      outlined
      label="Old Passowrd"
      v-model="user.old_password"
    />

    <v-text-field
      :rules="[rules.required('Password'), rules.password]"
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      prepend-inner-icon="mdi-lock"
      @click:append="password.show = !password.show"
      dense
      outlined
      label="Passowrd"
      v-model="user.new_password"
    />

    <v-text-field
      :type="password.show ? 'text' : 'password'"
      :append-icon="password.show ? 'mdi-eye' : 'mdi-eye-off'"
      prepend-inner-icon="mdi-lock"
      dense
      outlined
      @click:append="password.show = !password.show"
      v-model="user.confirm_password"
      :rules="[(v) => confirmPassword(v, user.new_password),rules.required('Confirm Password')]"
      label="Confirm Password"
    />

    <div class="text-center">
      <v-btn
        block
        type="submit"
        class="shadow"
        color="primary"
        small
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
        old_password: "",
        new_password: "",
        confirm_password:""
      },
    };
  },
  methods: {
    resetPassword() {
      this.$refs.updatePassword.validate();
      if (!this.isValid) return;
      this.$emit("updatePassword", this.user);
    },
    confirmPassword(cPass, pass) {
      return cPass === pass || "Password not matched";
    },
  },
};
</script>