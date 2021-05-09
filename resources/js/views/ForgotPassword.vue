<template>
  <v-app>
    <div class="overflow-visible d-flex my-auto">
      <v-row>
        <v-col
          class="mx-auto"
          v-if="!$vuetify.breakpoint.smAndDown"
          cols="12"
          md="6"
        >
          <v-img>
            <v-img
              contain
              height="300"
              :src="require('@/assets/authentication.svg')"
            />
          </v-img>
        </v-col>
        <v-col cols="12" md="6">
          <v-card
            class="mx-auto mx-md-0 my-auto"
            max-width="520"
            :loading="loading"
            :disabled="loading"
          >
            <div class="px-4 py-8">
              <v-card-title class=""
                ><span class="mx-auto">Forgot password</span>
              </v-card-title>
              <v-card-subtitle class="text-center body-2"
                >We will send a recovery link to your email address to reset
                your password</v-card-subtitle
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
                <ForgotPasswordForm
                  :loading="loading"
                  @ForgotPassword="handleForgotPassword"
                />
              </v-card-text>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </div>
  </v-app>
</template>

<script>
import ForgotPasswordForm from "@/components/forms/ForgotPasswordForm";
import { mapActions } from "vuex";
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

 methods:{
	 ...mapActions("AUTH",["forgotPassword"]),
   async handleForgotPassword(data){
     this.loading=true;
     let res =await this.forgotPassword(data)
		 if (res.error) {
       this.loading=false;
       return (this.error={
         dialog:true,
         message:"Invalid Email Address"
       })
     }
     else{
       this.isSuccess=true
       this.loading=false;
       this.error.dialog=true
       this.error.message=res.message
     }
	 }
 }
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