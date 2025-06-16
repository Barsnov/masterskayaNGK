<template>
    <Head title="Начальная"/>
    <Back ref="backMenu" @PartOnMap="PartOnMap" @checkedPasswordSuccess="checkedPasswordSuccess"
          @bookInCloseShelf="bookInCloseShelf" @pieceOnTrashFull="pieceOnTrashFull" @controller="controller" @closeUnderMenu="closeUnderMenu"
          @opacityMenuOne="opacityMenuOne" @inventoryItem="inventoryItem" @keiInShelf="keiInShelf" :imagesForBg="imagesForBg"></Back>

    <Menu ref="underCloseMenu" @targetInventory="targetInventory"></Menu>
    <Task @endGame="endGameBut" :images="images" ref="task" @taskedEmit="tasked" @removeBlockAllComponents="removeBlockAllComponents" @blockAllComponents = "blockAllComponents"></Task>
    <Hint @click="closeUnderMenu(true)"></Hint>

    <Link v-if="ifWelcome" class="absolute z-50 top-10 animate-bounce w-max hover:scale-110 transition-all text-center text-2xl rounded-xl cursor-pointer bg-amber-200 px-6 py-4 font-bold" href="/">На главную</Link>
</template>


<script>
import {Head, Link} from '@inertiajs/vue3'
import Back from "./Components/Back.vue"
import Menu from "./Components/Menu.vue"
import Task from "./Components/Task.vue"
import Hint from "./Components/Hints.vue"
export default{
    props:{
        images:{
            type: Object,
            required: true
        },
    },

    components: {
        Link,
        Back,
        Menu,
        Task,
        Hint,
        Head
    },

    data(){
        return{
            imagesForBg:[],
            emitForPieceOfPaper: true,
            itemCloseUnderMenu: '',
            emitForPartOnMap: '',
            emitForPassword: true,

            ifWelcome: false,
        }
    },

    methods: {
        async loadImage() {
            try{
                const response = await fetch('/Trading.txt');
                this.imagesForBg = await response.json();
                if (!response.ok) {
                    throw new Error('Инет подключи');
                }
            }catch (error) {
                console.error('Файл умер при извлечении...', error);
            }
        },

        closeUnderMenu(item){
            this.$refs.underCloseMenu.under_menu_open(item)
        },
        opacityMenuOne(value){
            this.$refs.underCloseMenu.opacityMenuOne(value)
        },
        inventoryItem(item){
            this.$refs.underCloseMenu.inventory(item)
        },
        targetInventory(item){
            item.addEventListener("touchstart", this.$refs.backMenu.handleTouchStart, {passive: true});
            item.item_move = true
            item.style.position = "absolute";
            item.style.transition = ".1s"
        },
        keiInShelf(item){
            this.$refs.underCloseMenu.inventoryDelete(item)
        },
        //ВЫПОЛНЕННЫЕ ЗАДАНИЯ
        pieceOnTrashFull(){
            if (this.emitForPieceOfPaper){
                this.$refs.task.startTask(".trash", false)
                this.emitForPieceOfPaper = false
            }
        },
        controller(item){
            this.$refs.underCloseMenu.inventoryDelete(item)
            this.timer = setTimeout(()=>{
                this.$refs.task.startTask(".interactiveBoard", false)
            }, 1500)

            document.querySelector(".mapOnScreen").style.zIndex = "0"
        },
        bookInCloseShelf(){
            this.$refs.task.startTask(".shelf_close_book", false)
        },
        checkedPasswordSuccess(){
            if (this.emitForPassword){
                this.$refs.task.startTask(".screen_unlocked", false)
                this.emitForPassword = false;
            }
        },
        PartOnMap(){
            this.emitForPartOnMap ++
            if (this.emitForPartOnMap === 2){
                this.timer = setTimeout(()=>{
                    this.$refs.task.startTask(".mapOnScreen", false)
                }, 1000);

                for (let item of document.querySelectorAll(".Part_map")){
                    this.$refs.backMenu.deleteOpenHint(item)
                }
            }
        },
        //
        blockAllComponents(){
            document.querySelectorAll("#app *").forEach(components => {
                if (components.className !== "card"){
                    components.classList.add("blocked")
                }
            })
        },
        removeBlockAllComponents(){
            document.querySelectorAll("#app *").forEach(components => {
                components.classList.remove("blocked")
            })
        },
        //Наполнение массива с выполненными заданиями
        tasked(item){
            this.$refs.underCloseMenu.tasked(item)
        },
        //На главную запуск
        endGameBut(){
            this.timer = setTimeout(()=>{this.ifWelcome = true}, 4500)
        }
    },

    mounted() {
        this.loadImage();
    },
}
</script>

<style>
.blocked{
    pointer-events: none;
}
</style>
