<template>
    <Head title="Начальная страница"/>
    <h1 class="animate-bounce mb-40" @click="clickCount++">Мастерская</h1>
    <div class="flex flex-col items-center gap-4">
        <select v-model="selectOption" name="selectCategory" class="w-full select-auto p-2 rounded text-xl bold">
            <option :value="null" disabled selected>Выберите категорию</option>
            <option :value="category.id" :key="category.id" v-for="category in filteredCategory">{{category.title}}</option>
        </select>
        <h2 @click="startGame">Начать игру</h2>
        <Link v-if="ifAdminButton" class="hover:scale-110 transition-all w-full text-center text-2xl rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold" href="/admin">Админ-панель</Link>
    </div>
</template>

<script>
import {Head, Link} from "@inertiajs/vue3";

    export default{
        components:{
            Head,
            Link
        },

        props:{
            categories:{
                type: Object,
                required: true
            },

            images:{
                type: Object,
                required: true
            }
        },

        data(){
            return{
                selectOption: null,
                clickCount: 0,
                ifAdminButton: false,
            }
        },

        methods: {
            startGame(){
                if (this.selectOption){
                    this.$inertia.get('/app', {category: this.selectOption})
                }else{
                    alert('Выберите категорию')
                }
            }
        },

        watch:{
            clickCount(newValue){
                if (newValue === 10){
                    this.ifAdminButton = true
                }
            }
        },

        computed:{
            filteredCategory(){
                return this.categories.filter((category)=>{
                    return this.images.filter(image => image.category_id === category.id).length === 5
                });
            }
        }
    }
</script>

<style scoped>
    h2{
        font-size: 1.7rem;
        font-weight: bold;
        z-index: 1;
        width: 20rem;
        height: 4rem;
        background-color: rgb(95, 235, 165);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 1rem;
        transition: .5s;
        border: 0.2rem solid transparent;
        box-shadow: 0.3rem 0.2rem 0.5rem rgba(0, 0, 0, 0.6);
    }

    h2:hover{
        transition: .5s;
        transform: scale(1.1);
        border: 0.2rem solid rgb(6, 122, 0);
    }

    h1{
        font-family: "Montserrat", sans-serif;
        font-size: 3rem;
        font-weight: bold;
    }
</style>
