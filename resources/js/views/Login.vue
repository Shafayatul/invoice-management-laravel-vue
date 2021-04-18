<template>
  <v-app class="login no-auth mx-auto">
    <div class="login__card overflow-visible mx-auto">
      <v-card  :loading="loading" :disabled="loading" >
        <div class="px-3 px-md-8 py-12">
          <v-card-title class="title login__title flex-column"
            >Welcome back!</v-card-title
          >
          <v-card-subtitle class="text-center body-2"
            >Login with your Email and password</v-card-subtitle
          >
          <v-card-text class="mt-5">
            <v-alert
              dense
              outlined
              dismissible
              v-model="error.dialog"
              type="error"
            >
              {{ error.message }}
            </v-alert>
            <LoginForm :loading="loading" @login="handleLogin" />
          </v-card-text>
        </div>
      </v-card>
    </div>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";
import LoginForm from "@/components/forms/LoginForm";

export default {
  name: "Login",
  components: {
    LoginForm,
  },
  data() {
    return {
      loading: false,
      error: {
        dialog: false,
        message: "",
      },
    };
  },
  methods: {
    ...mapActions("AUTH", ["login"]),
    async handleLogin(data) {
      console.log(data);
      this.loading = true;
      let res = await this.login(data);
      if (res.error) {
        this.loading = false;
        return (this.error = {
          dialog: true,
          message: "Incorrect email or password.",
        });
      }
      this.$toast.success("Successfully logged in.");
      let { redirect } = { ...this.$route.query };
      this.$router.replace(redirect ? redirect : "/");
    },
  },
};
</script>

<style lang="scss" type="text/scss" scoped>
.login {
  &__card {
    width: 450px;
    max-width: 90%;
    // @include center;
  }
}
</style>
