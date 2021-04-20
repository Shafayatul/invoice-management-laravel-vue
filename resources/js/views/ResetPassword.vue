<template>
  <v-app>
      <v-card class="mx-auto my-auto" :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'" :loading="loading" :disabled="loading">
        <div class="px-8 py-12">
          <v-card-title class="title login__title flex-column"
            >Reset Password</v-card-title
          >
          <v-card-subtitle class="text-center body-2"
            >Don't worry happens to the best of us</v-card-subtitle
          >
          <v-card-text class="mt-5">
            <v-alert
              dense
              outlined
              dismissible
              v-model="error.dialog"
              :type="isSuccess ? 'success' : 'error'"
            >
              {{ error.message }}
            </v-alert>
            <ResetPasswordForm
              :loading="loading"
            />
              <!-- @resetPassword="handleResetPassword" -->
          </v-card-text>
        </div>
      </v-card>
  </v-app>
</template>

<script>
import ResetPasswordForm from "@/components/forms/ResetPasswordForm";
import { mapActions } from "vuex";
export default {
  name: "signin",
  components: {
    ResetPasswordForm,
  },
  data() {
    return {
      loading: false,
      error: {
        dialog: false,
        message: "",
      },
      email: "",
      token: {
        token: "",
      },
      tokenValidity: false,
      isSuccess: false,
    };
  },
  created() {
    // this.token.token = this.$route.query.token;
    // this.email = localStorage.getItem("pepsi-forgot-password-temp-email");
    // localStorage.removeItem("pepsi-forgot-password-temp-email");
  },
  // methods: {
  //   ...mapActions("auth", ["resetPassword", "checkTokenValidity"]),
  //   async ChecktokenValidity() {
  //     let res = await this.checkTokenValidity(this.token);
  //     if (res.error) {
  //       this.error.dialog = true;
  //       this.error.message = res.data.message;
  //       return false;
  //     }
  //     return true;
  //   },
  //   async handleResetPassword(data) {
  //     this.loading = true;
  //     let isValid = await this.ChecktokenValidity();

  //     if (isValid) {
  //       let res = await this.resetPassword({
  //         ...data,
  //         email: this.email,
  //         token: this.token.token,
  //       });

  //       if (res.error) {
  //         this.loading = false;
  //         this.error.dialog = true;
  //         this.error.message = res.data.message;
  //       } else {
  //         this.isSuccess = true;
  //         this.loading = false;
  //         this.error.dialog = true;
  //         this.error.message = res.data.message;
  //       }
  //     }
  //   },
  // },
};
</script>

<style lang="scss" type="text/scss" scoped>
// .login {
//   &__card {
//     width: 450px;
//     @include center;
//     @include on("xs") {
//       width: 96%;
//     }
//   }
// }
</style>