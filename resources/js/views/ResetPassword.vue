<template>
  <v-app>
    <v-card 
      :loading='loading'
      class="mx-auto my-auto"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
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
          <ResetPasswordForm v-if="tokenValidity"
            @resetPassword="handleResetPassword"
            :loading="loading"
          />
        </v-card-text>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import ResetPasswordForm from "@/components/forms/ResetPasswordForm";
import { mapActions } from "vuex";
export default {
  name: "resetpassword",
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
      token: null,
      tokenValidity: false,
      isSuccess: false,

    };
  },
  created() {
    console.log(this.$route.params.token);
    
    // this.email = localStorage.getItem("ForgotPassword-email");
    // localStorage.removeItem("ForgotPassword-email");

    this.ChecktokenValidity()
  },
  methods: {
    ...mapActions("AUTH", ["resetPassword", "checkTokenValidity"]),
    async ChecktokenValidity() {
      this.loading=true
      let res = await this.checkTokenValidity(this.$route.params.token);
      if (res.error) {
        this.error.dialog = true;
        this.error.message = 'The token has expired';
        this.tokenValidity=false
        this.loading=false
      }
      else{
        this.email=res.email
        this.token=res.token
        this.tokenValidity=true
        this.loading=false
      }
 
    },
    async handleResetPassword(data) {
      console.log(data,'data');
      this.loading = true;
        let res = await this.resetPassword({
          ...data,
          email: this.email,
          token: this.token,
        });
        console.log(res);
        if (res.error) {
          console.log(res,'res');
          console.log(res.error,'res.error');
          this.loading = false;
          this.error.dialog = true;
          this.error.message = 'res.message';
          this.loading = false;
        } else {
          console.log(res);
          this.isSuccess = true;
          this.loading = false;
          this.error.dialog = true;
          this.error.message = "Password reset successfully";
          setTimeout(() => {
             this.$router.push('/login')
          }, 3000);
        }
    },
  },
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