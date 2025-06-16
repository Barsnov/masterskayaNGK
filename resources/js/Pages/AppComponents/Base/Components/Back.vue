<template>
    <div ref="background_div" class="background_div w-full h-full"></div>
</template>

<script>
    export default{
        props: {
            imagesForBg:{
                type: Object,
                required: true
            }
        },

        emits:['controller', 'opacityMenuOne', 'pieceOnTrashFull', 'PartOnMap', 'inventoryItem', 'closeUnderMenu', 'checkedPasswordSuccess', 'tasked', 'bookInCloseShelf', 'tasked', 'keiInShelf'],

        data(){
            return{
                element: '',
                currentElement: {current: null, style_left: null, style_top: null},
                item: '',
                elemBelow: '',

                timer: '',
                timerText: '',

                state: {0: './images/Trash.svg', 1: './images/Trash_one.svg', 2: './images/Trash_two.svg', 3: './images/Trash_three.svg', 4: './images/Trash_four.svg', 5: './images/Trash_five.svg'},
                screen: '',
                hintAction: false,

                viewportHeight: 0,
            }
        },

        methods: {
            startGame() {
                this.imagesForBg.forEach((item) => {
                    this.element = this.createItem(item)
                    this.$refs.background_div.appendChild(this.element)
                });

                this.$emit("opacityMenuOne", 'opacity: 1')
                document.body.addEventListener("touchend", this.handleTouchEnd)

                document.getElementsByClassName("screen")[0]?.addEventListener("click", this.openComputer)
                document.getElementsByClassName("boxOpen")[0]?.addEventListener("click", this.key)
                document.getElementsByClassName("controller_white")[0]?.addEventListener("click", this.controller)

                document.querySelector(".shelf_close")?.addEventListener("click", this.openHint)
                document.querySelector(".clock")?.addEventListener("click", this.openHint)
                document.querySelector(".interactiveBoard")?.addEventListener("click", this.openHint)

                for (let item of document.querySelectorAll(".pieceOfPaper")) {
                    item.addEventListener("click", this.openHint)
                }
            },
            createItem(item) {
                this.element = document.createElement("img")
                this.element.src = item.src
                this.element.alt = item.alt
                this.element.className = item.className
                if(this.element.className === 'background'){
                    this.element.style.transform = "scale(1.1)"
                }
                this.element.style.zIndex = item.zIndex
                this.element.item_move = item.item_move
                this.element.style.position = "absolute"
                this.element.style.opacity = item.opacity
                this.element.state = item.state

                this.element.style.height = item.height + "rem"
                this.element.style.left = item.style_left + "rem"
                this.element.style.top = item.style_top + "rem"

                this.element.addEventListener("click", this.closeUnderMenu)

                this.element.addEventListener("touchstart", this.handleTouchStart, {passive: true});
                if (this.element.item_move === true) {
                    this.element.style.transition = ".1s"
                }

                return this.element
            },
            handleTouchStart(item) {
                if (item.targetTouches[item.targetTouches.length - 1].target.item_move === true) {
                    if (this.currentElement.current === null) {
                        this.currentElement.current = item.targetTouches[0];
                        this.closeUnderMenu()
                        this.currentElement.style_left = this.currentElement.current.target.style.left
                        this.currentElement.style_top = this.currentElement.current.target.style.top

                        if (this.currentElement.current.target.item_move === true) {
                            this.currentElement.current.target.style.zIndex = String(100 + Number(this.currentElement.current.target.style.zIndex))
                            document.body.addEventListener('touchmove', this.handleTouchMove);
                        }
                    }
                } else if (item.targetTouches[item.targetTouches.length - 1].target.item_move === false) console.log("Объект нельзя выбрать");
            },
            handleTouchMove(event) {
                if (this.currentElement.current !== null) { // --- ЕСЛИ ПЕРЕТАСКИВАЕМАЯ ЦЕЛЬ ОПРЕДЕЛЕНА
                    if (this.currentElement.current.target.item_move === true) {

                        this.item = this.currentElement.current.target
                        if (this.item.className === "key" || this.item.className === "controller_white") {
                            this.item.style.left = event.touches[0].pageX - 110 - this.item.offsetWidth / 2 + 'px';
                            this.item.style.top = event.touches[0].pageY - this.item.offsetHeight / 2 + 'px';
                        } else if (/\bPart_map\b/.test(this.item.className)) {
                            this.item.style.left = event.touches[0].pageX - 720 - this.item.offsetWidth / 2 + 'px';
                            this.item.style.top = event.touches[0].pageY - 350 - this.item.offsetHeight / 2 + 'px';
                            this.partMapInMap(this.item, event.touches[0])
                        } else {
                            // --- ЗАДАЕМ ЧЕРЕЗ JS-АНИМАЦИЮ КООРДИНАТЫ НАШЕГО КУРСОРА (ПАЛЬЦА) НА ЭКРАНЕ ---
                            this.item.style.left = event.touches[0].pageX - this.item.offsetWidth / 2 + 'px';
                            this.item.style.top = event.touches[0].pageY - this.item.offsetHeight / 2 + 'px';
                        }

                        // --- ПРОВЕРЯЕМ, НЕ ВЫХОДИТ ЛИ НАШ ОБЪЕКТ ЗА ГРАНИЦЫ ЭКРАНА ---
                        this.item_document(event, this.item)

                        //проверка элемента под нашим объектом
                        this.check_Class(event, this.item)
                    }
                }
            },
            item_document(event, item) {
                if (event.touches[0].pageX < 40) {
                    item.style.left = event.touches[0].pageX - item.offsetWidth / 2 + 50 + 'px';
                } else if (event.touches[0].pageX > window.screen.width - 30) {
                    item.style.left = event.touches[0].pageX - item.offsetWidth / 2 - 50 + 'px';
                }
                if (event.touches[0].pageY < 40) {
                    item.style.top = event.touches[0].pageY - item.offsetHeight / 2 + 50 + 'px';
                } else if (event.touches[0].pageY > window.screen.height - 30) {
                    item.style.top = event.touches[0].pageY - item.offsetHeight / 2 - 50 + 'px';
                }
            },
            handleTouchEnd() {
                if (this.currentElement.current !== null) {
                    if (this.currentElement.current.target.item_move === true) {
                        this.currentElement.current.target.style.zIndex = String(Number(this.currentElement.current.target.style.zIndex) - 100)
                        //ФУНКЦИИ
                        if (!this.elemBelow) {
                        } else {
                            this.pieceOnTrash(this.currentElement.current.target, this.elemBelow)
                            this.keyInShelf(this.currentElement.current.target, this.elemBelow)
                            this.bookInShelf(this.currentElement.current.target, this.elemBelow)
                            this.controllerInteractiveBoard(this.currentElement.current.target, this.elemBelow)
                        }

                        //
                        if (!this.partMapOnMap(this.currentElement.current.target)) {
                            this.currentElement.current.target.style.left = this.currentElement.style_left
                            this.currentElement.current.target.style.top = this.currentElement.style_top
                        }
                        this.currentElement.current = null;
                        this.currentElement.style_left = null
                        this.currentElement.style_right = null
                        this.elemBelow = null
                    }
                }
            },

            //Чтобы за карту не выходили части мира
            partMapInMap(item, touch) {
                if (touch.clientX < 790) {
                    item.style.left = "0px"
                } else if (touch.clientX > 1250 && /\baustraly\b/.test(item.className)) {
                    item.style.left = "525px"
                } else if (touch.clientX > 1250 && /\bgreenland\b/.test(item.className)) {
                    item.style.left = "440px"
                }

                if (touch.clientY < 385) {
                    item.style.top = "20px"
                } else if (touch.clientY > 640) {
                    item.style.top = "270px"
                }
            },
            check_Class(event, item) {
                item.style.display = 'none';
                this.elemBelow = document.elementFromPoint(event.touches[0].pageX, event.touches[0].pageY);
                item.style.display = 'block';
            },
            // Игровая часть, условия и ТД
            pieceOnTrash(item, elementBelow) {
                if (/\bpieceOfPaper\b/.test(item.className)) { //
                    if (/\btrash\b/.test(elementBelow.className)) {
                        item.remove()
                        elementBelow.state++
                        elementBelow.src = this.state[elementBelow.state]

                        //МЕТОД ДЛЯ КНИЖКИ, ЧТОБЫ ЕЁ МОЖНО БЫЛО БРАТЬ
                        if (/\bpieceOfPaper one\b/.test(item.className)) {
                            document.querySelector(".book").item_move = true
                            document.querySelector(".book")?.addEventListener("click", this.openHint)
                            document.getElementsByClassName("book")[0].addEventListener("touchstart", this.handleTouchStart, {passive: true})
                        }
                    }
                }
                if (document.getElementsByClassName("pieceOfPaper").length === 0) this.$emit("pieceOnTrashFull")
            },
            partMapOnMap(item) {
                if (/\bgreenland\b/.test(item.className)) {
                    if ((event.changedTouches[0].clientX > 900 && event.changedTouches[0].clientX < 940) && (event.changedTouches[0].clientY > 395 && event.changedTouches[0].clientY < 430)) {
                        item.style.left = "115px"
                        item.style.top = "40px"
                        item.item_move = false
                        this.$emit("PartOnMap")
                        return true
                    }
                } else if (/\baustraly\b/.test(item.className)) {
                    if ((event.changedTouches[0].clientX > 1200 && event.changedTouches[0].clientX < 1250) && (event.changedTouches[0].clientY > 570 && event.changedTouches[0].clientY < 600)) {
                        item.style.left = "470px"
                        item.style.top = "207px"
                        item.item_move = false
                        this.$emit("PartOnMap")
                        return true
                    }
                }
                return false
            },
            key() {
                document.getElementsByClassName("boxOpen")[0].style.opacity = 1
                document.getElementsByClassName("boxOpen")[0].removeEventListener("click", this.key)
                document.getElementsByClassName("boxClosed")[0].remove()
                document.getElementsByClassName("key")[0].style.transition = "2s"
                document.getElementsByClassName("key")[0].style.top = "37rem"

                //Тут мы должны отправить ключ уже в массив в меню

                this.timer = setTimeout(() => {
                    document.getElementsByClassName("key")[0].style.transition = "2s"
                    document.getElementsByClassName("key")[0].style.top = "3rem"
                    document.getElementsByClassName("key")[0].style.left = "-2rem"
                    this.timer = setTimeout(() => {
                        this.$emit("inventoryItem", document.getElementsByClassName("key")[0])
                        document.getElementsByClassName("key")[0].remove()
                    }, 1000)
                }, 1000);
            },
            controller() {
                document.getElementsByClassName("man_control")[0].style.opacity = "0"
                document.getElementsByClassName("man")[0].style.opacity = "1"
                document.getElementsByClassName("controller_white")[0].style.opacity = "1"
                document.getElementsByClassName("controller_white")[0].removeEventListener("click", this.controller)

                this.timer = setTimeout(() => {
                    document.getElementsByClassName("controller_white")[0].style.transition = "2s"
                    document.getElementsByClassName("controller_white")[0].style.top = "2rem"
                    document.getElementsByClassName("controller_white")[0].style.left = "-5rem"
                    this.timer = setTimeout(() => {
                        this.$emit("inventoryItem", document.getElementsByClassName("controller_white")[0])
                        document.querySelector(".controller_white")?.remove()
                    }, 1500)
                }, 500);

            },
            keyInShelf(item, elementBelow) {
                if (item.className === "key") {
                    if (elementBelow.className === "shelf_close") {
                        item.remove()
                        elementBelow.style.opacity = "0"
                        elementBelow.style.zIndex = "2"
                        document.getElementsByClassName("shelf_open")[0].style.opacity = "1"
                        document.getElementsByClassName("shelf_open")[0].style.zIndex = "3"
                        this.$emit("keiInShelf", item)
                    }
                }
            },
            bookInShelf(item, elementBelow) {
                if (item.className === "book") {
                    if (elementBelow.className === "shelf_open" && elementBelow.style.opacity === "1") {
                        item.remove()
                        elementBelow.style.opacity = "0"
                        elementBelow.style.zIndex = "2"
                        document.getElementsByClassName("shelf_open_book")[0].style.opacity = "1"
                        document.getElementsByClassName("shelf_open_book")[0].style.zIndex = "3"
                        document.getElementsByClassName("shelf_open_book")[0].addEventListener("click", this.bookInCloseShelf)
                    }
                }
            },
            bookInCloseShelf() {
                document.getElementsByClassName("shelf_open_book")[0].style.opacity = "0"
                document.getElementsByClassName("shelf_open_book")[0].style.zIndex = "2"

                document.getElementsByClassName("shelf_close_book")[0].style.opacity = "1"
                document.getElementsByClassName("shelf_close_book")[0].style.zIndex = "3"

                this.$emit("bookInCloseShelf")
            },
            controllerInteractiveBoard(item, elementBelow) {
                if (item.className === "controller_white") {
                    if (elementBelow.className === "interactiveBoard") {
                        item.remove()
                        elementBelow.src = "./images/InteractiveBoardOn_one.svg"
                        this.timer = setTimeout(() => {
                            const div = document.createElement("div");
                            div.style.zIndex = "0"
                            div.style.opacity = "0"
                            div.style.position = "absolute"
                            div.style.left = "44.5rem"
                            div.style.top = "22rem"
                            div.style.height = "20.5rem"
                            div.style.display = "flex"
                            div.style.flexDirection = "column"
                            div.style.justifyContent = "center"
                            div.style.alignItems = "center"
                            div.className = "mapOnScreen"

                            const img_back = document.createElement("img");
                            img_back.className = "Part_map full"
                            img_back.src = "./images/InteractiveBoardOn_two.svg"
                            img_back.style.height = "13.85rem"
                            img_back.addEventListener("click", this.openHint)

                            const australy = document.createElement("img");
                            australy.className = "Part_map australy"
                            australy.src = "./images/Australia.svg"
                            australy.style.position = "absolute"
                            australy.style.left = "23rem"
                            australy.style.top = "12.5rem"
                            australy.addEventListener("touchstart", this.handleTouchStart, {passive: true})
                            australy.item_move = true
                            australy.style.height = "2.69rem"

                            const greenland = document.createElement("img");
                            greenland.className = "Part_map greenland"
                            greenland.src = "./images/Greenland.svg"
                            greenland.style.position = "absolute"
                            greenland.style.left = "1rem"
                            greenland.style.top = "1.5rem"
                            greenland.addEventListener("touchstart", this.handleTouchStart, {passive: true})
                            greenland.item_move = true
                            greenland.style.height = "2.79rem"

                            australy.addEventListener("click", this.openHint)
                            greenland.addEventListener("click", this.openHint)

                            this.$refs.background_div.appendChild(div)

                            div.appendChild(img_back)
                            div.appendChild(greenland)
                            div.appendChild(australy)

                            div.addEventListener("click", this.closeUnderMenu)
                            this.$emit("controller", item)
                        }, 500)
                    }
                }
            },
            //ДЛЯ МЕНЮШКИ
            closeUnderMenu() {
                if (event.target.className !== "key" && event.target.className !== "controller_white") this.$emit("closeUnderMenu", true)
            },
            //Для компьютера
            openComputer() {
                document.getElementsByClassName("screen")[0].style.opacity = "0"
                document.getElementsByClassName("screen")[0].style.zIndex = "5"
                document.getElementsByClassName("screen")[0].removeEventListener("click", this.openComputer)
                document.getElementsByClassName("screen_locked_one")[0].style.opacity = "1"
                document.getElementsByClassName("screen_locked_one")[0].style.zIndex = "6"

                this.timer = setTimeout(() => {
                    document.getElementsByClassName("screen_locked_one")[0].style.opacity = "0"
                    document.getElementsByClassName("screen_locked_one")[0].style.zIndex = "5"

                    document.getElementsByClassName("screen_locked_two")[0].style.opacity = "1"
                    document.getElementsByClassName("screen_locked_two")[0].style.zIndex = "6"
                    this.createScreenLocked()
                }, 1000)
            },
            createScreenLocked() {
                this.screen = document.createElement("div")
                this.screen.style.position = "absolute"
                this.screen.style.left = "45rem"
                this.screen.style.top = "-50rem"
                this.screen.style.zIndex = "20"
                this.screen.style.borderRadius = "1rem"
                this.screen.style.backgroundImage = "url(./images/ScreenFull_locked.svg)"
                this.screen.style.backgroundSize = "cover"
                this.screen.style.height = "42rem"
                this.screen.style.width = "73rem"
                this.screen.style.display = "flex"
                this.screen.style.justifyContent = "center"
                this.screen.style.flexDirection = "column"
                this.screen.style.alignItems = "center"
                this.screen.className = "screenDiv"

                const backComp = document.createElement("div")
                backComp.textContent = "x"
                backComp.className = "endComp"
                backComp.addEventListener("click", this.closeComp)

                const h4 = document.createElement("h4")
                h4.textContent = "Введите пин-код"
                h4.style.fontSize = "28pt"

                const div_input = document.createElement("div")
                div_input.style.display = "flex"
                div_input.style.marginTop = "2rem"

                const input = document.createElement("input")
                input.className = "inputPassword"
                input.type = "password"
                input.style.borderRadius = "1rem 0 0 1rem"
                input.style.height = "4rem"
                input.style.width = "15rem"
                input.style.paddingLeft = "2rem"
                input.style.paddingRight = "2rem"
                input.maxLength = 4
                input.style.fontSize = "20pt"
                input.addEventListener("input", this.inputValidation)
                input.style.border = "0.25rem solid black"
                input.style.backgroundColor = "white"

                const div_button = document.createElement("div")
                div_button.style.width = "4rem"
                div_button.style.marginLeft = "-.5rem"
                div_button.style.borderRadius = "0 1rem 1rem 0"
                div_button.style.height = "4rem"
                div_button.style.border = "0.25rem solid black"
                div_button.style.backgroundColor = "#777777"
                div_button.addEventListener("click", this.checkedPassword)

                const white_div = document.createElement("div")
                white_div.className = "white_div"
                white_div.style.position = "absolute"
                white_div.style.opacity = "0"
                white_div.style.transition = "2s"
                white_div.style.width = "100%"
                white_div.style.height = "100%"
                white_div.style.zIndex = "19"
                white_div.style.left = "0"
                white_div.style.top = "0"
                white_div.style.backgroundColor = "rgba(255, 255, 255, 0.5)"
                white_div.addEventListener("click", this.closeUnderMenu)
                this.$refs.background_div.appendChild(white_div)

                this.$refs.background_div.appendChild(this.screen)
                this.screen.appendChild(backComp)
                this.screen.appendChild(h4)
                this.screen.appendChild(div_input)

                div_input.appendChild(input)
                div_input.appendChild(div_button)

                this.timer = setTimeout(() => {
                    this.screen.style.transition = "2s"
                    this.screen.style.top = "15rem"
                    white_div.style.opacity = "1"
                }, 1000)
            },
            inputValidation(event) {
                if (!/^[0-9]*$/.test(event.target.value)) {
                    event.target.value = event.target.value.replace(/\D/g, '');
                }
                event.target.value;
            },
            checkedPassword() {
                const input = document.getElementsByClassName('inputPassword')[0]
                const white_div = document.getElementsByClassName('white_div')[0]
                const div = document.getElementsByClassName('screenDiv')[0]

                if (input.value !== "1137") {
                    input.style.transition = "1s"
                    input.style.borderColor = "red"
                    this.timer = setTimeout(() => {
                        input.style.borderColor = "black"
                    }, 2000)
                } else {
                    input.style.transition = "1s"
                    input.style.borderColor = "green"
                    event.target.style.transition = "1s"
                    event.target.style.backgroundColor = "green"
                    event.target.removeEventListener("click", this.checkedPassword)
                    document.querySelector(".clock").removeEventListener("click", this.openHint)

                    this.timer = setTimeout(() => {
                        while (div.firstChild) {
                            div.removeChild(div.firstChild)
                        }
                        this.timer = setTimeout(() => {
                            div.style.backgroundImage = "url(./images/ScreenFull_unlocked.svg)"
                            document.getElementsByClassName("screen_locked_two")[0].style.opacity = "0"
                            document.getElementsByClassName("screen_locked_two")[0].style.zIndex = "5"

                            document.getElementsByClassName("screen_unlocked")[0].style.opacity = "1"
                            document.getElementsByClassName("screen_unlocked")[0].style.zIndex = "6"
                        }, 1000)

                        this.timer = setTimeout(() => {
                            div.style.top = "-50rem"
                            white_div.style.opacity = "0"
                            this.timer = setTimeout(() => {
                                div.remove()
                                white_div.remove()
                                this.$emit("checkedPasswordSuccess")
                            }, 2500)
                        }, 2500)
                    }, 3000)
                }
            },
            closeComp() {
                document.querySelector(".white_div").style.opacity = "0"
                document.querySelector(".screenDiv").style.top = "-55rem"
                document.querySelector(".white_div").style.zIndex = "0"
                document.querySelector(".screen_locked_two").classList.remove("blocked")
                document.querySelector(".screen_locked_two").addEventListener("click", this.openComp)
            },
            openComp() {
                document.querySelector(".white_div").style.opacity = "1"
                document.querySelector(".screenDiv").style.top = "15rem"
                document.querySelector(".white_div").style.zIndex = "19"
            },
            openHint() {
                if (this.hintAction) {
                    clearInterval(this.timer)
                    document.querySelector(".hint").style.left = "120rem"
                    this.timer = setTimeout(() => {
                        document.querySelector(".hint").style.left = "77rem"
                    }, 1500)

                    this.timer = setTimeout(() => {
                        document.querySelector(".hint").style.left = "120rem"
                        this.hintAction = false
                    }, 5500)

                    if (event.target.className === "shelf_close") {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "Чтобы открыть шкаф, нужен ключ"
                        }, 1500)
                    } else if (event.target.className === "clock") {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "Часы давно не идут"
                        }, 1500)
                    } else if (event.target.className === "interactiveBoard") {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "Необходимо включить панель"
                        }, 1500)
                    } else if (/pieceOfPaper/.test(event.target.className)) {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "В кабинете нужно прибраться"
                        }, 1500)
                    } else if (event.target.className === "book") {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "Книга лежит не на месте"
                        }, 1500)
                    } else if (/Part_map/.test(event.target.className)) {
                        clearInterval(this.timerText)
                        this.timerText = setTimeout(() => {
                            document.querySelector(".hint_text").textContent = "Вспомните географическое местоположение островов и материков"
                        }, 1500)
                    }
                } else {
                    document.querySelector(".hint").style.left = "77rem"
                    this.hintAction = true

                    this.timer = setTimeout(() => {
                        document.querySelector(".hint").style.left = "120rem"
                        this.hintAction = false
                    }, 4000)

                    if (event.target.className === "shelf_close") {
                        document.querySelector(".hint_text").textContent = "Чтобы открыть шкаф, нужен ключ"
                    } else if (event.target.className === "clock") {
                        document.querySelector(".hint_text").textContent = "Часы давно не идут"
                    } else if (event.target.className === "interactiveBoard") {
                        document.querySelector(".hint_text").textContent = "Необходимо включить панель"
                    } else if (/pieceOfPaper/.test(event.target.className)) {
                        document.querySelector(".hint_text").textContent = "В кабинете нужно прибраться"
                    } else if (event.target.className === "book") {
                        document.querySelector(".hint_text").textContent = "Книга лежит не на месте"
                    } else if (/Part_map/.test(event.target.className)) {
                        document.querySelector(".hint_text").textContent = "Вспомните географическое местоположение островов и материков"
                    }
                }
                // left: 77rem;
            },
            deleteOpenHint(item) {
                item.removeEventListener("click", this.openHint)
            }
        },

        watch:{
            imagesForBg(newValue){
                if (newValue.length !== 0){
                    this.startGame()
                }
            }
        },
    }
</script>

<style scoped>
    .background_div{
        position: relative;
        overflow: hidden;
    }

    h1{
        font-size: 1.7rem;
        font-weight: bold;
        z-index: 1;
        width: 20rem;
        height: 5rem;
        background-color: rgb(95, 235, 165);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 1rem;
        transition: .5s;
        border: 0.2rem solid transparent;
        box-shadow: 0.3rem 0.2rem 0.5rem rgba(0, 0, 0, 0.6);
    }

    h1:hover{
        transition: .5s;
        transform: scale(1.1);
        border: 0.2rem solid rgb(6, 122, 0);
    }
</style>

<style>
    .blocked{
        pointer-events: none;
    }

    .endComp{
        color: white;
        font-size: 3rem;
        font-weight: 900;
        border-radius: .5rem;
        background-color: rgb(100, 100, 100);
        border: .4rem solid transparent;
        display: flex;
        justify-content: center;
        align-items: center;

        padding-bottom: .6rem;
        width: 4rem;
        height: 4rem;
        transition: .3s ease-in;

        position: absolute;
        left: 66rem;
        top: 3rem;
    }

    .endComp:active{
        background-color: white;
        border: .2rem solid rgb(100, 100, 100);
        color:  rgb(100, 100, 100);
    }
</style>
