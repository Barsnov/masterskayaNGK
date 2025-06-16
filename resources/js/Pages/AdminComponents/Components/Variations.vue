<template>
    <Head title="Вариации"/>
    <div class="flex flex-col h-screen px-10 pt-5 w-screen">
        <Link class="hover:scale-105 transition-all w-max text-center text-2xl rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold" href="/admin">Админ-панель</Link>
        <h1 class="text-4xl mt-5 font-bold">Добро пожаловать в раздел "Вариации"</h1>
        <div class="flex flex-col">
            <div class="mt-4 flex items-center">
                <form @submit.prevent="addVariation" class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <input v-model="form.name" class="block text-2xl max-w-lg rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0"
                               placeholder="Название вариации">
                        <input v-model="form.component" class="block text-2xl max-w-lg rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0"
                               placeholder="Компонент">
                        <button type="submit" class="flex rounded bg-black text-amber-50 text-xl px-4 py-2 cursor-pointer">Создать вариацию</button>
                    </div>
                </form>
            </div>

            <div class="mt-10 flex justify-between">
                <div class="w-2/5 flex flex-col items-center">
                    <h1 class="text-4xl font-bold text-center">Список вариаций</h1>
                    <div class="flex flex-col mt-6 w-max p-10 gap-4 overflow-y-scroll snap-y h-96">
                        <div class="flex snap-start items-center justify-between gap-10 opacity-50 border-2 border-black transition-all px-4 rounded text-xl font-bold py-2
                     hover:scale-110 hover:opacity-100"
                             v-for="(variation, index) in variations">
                            <h1>{{index+1}}. {{variation.name}}</h1>
                            <div class="flex gap-4 items-center">
                                <div class="bg-yellow-300 rounded px-2 py-1 cursor-pointer" @click="targetVariation(variation.id)">Изменить</div>
                                <div class="bg-red-600 rounded px-2 py-1 cursor-pointer"  @click="deleteVariation(variation.id)">Удалить</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="variationCurrent !== ''" class="flex w-3/5 h-max flex-col border-2 border-black p-6 rounded">
                    <div class="flex gap-2 items-center">
                        <label class="text-2xl font-bold">Название:</label>
                        <input @input="renameVariation" class="block text-xl max-w-96 rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0"
                               v-model="variationCurrent.name">
                        <div class="relative">
                            <input @input="renameVariationComponent" class="block text-xl max-w-96 rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0"
                                   v-model="variationCurrent.component">
                            <p class="absolute right-5 top-2 text-gray-400 z-10 font-bold text-xl">.vue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Head, Link} from "@inertiajs/vue3";
    export default{
        props:{
            variations:{
                type: Object,
                required: true
            }
        },

        components:{
            Head,
            Link
        },

        data(){
            return{
                variationCurrent: '',
                imagesCategory: '',

                form: {
                    name: null,
                },

                imagesForm: [],
            }
        },

        methods:{
            addVariation(){
                this.$inertia.post('/admin/variations/variation', this.form);
            },
            deleteVariation(variationId){
                this.$inertia.delete(`/admin/variations/variation/delete/${variationId}`);
            },
            targetVariation(variationId){
                this.variationCurrent = this.variations.find(item => item.id === variationId);
            },
            renameVariation(){
                this.$inertia.put('/admin/variations/variation/up/' + this.variationCurrent.id, {name: this.variationCurrent.name})
            },
            renameVariationComponent(){
                this.$inertia.put('/admin/variations/variation/component/up/' + this.variationCurrent.id, {component: this.variationCurrent.component})
            },
        },
    }
</script>

<style scoped>

</style>
