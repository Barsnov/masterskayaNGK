<template>
    <div ref="menu" :style="opacityMenu" class="menu justify-between pb-5">
        <div class="flex flex-col items-center">
            <div v-for="(image, index) in optionInMenu" :key="index">
                <img :ref="image.ref" class="option_in_menu" :src="image.src" :alt="image.alt" :style="{marginLeft: image.marginLeft}">
            </div>
        </div>

        <div ref="under_menu" :state="this.state" class="under_menu" :class="{ hover: isHoveredUnderMenu }">
            <h3>{{ underMenuAlt }}</h3>
            <div ref="underMenuItem" class="underMenuItem"></div>
        </div>

        <Link class="z-50 top-10 w-max transition-all text-center text-2xl rounded-xl cursor-pointer px-6 py-4 font-bold" href="/">
            <img class="w-14 ms-3" :src="'/images/Logout.png'" alt="Выход">
        </Link>
    </div>
</template>

<script>
    import {Link} from "@inertiajs/vue3";

    export default{
        components: {Link},
        data(){
            return{
                optionInMenu:[
                    {src: '../images/Backpack.svg', alt: 'Инвентарь', ref: 'backpack'},
                    {src: '../images/To-do_list.svg', alt: 'Выполнено', ref: 'toDoList', marginLeft: '.5rem'},
                ],
                //Таймер для всех таймингов
                timer: '',
                //Состояние для меню, чтобы проверять какой анимации быть в функции
                state: false,
                isHovered: false,
                isHoveredUnderMenu: true,
                elementInMenu: '',
                underMenuAlt: '',
                opacityMenu: "opacity: 0",
                //Инвентарь
                inventoryItem: [],
                divForItem:'',
                //Задания
                task: [],
                h5ForItem: ''
            }
        },

        methods:{
            under_menu_open(item){
                //Чтобы при выходе на главную страницу (фон) меню уходило
                if (item === true){
                    this.$refs.under_menu.style.width = "0"
                    this.underMenuAlt = "";

                    this.isHoveredUnderMenu = true
                    this.$refs.under_menu.state = false

                    Array.from(document.getElementsByClassName('option_in_menu')).forEach(element => {
                        element.style.transform = "scale(.7)"
                    });

                    this.clearUnderMenu()
                } else{
                    this.$refs.backpack[0]?.removeEventListener('click', this.under_menu_open)
                    this.$refs.toDoList[0]?.removeEventListener('click', this.under_menu_open)

                    Array.from(document.getElementsByClassName('option_in_menu')).forEach(element => {
                        element.style.transform = "scale(.7)"
                    });
                    event.target.style.transform = "scale(.9)"

                    //ЗДЕСЬ ПРОВЕРКА НА ТО - ЧТО ИМЕННО НАМ ИНТЕРЕСНО ПО ИДЕЕ)
                    this.under_menuChosen(item.target)

                    //Открывашка для под-меню (между айтемами)
                    if (!this.$refs.under_menu.state){
                        this.$refs.under_menu.style.width = "40rem"
                        this.timer = setTimeout(() =>{
                            this.isHoveredUnderMenu = false
                            this.$refs.under_menu.state = true

                            this.$refs.backpack[0]?.addEventListener('click', this.under_menu_open)
                            this.$refs.toDoList[0]?.addEventListener('click', this.under_menu_open)
                        }, 1000)

                    }else{
                        this.isHoveredUnderMenu = true

                        this.timer = setTimeout(() =>{
                            this.$refs.under_menu.style.width = "0"
                        }, 500)

                        this.timer = setTimeout(() =>{
                            this.$refs.under_menu.style.width = "40rem"
                        }, 1500)

                        this.timer = setTimeout(() =>{
                            this.isHoveredUnderMenu = false
                            this.$refs.backpack[0]?.addEventListener('click', this.under_menu_open)
                            this.$refs.toDoList[0]?.addEventListener('click', this.under_menu_open)
                        }, 2000)
                    }
                }
            },
            //Определяем: где, что и когда будет
            under_menuChosen(item){
                this.timer = setTimeout(() =>{
                    this.underMenuAlt = item.alt
                }, 700)

                if (item.alt=== "Инвентарь"){
                    this.clearUnderMenu()
                    this.$refs.underMenuItem.style.flexDirection = "row"
                    if (this.inventoryItem.length !== 0){
                        this.inventoryItem.forEach((item) =>{
                            this.$refs.underMenuItem.appendChild(this.stilizationDiv(item))
                            this.$emit("targetInventory", item)
                        })
                        //Добавляем возможность передвижения нашего заветного ключика или пультика
                    }else{
                        const text = document.createElement("h5")
                        text.style.fontSize = "2rem"
                        text.style.marginTop = "2rem"
                        text.style.marginLeft = "10.7rem"
                        text.textContent = "Инвентарь пуст..."
                        this.$refs.underMenuItem.appendChild(text)
                    }
                }else if(item.alt === "Выполнено"){
                    this.clearUnderMenu()
                    this.$refs.underMenuItem.style.flexDirection = "column"
                    if(this.task.length !== 0){
                        this.task.forEach((item)=>{
                            this.$refs.underMenuItem.appendChild(this.stilizationTask(item, true))
                        })
                        this.$refs.underMenuItem.appendChild(this.stilizationTask("Выполнено: " + this.task.length + " из 5", false))
                    }else{
                        const text = document.createElement("h5")
                        text.style.fontSize = "2rem"
                        text.style.marginTop = "2rem"
                        text.style.marginLeft = ".5rem"
                        text.style.textAlign = "center"
                        text.textContent = "Вы ещё не выполнили ни одного задания"
                        this.$refs.underMenuItem.appendChild(text)
                    }
                }
            },
            //Очищаем меню
            clearUnderMenu(){
                while(this.$refs.underMenuItem.lastChild){
                    if (this.$refs.underMenuItem.lastChild.tagName === "H3"){
                        break
                    }else{
                        this.$refs.underMenuItem.removeChild(this.$refs.underMenuItem.lastChild)
                    }
                }
            },
            //Украшаем этот дивчик))
            stilizationDiv(item){
                this.divForItem = document.createElement("div")
                this.divForItem.style.marginTop = "1rem"
                this.divForItem.style.width = "8rem"
                this.divForItem.style.height = "8rem"
                this.divForItem.style.backgroundColor = "white"
                this.divForItem.style.display = "flex"
                this.divForItem.style.flexDirection = "column"
                this.divForItem.style.alignItems = "center"
                this.divForItem.style.justifyContent = "center"
                this.divForItem.style.borderRadius = "1rem"

                item.style.height = "6rem"
                this.divForItem.appendChild(item)
                return this.divForItem
            },
            stilizationTask(item, value){
                if(value){
                    this.h5ForItem = document.createElement("h5")
                    this.h5ForItem.style.fontSize = "2.2rem"
                    this.h5ForItem.textContent = item
                    this.h5ForItem.style.marginTop = "1rem"
                    this.h5ForItem.style.marginLeft = ".5rem"
                    this.h5ForItem.style.fontWeight = "500"
                    return this.h5ForItem
                }else{
                    this.h4ForItem = document.createElement("h4")
                    this.h4ForItem.style.fontSize = "2.5rem"
                    this.h4ForItem.textContent = item
                    this.h4ForItem.style.marginTop = "2rem"
                    this.h4ForItem.style.width = "70%"
                    this.h4ForItem.style.marginLeft = ".5rem"
                    this.h4ForItem.style.fontWeight = "900"
                    this.h4ForItem.style.borderTop = ".2rem solid black"
                    return this.h4ForItem
                }
            },
            start_menu(){
                this.timer = setTimeout(() =>{
                    this.$refs.backpack[0]?.addEventListener('click', this.under_menu_open)
                    this.$refs.toDoList[0]?.addEventListener('click', this.under_menu_open)
                }, 500)
            },
            opacityMenuOne(value){
                this.opacityMenu = value
            },
            //Добавление объектов в инвентарь
            inventory(item){
                item.style = ''
                this.inventoryItem.push(item)
            },
            //Удаление объектов из инвентаря
            inventoryDelete(item){
                this.timer = setTimeout(() =>{
                    this.inventoryItem = this.inventoryItem.filter(items => items.className !== item.className)
                    this.under_menu_open(true)
                }, 500)
            },
            //Добавление выполненных заданий в меню
            tasked(item){
                this.timer = setTimeout(()=>{
                    this.task.push(item)
                }, 1000)
            }
        },

        mounted(){
            this.start_menu()
        }
    }
</script>

<style scoped>
    .menu{
        height: 100vh;
        width: 7rem;
        background-color: rgb(255, 255, 255, 1);
        position: absolute;
        z-index: 20;
        transition: 1s;
        left: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 1rem;
        box-shadow: 0.3rem 0 0.5rem rgba(0, 0, 0, 0.6);
    }

    .menu.hover{
        width: 7rem;
        transition: 1s;
    }

    .menu *{
        transition: 0.7s;
        opacity: 1;
    }

    .menu.hover *{
        transition: 1s;
        opacity: 1;
    }

    .menu .nameMenu{
        margin-top: 29rem;
        transition: 0s;
        display: flex;
        flex-direction: column;
        align-items: center;
        opacity: 1;
    }

    .menu .nameMenu .menuBar{
        transition: 0s;
        height: .5rem;
        width: 1rem;
        background-color: black;
        border-radius: .2rem;
        margin-top: .5rem
    }

    .menu .nameMenu *{
        opacity: 1;
    }

    .menu.hover .nameMenu{
        opacity: 0;
    }

    .menu .option_in_menu{
        transform: scale(.7);
        margin-top: 1rem;
        transition: .7s;
    }

    .menu .under_menu{
        z-index: 1;
        border-radius: 0 2rem 2rem 0;
        height: 100vh;
        width: 0;
        background-color: rgba(255, 255, 255, 0.5);
        position: absolute;
        left: 7rem;
        top: 0;

        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 1rem;

        transition: 1s;
    }

    .menu .under_menu *{
        transition: 1s;
    }

    .menu .under_menu.hover *{
        opacity: 0;
    }

    .underMenuItem{
        width: 90%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    h3{
        margin-top: 1rem;
        font-size: 3rem;
        font-weight: 900;
    }
</style>
