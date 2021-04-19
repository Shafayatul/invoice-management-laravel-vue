<template>
  <v-dialog persistent v-model="dialog" :width="width || 400">
    <v-card :disabled="loading">
      <v-card-title class="headline font-weight-regular">{{
        title ? title : "Are you sure to continue?"
      }}</v-card-title>
      <v-card-subtitle v-if="subtitle" class="mt-n2">{{
        subtitle
      }}</v-card-subtitle>
      <v-card-text v-if="text">{{ text }}</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red" text @click="$emit('input', false), $emit('no')"
          >NO</v-btn
        >
        <v-btn color="green darken-1" text @click="$emit('yes')">Yes</v-btn>
      </v-card-actions>
    </v-card>
    <circle-loader
      center
      v-if="loading"
      size="64"
      speed="1"
      border-width="3"
    ></circle-loader>
  </v-dialog>
</template>

<script>

import CircleLoader from "@/components/customs/CircleLoader.vue";
export default {
     components:{
          CircleLoader
     },
  name: "DConfirm",
  props: {
    text: String,
    title: String,
    subtitle: String,
    value: Boolean,
    loading: {
      type: Boolean,
      default: false,
    },
    width: Number | String,
  },
  computed: {
    dialog: {
      get() {
        return this.value;
      },
      set(v) {
        this.$emit("input", v);
      },
    },
  },
};
</script>
