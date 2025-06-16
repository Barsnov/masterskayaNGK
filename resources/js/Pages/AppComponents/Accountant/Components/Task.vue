<template>
  <div ref="card" class="card">
    <img :src="imagesSrc !== '' ? `/storage/${imagesSrc}` : '#'" :alt="imagesAlt"/>
    <div class="last_div">
      <input ref="input" type="text" class="border-2 border-black" v-model="inputText" />
      <div ref="checkAnswer" class="button">ПРОВЕРИТЬ</div>
      <div class="closeCard" @click="closeCardFunc">x</div>
    </div>
  </div>
</template>

<script>

export default {
    props:{
        images:{
            type: Object,
            required: true
        }
    },

    data() {
        return {
            timer: "",
            filteredImages: "",

            imagesSrc: "",
            imagesAlt: "",
            inputText: "",
            numberForArray: "",
            answer: "",

            objectCard: "",
            objectsCloseCard: "",
            arrayObjectFromCard: [],

            answerObjectRight: "",
            answerNumber: 0,

            setNumberSelected: new Set()
        };
    },

    methods: {
        imagesCopy(){
            this.filteredImages = this.images
            this.$refs.checkAnswer.addEventListener("click", this.checkAnswer);
        },
        startTask(value, binary_value){
            if (binary_value){
                this.answerObjectRight = document.querySelector(value.name);
                this.$refs.card.style.opacity = "1";
                this.$refs.card.style.zIndex = "101";
                this.numberForArray = value.value
                this.inputText = "";
                this.imagesSrc = this.filteredImages[this.numberForArray]?.url;
                this.imagesAlt = this.filteredImages[this.numberForArray]?.name;
                this.answer = this.filteredImages[this.numberForArray]?.answer;
            }else{
                this.answerObjectRight = document.querySelector(value);
                this.getRandomNumber()
                this.objectCard = value
                this.inputText = "";
                this.$refs.card.style.opacity = "1";
                this.$refs.card.style.zIndex = "101";
                this.imagesSrc = this.filteredImages[this.numberForArray]?.url;
                this.imagesAlt = this.filteredImages[this.numberForArray]?.name;
                this.answer = this.filteredImages[this.numberForArray]?.answer;
                this.arrayObjectFromCard.push({name: this.objectCard, value: this.numberForArray})
            }
            this.$emit("BlockAllComponents");
          },
        getRandomNumber() {
            if (this.setNumberSelected.size === this.filteredImages.length) return;
            do {this.numberForArray = Math.ceil(Math.random() * this.filteredImages.length) - 1;
            } while (this.setNumberSelected.has(this.numberForArray));
            this.setNumberSelected.add(this.numberForArray);
          },
        checkAnswer() {
            if (String(this.$refs.input.value.toUpperCase()) === String(this.answer.toUpperCase()) || String(this.$refs.input.value.toUpperCase()) === 'admin'.toUpperCase()) {
                this.$refs.checkAnswer.style.backgroundColor = "rgb(74, 230, 84)";
                try{
                    this.answerObjectRight.removeEventListener("click", this.openCardAgain)
                }catch{
                    console.log("Не существует функции для удаления.");
                }
                this.answerNumber ++
                if (/trash/.test(this.answerObjectRight.className)) this.$emit("taskedEmit", "Убрать весь мусор ✓")
                else if(/screen_unlocked/.test(this.answerObjectRight.className)) this.$emit("taskedEmit", "Разблокировать компьютер ✓")
                else if(/shelf_close_book/.test(this.answerObjectRight.className)) this.$emit("taskedEmit", "Убрать книжку в шкаф ✓")
                else if(/interactiveBoard/.test(this.answerObjectRight.className)) this.$emit("taskedEmit", "Включить интерактивную доску ✓")
                else if(/mapOnScreen/.test(this.answerObjectRight.className)) this.$emit("taskedEmit", "Поправить карту на интерактивной доске ✓")
                this.timer = setTimeout(() => {
                    this.$refs.card.style.transition = "1.5s";
                    this.$refs.card.style.top = "-70rem";
                }, 1000);
                this.timer = setTimeout(() => {
                    this.$refs.card.style.opacity = "0";
                    this.$refs.card.style.zIndex = "-1";
                    this.$emit("removeBlockAllComponents");
                }, 1500);
                this.timer = setTimeout(() => {
                    this.$refs.card.style.top = "9rem";
                    this.$refs.checkAnswer.style.backgroundColor = "white";
                    if (this.answerObjectRight.className === "interactiveBoard"){document.querySelector(".mapOnScreen").style.opacity = "1"
                        document.querySelector(".mapOnScreen").style.zIndex = "11"
                    }
                }, 2500);
            }else {
                this.$refs.checkAnswer.style.backgroundColor = "rgb(255, 88, 88)";
                this.timer = setTimeout(() => {this.$refs.checkAnswer.style.backgroundColor = "white";
                }, 1000);
            }
            this.endGame();
          },
        //Выход из карточки путём нажатия на крестик и после, добавить функционал всяким предметам с выпадением этой же самой карточки.
        closeCardFunc(){
            this.$refs.card.style.opacity = "0";
            this.$refs.card.style.zIndex = "-1";
            this.$emit("removeBlockAllComponents");
            document.querySelector(this.objectCard).addEventListener("click", this.openCardAgain)
          },
        openCardAgain(){
            for (let item of this.arrayObjectFromCard){
                if ("." + event.target.className === item.name || "." + event.target.parentNode.className === item.name) this.startTask(item, true)
            }
          },
        //Проверка на конец игры?
        endGame() {
            if (this.answerNumber === 5) {
                const endGameImages = document.createElement("img")
                endGameImages.style.position = "absolute"
                endGameImages.style.width = "100%"
                endGameImages.style.opacity = "0"
                endGameImages.style.zIndex = "40"
                endGameImages.style.height = "auto"
                endGameImages.style.left = "0"
                endGameImages.style.top = "0"
                endGameImages.style.transition = "1s ease-out"
                endGameImages.src = "./images/End_game.jpg"
                this.timer = setTimeout(() => {
                    document.querySelector(".background_div").appendChild(endGameImages)
                    endGameImages.style.opacity = "1"
                }, 2000)

                this.$emit('endGame')
            }
        }
    },

    mounted(){
        this.imagesCopy()
    }
};
</script>

<style scoped>
    .blocked{
        pointer-events: all;
    }

    .card {
        position: absolute;
        z-index: -1;
        opacity: 0;
        width: 75rem;
        height: 50rem;
        background-color: aliceblue;
        border-radius: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        top: 9rem;
        transition: 1s ease-in;
    }

    .last_div {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        width: 90%;
        height: 35%;
    }

    .button {
        border: 0.2rem solid rgb(59, 59, 59);
        background-color: rgb(255, 255, 255);
        width: 20rem;
        height: 4rem;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .button:active {
        transition: 0.2s;
        background-color: aliceblue;
    }

    img {
        width: 100%;
        height: 75%;
        border-radius: 0.5rem;
    }

    input {
        text-align: center;
        padding: 0 1rem;
        font-size: 1.7rem;
        font-weight: 600;
        width: 40rem;
        height: 4rem;
        border-radius: 0.5rem;

    }

    .closeCard{
        color: white;
        font-size: 3rem;
        font-weight: 900;
        border-radius: .5rem;
        background-color: rgb(100, 100, 100);
        border: .2rem solid transparent;
        display: flex;
        justify-content: center;
        align-items: center;

        padding-bottom: .6rem;
        width: 4rem;
        height: 4rem;
        transition: .3s ease-in;
    }

    .closeCard:active{
        background-color: white;
        border: .2rem solid rgb(100, 100, 100);
        color: rgb(100, 100, 100);
    }
</style>
