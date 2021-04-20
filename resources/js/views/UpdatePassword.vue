<template>
  <v-app>
    <v-card 
      :loading='loading'
      class="mx-auto my-auto"
      :width="this.$vuetify.breakpoint.mdAndUp ? '30vw' : '80vw'"
    >
      <div class="px-8 py-12">
        <v-card-title class="title login__title flex-column"
          >Change Password</v-card-title
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
          <UpdatePasswordForm 
            @updatePassword="handleUpdatePassword"
            :loading="loading"
          />
        </v-card-text>
      </div>
    </v-card>
  </v-app>
</template>

<script>
import UpdatePasswordForm from "@/components/forms/UpdatePasswordForm";
import { mapActions } from "vuex";
export default {
  name: "resetpassword",
  components: {
    UpdatePasswordForm,
  },
  data() {
    return {
      loading: false,
      isSuccess:false,
      error: {
        dialog: false,
        message: "",
      }
    };
  },

  methods: {
    ...mapActions("AUTH", ["updatePassword"]),
    async handleUpdatePassword(data) {
      console.log(data,'data');
      this.loading = true;
        let res = await this.updatePassword(data);
        console.log(res);
        if (res.error) {
          console.log(res,'res');
          console.log(res.error,'res.error');
          this.loading = false;
          this.error.dialog = true;
          this.error.message = res.statusText;
          this.loading = false;
        } else {
          console.log(res);
          this.isSuccess = true;
          this.loading = false;
          this.error.dialog = true;
          this.error.message = "Password updated successfully";
          setTimeout(() => {
               this.$router.push('/')
          }, 3000);
         
        }
    },
  },
};
</script>