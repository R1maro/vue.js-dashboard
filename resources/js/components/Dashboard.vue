<script setup>

</script>

<template>
    <h1>
        hellow app!
    </h1>

    <nav>
        <div>
            <router-link to="/">dashboard</router-link>
            <router-link to="/users-list">users-list</router-link>
            <router-link to="/users-form">users-form</router-link>
        </div>
    </nav>
    <main>
        <router-view/>
    </main>
</template>

<style scoped>

</style>
