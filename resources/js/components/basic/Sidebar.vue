<template>
  <v-navigation-drawer
    app
    :permanent="!xs"
    :absolute="xs"
    :temporary="xs"
    :mini-variant="!$drawer && !xs"
    v-model="drawer"
    class="sidebar shadow"
    color="#393A3D"
    dark
  >
    <v-list v-if="user">
      <v-list-item two-line :class="`${$drawer ? 'px-4' : 'px-2'}`">
        <v-list-item-avatar>
          <v-avatar  size="38" color="white" class="black--text">
            {{ user.name && user.name[0] }}
          </v-avatar>
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ user.name }}</v-list-item-title>
          <v-list-item-subtitle>{{ user.role }}</v-list-item-subtitle>
          <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>
    <v-divider />
    <slot></slot>
  </v-navigation-drawer>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "main-drawer",
  props: ["user", "hasChild", "xs"],
  data: () => ({
    drawer: null,
  }),
  computed: {
    ...mapGetters(["$drawer"]),
  },
  watch: {
    $drawer(v) {
      if (this.xs) this.drawer = v;
    },
    drawer(v) {
      if (this.xs) this.$emit("drawer", v);
    },
  },
};
</script>
<style lang='scss'>
.sidebar {
  .v-list-group__header {
  }
}
</style>