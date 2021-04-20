<template>
  <v-form
    ref="loginForm"
    v-model="isValid"
    :disabled="loading"
    @submit.prevent="ForgotPassword"
  >
    <v-text-field
      label="Email Address"
      outlined
      dense
      :rules="[rules.required('Email'), rules.email]"
      v-model="user.email"
      hide-details="auto"
      prepend-inner-icon="mdi-email"
    />

    <div class="text-center mt-4">
      <v-btn
        block
        type="submit"
        class="shadow"
        color="primary"
        :disabled='!user.email'
        :loading="loading"
        >send recovery link</v-btn
      >
    </div>
  </v-form>
</template>

<script>
import { createFormMixin } from "@/mixins/form-mixin";
// import { LoginValidations } from "@/mixins/validations-mixin";

export default {
  name: "ForgotPasswordForm",
  // LoginValidations,
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
      },
    };
  },
  methods: {
    ForgotPassword() {
      this.$refs.loginForm.validate();
      if (!this.isValid) return;
      this.$emit("ForgotPassword",this.user );
    },
  },
};
</script>
