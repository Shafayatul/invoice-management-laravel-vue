<template>
  <v-app >
      <v-card class="mx-auto my-auto" :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'" :loading="loading" :disabled="loading">
        <div class="px-4 py-8">
          <v-card-title class=""
            ><span class="mx-auto">Forgot password</span> </v-card-title
          >
          <v-card-subtitle class="text-center body-2"
            >We will send a recovery link to your email address to reset your password</v-card-subtitle
          >
          <v-card-text class="mt-5">
            <v-alert
              dense
              outlined
              dismissible
              v-model="error.dialog"
              :type="isSuccess?'success':'error'"
            >
              {{ error.message }}
            </v-alert>
            <ForgotPasswordForm :loading="loading" @ForgotPassword="handleForgotPassword" />
          </v-card-text>
        </div>
      </v-card>
  </v-app>
</template>

<script>
import ForgotPasswordForm from "@/components/forms/ForgotPasswordForm";
// import { mapActions } from "vuex";
export default {
  name: "ForgotPassword",
  components: {
    ForgotPasswordForm
  },
  data() {
    return {
      loading: false,
      error: {
        dialog: false,
        message: "",
      },
      isSuccess:false,
      
    };
  },
//   methods: {
//     ...mapActions("auth", ["login"]),
//     async handleLogin(data) {
//       this.loading = true;
//       let res = await this.login(data);
//       if (res.error) {
//         this.loading = false;
//         return (this.error = {
//           dialog: true,
//           message: "Incorrect email or password.",
//         });
//       }
//       let { redirect } = { ...this.$route.query };
//       this.$router.push(redirect ? redirect : "/");
//     },
//   },
//  methods:{
// 	 ...mapActions("auth",["forgotPassword"]),
//    async handleForgotPassword(data){
//      this.loading=true;
//      let res =await this.forgotPassword(data)
// 		 if (res.error) {
//        this.loading=false;
//        return (this.error={
//          dialog:true,
//          message:"Invalid Email Address"
//        })
//      }
//      else{
//        this.isSuccess=true
//        this.loading=false;
//        this.error.dialog=true
//        this.error.message=res.data.message
//      }
// 	 }
//  }
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