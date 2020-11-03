<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item active">

                        <router-link :to="{name: 'indexIngredients'}" class="nav-link">Вещества</router-link>
                    </li>
                    <li class="nav-item ">

                        <router-link :to="{name: 'indexMedicaments'}" class="nav-link">Медикоменты</router-link>
                    </li>
                    <li class="nav-item ">

                        <router-link :to="{name: 'indexUser'}" class="nav-link">Поиск</router-link>
                    </li>


                </ul>
            </div>
        </nav>
        <div class="container" style="margin-top: 30px">
        <router-view :key="$route.fullPath"/>
        </div>
    </div>

<!--    <div class="container">-->
<!--        <div class="row justify-content-center">-->
<!--            <div class="col-md-8">-->
<!--                <div class="card">-->
<!--                    <div class="card-header">Example Component</div>-->

<!--                    <div class="card-body">-->
<!--                        I'm an example component.-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
