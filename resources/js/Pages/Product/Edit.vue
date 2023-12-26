<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ContentEditor from "@/Components/ContentEditor.vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/themes/light.css";

const props = defineProps({
    product: Object,
    productImages: Array,
});

const form = useForm({
    name: props.product.name,
    category: props.product.category,
    description: props.product.description,
    files: [],
    date_and_time: props.product.date_and_time,
});

const submit = async () => {
    const formData = new FormData();
    formData.append("name", form.name);
    formData.append("category", form.category);
    formData.append("description", form.description);
    formData.append("date_and_time", form.date_and_time);

    form.files.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
    });

    try {
        await router.post(
            route("products.update", { product: props.product.id }),
            formData
        );
    } catch (error) {
        // Handle any errors
        console.error(error);
    }
};

const onFilesChange = (event) => {
    const fileInput = event.target;
    const newFiles = Array.from(fileInput.files);
    console.log(newFiles);
    if (newFiles.length > 0) {
        const validFiles = newFiles.filter((file) => file instanceof File);
        form.files.push(...validFiles);
    }
};

const removeFile = (index) => {
    form.files.splice(index, 1);
};

onMounted(() => {
    flatpickr(".flatpickr", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultHour: 8,
        defaultDate: props.product.date_and_time,
    });

    if (props.productImages && props.productImages.length > 0) {
        props.productImages.forEach((image) => {
            const imageUrl = image.image_path;

            fetch(imageUrl)
                .then((response) => response.blob())
                .then((blob) => {
                    const filename = image.name;
                    const imageFile = new File([blob], filename, {
                        type: "image/*",
                    });
                    form.files.push(imageFile);
                })
                .catch((error) => {
                    console.error("Error fetching image:", error);
                });
        });
    }
});
</script>

<template>
    <Head title="Product Create" />
    <AppLayout title="Product">
        <AuthenticatedLayout>
            <div class="py-12">
                <div class="mx-auto max-w-7xl">
                    <div
                        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <form @submit.prevent="submit">
                                <div>
                                    <InputLabel for="name" value="Name" />

                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <div class="my-6 form-group">
                                    <label
                                        for="category"
                                        class="block mb-2 text-sm font-medium text-gray-900"
                                        >Category</label
                                    >
                                    <select
                                        id="category"
                                        class="form-control"
                                        v-model="form.category"
                                        required
                                        autofocus
                                    >
                                        <option value="Cat1">Cat1</option>
                                        <option value="Cat2">Cat2</option>
                                        <option value="Cat3">Cat3</option>
                                    </select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>

                                <div class="my-6">
                                    <label
                                        for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900"
                                    >
                                        Description
                                    </label>
                                    <ContentEditor v-model="form.description" />

                                    <div
                                        v-if="form.errors.description"
                                        class="text-sm text-red-600"
                                    >
                                        {{ form.errors.description }}
                                    </div>
                                </div>

                                <div class="my-6 form-group">
                                    <label
                                        for="files"
                                        class="block mb-2 text-sm font-medium text-gray-900"
                                    >
                                        Files
                                    </label>
                                    <input
                                        id="files"
                                        type="file"
                                        class="form-control"
                                        multiple
                                        @change="onFilesChange"
                                    />
                                    <!-- Display the selected files -->
                                    <div
                                        v-if="form.files.length > 0"
                                        class="mt-2"
                                    >
                                        Selected Files:
                                        <ul>
                                            <li
                                                v-for="(
                                                    file, index
                                                ) in form.files"
                                                :key="file.name"
                                            >
                                                {{ file.name }}
                                                <button
                                                    class="btn btn-danger"
                                                    @click="removeFile(index)"
                                                >
                                                    Remove
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="my-6">
                                    <InputLabel
                                        for="date_and_time"
                                        value="DateTime"
                                    />

                                    <TextInput
                                        id="date_and_time"
                                        type="date"
                                        class="mt-1 block w-full flatpickr"
                                        v-model="form.date_and_time"
                                        required
                                        autofocus
                                        autocomplete="false"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.date_and_time"
                                    />
                                </div>
                                <PrimaryButton
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Submit
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </AppLayout>
</template>
