<script setup>
import { ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import VideoPlayer from "@/components/VideoPlayer.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const videoUrls = [
    "http://localhost:8000/videos/Video1.mp4",
    "http://localhost:8000/videos/Video2.mp4",
    "http://localhost:8000/videos/Video3.mp4",
];

const selectedVideoUrl = ref(videoUrls[0]);
const key = ref(0);

const changeVideo = (newVideoUrl) => {
    selectedVideoUrl.value = newVideoUrl;
};

watch(
    () => selectedVideoUrl.value,
    () => {
        key.value += 1;
    }
);
</script>

<template>
    <Head title="Video" />
    <AppLayout title="Video">
        <div class="py-12">
            <div class="mx-auto max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="p-6 bg-white border-b border-gray-200 flex flex-col items-center"
                    >
                        <VideoPlayer :key="key" :videoUrl="selectedVideoUrl" />
                    </div>
                    <div
                        class="p-6 bg-white border-b border-gray-200 flex flex-col"
                    >
                        <div>
                            <label for="videoUrl">Video URL:</label>
                            <span>{{ selectedVideoUrl }}</span>
                        </div>
                        <div>
                            <h2>Video Links:</h2>
                            <ul>
                                <li>
                                    <a
                                        href="#"
                                        @click="changeVideo(videoUrls[0])"
                                        >Video 1</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        @click="changeVideo(videoUrls[1])"
                                        >Video 2</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        @click="changeVideo(videoUrls[2])"
                                        >Video 3</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
