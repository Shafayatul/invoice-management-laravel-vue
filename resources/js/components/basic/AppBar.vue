<template>
  <div class="app-bar px-3">
    <v-app-bar
      app
      dense
      :dark="theme.dark"
      class="ma-3 rounded shadow"
      :class="theme.dark ? 'grey darken-3' : 'white'"
    >
      <v-app-bar-nav-icon @click="$emit('drawer')" />
      <v-toolbar-title class="pl-0">{{ title }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <slot name="actions" />
    </v-app-bar>
    <v-progress-linear
      top
      left
      absolute
      height="3"
      indeterminate
      color="secondary"
      v-if="loading"
    />
  </div>
</template>

<script>
export default {
  name: "AppBar",
  props: {
    title: String,
    isAuth: Boolean,
    theme: {
      dark: Boolean,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
<style lang="scss">
.app-bar {
  top: 0;
  z-index: 1;
  width: 100%;
  height: 4.5rem;
  position: fixed;
  &::before {
    // @include inset();
    content: "";
    display: block;
    position: absolute;
    transition: none !important;
    background: rgb(238, 238, 238);
    background: linear-gradient(
      0deg,
      rgba(238, 238, 238, 0) 0%,
      rgba(238, 238, 238, 0.8) 70%,
      rgba(238, 238, 238, 1) 100%
    );
  }
}
.theme--dark {
  .app-bar {
    &::before {
      background: rgb(33, 33, 33);
      background: linear-gradient(
        0deg,
        rgba(33, 33, 33, 0) 0%,
        rgba(33, 33, 33, 0.8) 70%,
        rgba(33, 33, 33, 1) 100%
      );
    }
  }
}
</style>
