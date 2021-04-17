<template>
  <v-dialog :width="width" persistent v-model="vModel" class="dialog">
    <v-card class="dialog__card">
      <v-card-title>{{ title || "Dialog Title" }}</v-card-title>
      <v-card-subtitle>{{
        subtitle || "Lorem ipsum dolor sit amet consectetur adipisicing elit."
      }}</v-card-subtitle>
      <v-card-text>
        <slot></slot>
      </v-card-text>
      <v-card-actions>
        <slot name="actions"> </slot>
      </v-card-actions>
      <div v-if="loading" class="dialog__overlay">
        <CircleLoader
          speed="1"
          size="96"
          border-width="4"
          class="dialog__loader"
        />
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "DialogWrapper",
  props: {
    title: String,
    subtitle: String,
    loading: Boolean,
    persistent: Boolean,
    value: {
      type: Boolean,
      required: true,
    },
    width: {
      default: "500",
      type: [String, Number],
    },
  },
  computed: {
    vModel: {
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
<style lang="scss" scoped>
.dialog {
  &__card {
    position: relative;
  }
  &__overlay,
  &__loader {
    position: absolute;
  }
  &__overlay {
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }
  &__loader {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
}
.theme--dark .dialog__overlay {
  background-color: rgba(white, 0.25);
}
.theme--light .dialog__overlay {
  background-color: rgba(white, 0.65);
}
</style>