<template>
  <v-list-group v-if="hasChildrens" :value="false" :prepend-icon="icon">
    <template v-slot:activator>
      <v-list-item-title>{{ name }}</v-list-item-title>
    </template>
    <slot></slot>
  </v-list-group>
  <v-list-item
    link
    v-else
    :to="to"
    :exact="exact"
    :active="$route.path === to"
    class="my-1 text--white"
    @click="$emit('click')"
  >
    <v-list-item-icon v-if="icon">
      <v-icon>{{ icon }}</v-icon>
    </v-list-item-icon>
    <v-list-item-title>{{ name }}</v-list-item-title>
  </v-list-item>
</template>

<script>
export default {
  name: "SidebarNavListItem",
  props: ["icon", "name", "to", "active", "exact", "hasChildrens"],
};
</script>