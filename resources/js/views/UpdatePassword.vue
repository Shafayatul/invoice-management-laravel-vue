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
            <v-img
              contain
              height="300"
              :src="require('@/assets/my_password.svg')"
            />
          </v-col>
      
        <v-col>
          <v-card :loading="loading" class="mx-auto mx-md-0 my-auto" max-width="480">
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
        </v-col>
      </v-row>
    </div>
  </v-app>
</template>

<script>
import UpdatePasswordForm from "@/components/forms/UpdatePasswordForm";
import { mapActions } from "vuex";
export default {
    name: "resetpassword",
    components: {
        UpdatePasswordForm
    },
    data() {
        return {
            loading: false,
            isSuccess: false,
            error: {
                dialog: false,
                message: ""
            }
        };
    },

    methods: {
        ...mapActions("AUTH", ["updatePassword"]),
        async handleUpdatePassword(data) {
            this.loading = true;
            let res = await this.updatePassword(data);

            if (res.error) {
                this.loading = false;
                this.error.dialog = true;
                this.error.message = res.statusText;
                this.loading = false;
            } else {
                this.isSuccess = true;
                this.loading = false;
                this.error.dialog = true;
                this.error.message = "Password updated successfully";
                setTimeout(() => {
                    this.$router.push("/");
                }, 3000);
            }
        }
    }
};
</script>
