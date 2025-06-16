<template>
    <Head title="Админ-панель"/>
    <div class="flex flex-col h-screen p-10 w-screen">
        <div class="flex justify-between">
            <h1 class="text-4xl font-bold">Приветствую, админ!</h1>
            <Link href="/" methods="get"
                  class="hover:scale-110 transition-all w-max rounded-xl cursor-pointer bg-blue-400 px-6 py-4 font-bold">На главную</Link>
        </div>
        <div class="mt-10 ms-10">
            <h2 class="text-2xl font-bold">Ты можешь работать с:</h2>
            <div class="flex flex-col mt-4 ps-10 gap-5">
                <Link href="/admin/categories" method="get"
                      class="hover:scale-110 transition-all w-max rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold">Категориями</Link>
                <Link href="/admin/variations" method="get"
                      class="hover:scale-110 transition-all w-max rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold">Вариациями</Link>
            </div>
        </div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3'
    export default {
        components: {
            Head,
            Link,
        }
    }
</script>

<style scoped>
    *{
        font-family: 'Montserrat', sans-serif;
    }
</style>
