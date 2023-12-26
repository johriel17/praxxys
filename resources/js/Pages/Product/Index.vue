<script setup>
import { ref, watch } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    products: Object,
    filters: Object,
});

let search = ref(props.filters.search);

let selectedCategory = ref(props.filters.category);

watch([search, selectedCategory], ([searchValue, category]) => {
    router.get(
        "/products",
        { search: searchValue, category },
        {
            preserveState: true,
            replace: true,
        }
    );
});

const form = useForm({});

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route("products.destroy", id));
    }
}
</script>

<template>
    <Head title="Product" />
    <AppLayout title="Product">
        <div class="card col-12 p-5">
            <div class="mb-2">
                <Link
                    href="/products/create"
                    as="button"
                    class="btn btn-primary"
                >
                    <p>Create</p>
                </Link>
            </div>
            <div class="row mb-3">
                <div class="col-8">
                    <div class="form-group">
                        <input
                            v-model="search"
                            type="text"
                            class="form-control"
                            placeholder="Search products..."
                        />
                    </div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-5 text-right">
                            <label for="category" class="form-label"
                                >Filter by Category:</label
                            >
                        </div>
                        <div class="col-7">
                            <select
                                v-model="selectedCategory"
                                @change="updateCategoryFilter"
                                class="form-control"
                                id="category"
                            >
                                <option value="">All Categories</option>
                                <!-- Add options dynamically based on your categories -->
                                <option value="Cat1">Cat1</option>
                                <option value="Cat2">Cat2</option>
                                <option value="Cat3">Cat3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover table-md">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.id">
                        <td style="width: 20%" class="text-center">
                            {{ product.name }}
                        </td>
                        <td style="width: 15%" class="text-center">
                            {{ product.category }}
                        </td>
                        <td style="width: 50%">{{ product.description }}</td>
                        <td style="width: 15%" class="text-center">
                            <Link
                                :href="'/products/' + product.id + '/edit'"
                                as="button"
                                class="btn btn-outline-warning"
                            >
                                Edit
                            </Link>

                            <button
                                class="btn btn-outline-danger"
                                @click="destroy(product.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <Pagination :data="products" />
        </div>
    </AppLayout>
</template>

<script>
export default {
    methods: {
        updateCategoryFilter() {
            // Manually trigger the watch function when the category changes
            this.$watchEffect();
        },
    },
};
</script>
