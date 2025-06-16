<template>
    <div class="hint">
        <h4 class="hint_text">Чтобы открыть шкаф, нужен ключ.</h4>

        <!-- <div class="timerblock">
            <div class="timerblock double"></div>
        </div> -->
    </div>
</template>

<script>
</script>

<style scoped>
    .hint{
        position: absolute;
        z-index: 100;
        right: -45rem;
        top: 3rem;
        width: 41rem;
        height: 7rem;
        padding: 3rem;

        border-radius: 1rem;
        background-color: aliceblue;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        transition: .8s;
    }

    .hint h4{
        text-align: center;
        font-size: 2rem;
        font-weight: 800;
    }

    .hint .timerblock{
        width: 100%;
        height: .5rem;

        border-radius: 100rem;
        box-shadow: inset 0px 0px 2px 1px rgba(0, 0, 0, 0.5);
    }

    .hint .timerblock .timerblock.double{
        width: 100%;
        height: .5rem;

        border-radius: 100rem;
        background-color: rgb(100, 100, 100);
        /* animation: timerblock 3s linear infinite; */
    }

    /*
    @keyframes timerblock {
        from{
            width: 100%;
        }

        to{
            width: 0%;
        }
    } */
</style>
