<template>
    <div>
        <div>
            <label for="keyword">Search:</label>
            <input
                type="text"
                v-model="keyword"
                @input="searchProducts"
                id="keyword"
            />
        </div>

        <div>
            <label for="category">Filter by Category:</label>
            <select v-model="category" @change="filterByCategory" id="category">
                <option value="">All</option>
                <!-- Add category options dynamically -->
                <option v-for="cat in categories" :value="cat" :key="cat">
                    {{ cat }}
                </option>
            </select>
        </div>

        <ul>
            <li v-for="product in products.data" :key="product.id">
                {{ product.name }} - {{ product.description }}
                <button @click="removeProduct(product.id)">Remove</button>
            </li>
        </ul>

        <div>
            <button
                @click="loadProducts(products.prev_page_url)"
                :disabled="!products.prev_page_url"
            >
                Previous
            </button>
            <button
                @click="loadProducts(products.next_page_url)"
                :disabled="!products.next_page_url"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: {},
            keyword: "",
            category: "",
            categories: [],
        };
    },
    mounted() {
        this.loadProducts("/products");
    },
    methods: {
        loadProducts(url) {
            Inertia.get(url, {
                params: { keyword: this.keyword, category: this.category },
            })
                .then((response) => {
                    this.products = response;
                    this.categories = [
                        ...new Set(
                            response.data.data.map(
                                (product) => product.category
                            )
                        ),
                    ];
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
        searchProducts() {
            this.loadProducts("/products");
        },
        filterByCategory() {
            this.loadProducts("/products");
        },
        removeProduct(productId) {
            if (confirm("Are you sure you want to remove this product?")) {
                Inertia.delete(`/products/${productId}`)
                    .then(() => {
                        this.loadProducts("/products");
                    })
                    .catch((error) => {
                        console.error("Error deleting product:", error);
                    });
            }
        },
    },
};
</script>
