<template>
    <component :is="getComponent" :images="images"></component>
</template>


<script>
    import {defineAsyncComponent} from "vue";
    export default{
        props:{
            images:{
                type: Object,
                required: true
            },

            component:{
                type: String,
                required: true
            }
        },

        computed: {
            getComponent () {
                return defineAsyncComponent(() => import(`../../Pages/AppComponents/${this.component}/${this.component}.vue`))
            }
        }
    }
</script>

<style>
</style>
