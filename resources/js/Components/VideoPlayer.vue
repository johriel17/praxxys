<script setup>
import { onMounted, onBeforeUnmount, watch, ref } from "vue";
import videojs from "video.js";
import "video.js/dist/video-js.css";

const props = defineProps(["videoUrl"]);
const videoPlayerRef = ref(null);

let player;

onMounted(() => {
    initializePlayer();
});

onBeforeUnmount(() => {
    if (player) {
        player.dispose();
    }
});

watch(
    () => props.videoUrl,
    (newVideoUrl) => {
        initializePlayer(newVideoUrl);
        console.log("New Video URL:", newVideoUrl);
    }
);

function initializePlayer(newVideoUrl) {
    // Check if the videoPlayerRef is ready
    if (videoPlayerRef.value) {
        if (player) {
            player.dispose();
        }

        player = videojs(videoPlayerRef.value, {}, function onPlayerReady() {
            if (newVideoUrl) {
                // Change the video source when the URL changes
                player.src(newVideoUrl);
                player.load();
            }
            // You can add custom configurations here if needed
        });
    }
}
</script>

<template>
    <video
        ref="videoPlayer"
        class="video-js vjs-default-skin custom-video-player"
        controls
    >
        <source :src="videoUrl" type="video/mp4" />
    </video>
</template>

<style scoped>
.custom-video-player {
    width: 80%; /* Set the width to 100% of the parent container */
    height: auto; /* Let the height adjust automatically to maintain the aspect ratio */
}
</style>
