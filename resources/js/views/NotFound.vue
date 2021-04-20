<template>
  <v-app>
    <v-main class="bg-pri">
      <v-container class="center">
        <v-row>
          <v-col cols="12" md="8" class="mx-auto">
            <!-- <v-img contain height="400" src="@/assets/images/404.svg" /> -->
            <div class="text-center py-10">
              <h1 class="">Page Not Found</h1>
              <v-chip to="/" dark color="error">
                Go back to Home
                </v-chip> 
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "NotFound",
};
</script>