<template>
    <Head title="Категории"/>
    <div class="flex flex-col h-screen px-10 pt-5 w-screen">
        <Link class="hover:scale-105 transition-all w-max text-center text-2xl rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold" href="/admin">Админ-панель</Link>
        <h1 class="text-4xl mt-5 font-bold">Добро пожаловать в раздел "Категории"</h1>
        <div class="flex flex-col">
            <div class="mt-4 flex items-center">
                <form @submit.prevent="addCategory" class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <input v-model="form.name" class="block text-2xl max-w-lg rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 placeholder:text-xl focus:outline focus:outline-0"
                               placeholder="Название категории">z
                        <select v-model="form.select" class="w-full select-auto p-2 rounded text-xl bold">
                            <option :value="null" selected hidden>Выберите вариацию</option>
                            <option v-for="item in variations" :key="item.id" :value="item.id">{{item.name}}</option>
                        </select>
                        <button type="submit" class="flex rounded bg-black text-amber-50 text-xl px-4 py-2 cursor-pointer   ">Создать категорию</button>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-bold text-2xl">Добавить изображения к категории</h4>
                        <div class="flex mt-2 gap-2 items-center">
                            <div v-for="(image, index) in imagesForm" :key="index" class="relative flex flex-col w-max gap-2">
                                <img :src="image.url" alt="Загруженное изображение" class="w-24 h-24 object-cover rounded-2xl"/>
                                <div @click="removeImage(index)" class="hover:opacity-70 opacity-0 transition-all absolute rounded-2xl text-center
                                    text-amber-50 text-4xl cursor-pointer w-24 h-24 bg-red-600 flex justify-center items-center">-</div>
                            </div>
                            <div v-show="imagesForm.length < 5" class="w-24 h-24 relative block border-black border-2 rounded-2xl overflow-hidden">
                                <label class="text-4xl absolute top-1/2 left-1/2" style="transform: translate(-50%, -50%)">+</label>
                                <input type="file" id="img" accept="image/jpeg, image/bmp, image/png" name="img" class="absolute w-24 h-24 file:w-24 file:h-24 file:border-0 file:bg-transparent
                                file:text-transparent" multiple @change="handleFileUpload"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-10 flex justify-between">
                <div class="w-2/5 flex flex-col items-center">
                    <h1 class="text-4xl font-bold text-center">Список категорий</h1>
                    <div class="flex flex-col mt-6 w-max px-10 py-2 gap-2 overflow-y-scroll snap-y h-2/3">
                        <div class="flex snap-start items-center justify-between gap-10 opacity-50 border-2 border-black transition-all px-4 rounded text-xl font-bold py-2
                     hover:scale-110 hover:opacity-100"
                             v-for="(category, index) in categories">
                            <h1>{{index+1}}. {{category.title}}</h1>
                            <div class="flex gap-4 items-center">
                                <div class="bg-yellow-300 rounded px-2 py-1 cursor-pointer" @click="targetCategory(category.id)">Изменить</div>
                                <div class="bg-red-600 rounded px-2 py-1 cursor-pointer"  @click="deleteCategory(category.id)">Удалить</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="categoryCurrent !== ''" style="margin-top: -10rem" class="flex w-3/5 h-max flex-col border-2 border-black p-6 rounded">
                    <div class="flex gap-2 items-center">
                        <label class="text-2xl font-bold">Название:</label>
                        <input @input="renameCategory" class="block text-xl max-w-96 rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0"
                               v-model="categoryCurrent.title">
                        <select @change="renameVariation" v-model="categoryCurrent.variation_id" class="w-full select-auto p-2 rounded text-xl bold">
                            <option :value="categoryCurrent.variation_id" selected disabled>{{ variations.find(v => v.id === categoryCurrent.variation_id)?.name }}</option>
                            <option :hidden='categoryCurrent.variation_id === item.id' :value="item.id" v-for="item in variations" >{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="flex mt-5 gap-2 flex-wrap">
                        <div v-for="image in filteredImages" class="flex flex-col gap-2">
                            <div class="relative flex rounded-2xl overflow-hidden">
                                <img :alt="image.name" class="w-72 h-48 object-cover" :src="`/storage/${image.url}`"/>
                                <div @click="removeImageForCurrentCategory(image.id)" class="hover:opacity-70 opacity-0 transition-all absolute text-center
                        text-amber-50 text-4xl cursor-pointer w-72 h-48 bg-red-600 flex justify-center items-center">-</div>
                            </div>
                            <input @input="renameImage(image.id, image.answer)" v-model="image.answer"
                                   class="block text-xl max-w-96 rounded grow py-2 pl-3 pr-3 text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0">
                        </div>

                        <div v-show="filteredImages.length < 5" class="">
                            <div class="w-72 h-48 relative block border-black border-2 rounded-2xl overflow-hidden">
                                <label class="text-4xl absolute top-1/2 left-1/2" style="transform: translate(-50%, -50%)">+</label>
                                <input type="file" id="img" style="font-size: 0;" accept="image/jpeg, image/bmp, image/png" name="img" class="absolute w-72 h-48 file:w-24 file:h-24 file:border-0 file:bg-transparent
                                file:text-transparent" multiple @input="addImageForCategory"/>
                            </div>
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
            categories:{
                type: Object,
                required: true
            },

            images:{
                type: Object,
                required: true
            },

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
                categoryCurrent: '',
                imagesCategory: '',
                variation: '',

                form: {
                    name: null,
                    select: null,
                    files: [],
                },

                imagesForm: [],
            }
        },

        methods:{
            addCategory(){
                this.imagesForm.forEach((file)=>{
                    this.form.files.push(file.file)
                })
                this.imagesForm = [];
                this.$inertia.post('/admin/categories/category', this.form);
            },
            deleteCategory(categoryId) {
                this.$inertia.delete(`/admin/categories/category/${categoryId}`);
            },
            targetCategory(categoryId){
                this.categoryCurrent = this.categories.find(item => item.id === categoryId);
            },
            renameCategory(){
                this.$inertia.put('/admin/categories/category/up/' + this.categoryCurrent.id, {title: this.categoryCurrent.title})
            },

            renameVariation(){
                this.$inertia.put('/admin/categories/category/variation/up/' + this.categoryCurrent.id, {id: this.categoryCurrent.variation_id})
            },
            //Загрузка изображений, при создании категории
            handleFileUpload(event) {
                if (this.imagesForm.length + Array.from(event.target.files).length > 5) {
                    alert("Можно загрузить максимум 5 изображений.");
                    const files = Array.from(event.target.files).slice(0, 5-this.imagesForm.length)
                    // Преобразуем файлы в объекты с URL
                    files.forEach((file) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagesForm.push({
                                file, // оригинальный файл
                                url: e.target.result, // предварительный просмотр
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                    // return
                }else{
                    const files = Array.from(event.target.files)
                    // Преобразуем файлы в объекты с URL
                    files.forEach((file) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagesForm.push({
                                file, // оригинальный файл
                                url: e.target.result, // предварительный просмотр
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                }
                // Сбрасываем значение input, чтобы можно было снова загрузить те же файлы
                event.target.value = null;
            },
            //Удаление загруженных изображений, при создании категории
            removeImage(index){
                this.imagesForm.splice(index, 1);
            },
            //Изменение ответа у картинки выбранной категории
            renameImage(imageId, value){
                this.$inertia.put(`/admin/images/image/up/${imageId}`, {answer: value});
            },

            addImageForCategory(event){
                if(event.target.files.length > 5){
                    const imagesForm = event.target.files.slice(0, 4)
                    this.$inertia.post('/admin/images/image', {category: this.categoryCurrent.id, images: imagesForm});
                }else{
                    this.$inertia.post('/admin/images/image', {category: this.categoryCurrent.id, images: event.target.files});
                }
            },

            removeImageForCurrentCategory(imageId){
                this.$inertia.delete(`/admin/images/image/${imageId}`)
            }
        },

        computed:{
            filteredImages(){
                return this.images.filter(item => item.category_id === this.categoryCurrent.id);
            }
        }
    }
</script>

<style scoped>

</style>
