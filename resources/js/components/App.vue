<script setup>

</script>

<template>
    <h1>
        hellow app!
    </h1>

    <nav>
        <div>
            <a href="" class="btn btn-secondary">
                <router-link to="/">dashboard</router-link>
            </a>
            <a href="" class="btn btn-secondary">
                <router-link to="/invoice">invoice</router-link>
            </a>
            <a href="" class="btn btn-secondary">
                <router-link to="/users-form">users-form</router-link>
            </a>
            <a href="" class="btn btn-secondary">
                <router-link to="/users-list">users-list</router-link>
            </a>


        </div>
    </nav>
    <main>
        <router-view/>
    </main>
</template>

<style scoped>

</style>
