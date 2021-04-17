<template>
	<v-btn
		fab
		right
		fixed
		dark
		bottom
		color="primary"
		class="d-none d-sm-flex"
		@click="$emit('click')"
	>
		<v-icon>mdi-plus</v-icon>
	</v-btn>
</template>

<script>
export default {
	
}
</script>