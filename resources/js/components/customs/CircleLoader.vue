<template>
	<div
		class="circle-loader"
		:style="wrapperStyles"
		:class="{ 
      center,
      'mx-auto': mxAuto
    }"
	>
		<div class="circle" :style="circleStyles"></div>
		<div class="circle" :style="circle2Styles"></div>
		<div class="circle" :style="circle3Styles"></div>
	</div>
</template>

<script>
export default {
	name: 'CircleLoader',
	props: {
		mxAuto: Boolean,
    center:Boolean,
		speed: {
			default: 2,
			type: [String, Number]
		},
		borderWidth: {
			default: 5,
			type: [String, Number]
		},
		size: {
			default: 200,
			type: [String, Number]
		},
		colors: {
			type: Array,
			default: () => (['#0065C3', '#EA0727', '#009688'])
		}
	},
	computed: {
		wrapperStyles() {
			return {
				width: `${this.size}px`,
				height: `${this.size}px`
			}
		},
		circleStyles() {
			return {
				...this.wrapperStyles,
				animationDuration: `${this.speed}s`,
				borderWidth: `${this.borderWidth}px`,
				borderTopColor: this.colors[0],
			}
		},
		circle2Styles() {
			const size = this.percent(this.size, 85)
			const speed = this.percent(this.speed, 85)
			return {
				width: `${size}px`,
				height: `${size}px`,
				animationDuration: `${speed}s`,
				borderWidth: `${this.borderWidth}px`,
				borderTopColor: this.colors[1],
			}
		},
		circle3Styles() {
			const size = this.percent(this.size, 70)
			const speed = this.percent(this.speed, 70)
			return {
				width: `${size}px`,
				height: `${size}px`,
				animationDuration: `${speed}s`,
				borderWidth: `${this.borderWidth}px`,
				borderTopColor: this.colors[2],
			}
		}
	},
	methods: {
		percent(s, p) {
			return (+s / 100) * p
		}
	}
}
</script>

<style lang='scss' scoped>
	.circle-loader {
		position: relative;
		.circle {
			top: 50%;
			left: 50%;
			position: absolute;
			border-radius: 50%;
			border: solid transparent;
			animation: rotating infinite linear;
		}
    &.center {
      top: 50%;
      left: 50%;
      position: absolute;
      transform: translate(-50%,-50%);
    }
	}
	@keyframes rotating {
		0% {
			transform: translate(-50%, -50%) rotate(0deg);
		}
		100% {
			transform: translate(-50%, -50%) rotate(360deg);
		}
	}
</style>
