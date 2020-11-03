<template>
    <div>
        <div class="form-group">

            <div class="form-inline">
                <div @click="e => e.target.nextElementSibling.classList.toggle('panelcreate')" class="btn btn-success mb-2">Создать новое вещество</div>
                <div class="panelcreate" style="display: flex">
                    <div class="form-group mx-sm-3 mb-2">
                        <input v-model="newitem" type="text" class="form-control"  placeholder="название">
                    </div>
                    <button @click="createIngredient()"  class="btn btn-primary mb-2">Создать</button>
                </div>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Вещества</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th width="150">Enable</th>
                        <th width="100">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(indegrient, index) in indegriens">
                        <td>{{ indegrient.name }}</td>
                        <td>
                            <button @click="switchAvailable(indegrient.id,index)"
                                    type="button"
                                    :class="'btn btn-'+(indegrient.stock?'success':'danger')">
                                {{indegrient.stock?'Доступен':'Недоступен'}}
                            </button>
                        </td>
                        <td>
                            <button @click="deleteIngredient(indegrient.id,index)"
                                    type="button"
                                    class="btn btn-light">
                                Удалить
                            </button>
                        </td>

                        <!--                        <td>-->
                        <!--                            <router-link :to="{name: 'editCompany', params: {id: company.id}}" class="btn btn-xs btn-default">-->
                        <!--                                Edit-->
                        <!--                            </router-link>-->
                        <!--                            <a href="#"-->
                        <!--                               class="btn btn-xs btn-danger"-->
                        <!--                               v-on:click="deleteEntry(company.id, index)">-->
                        <!--                                Delete-->
                        <!--                            </a>-->
                        <!--                        </td>-->
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

        name: 'HelloWorld',
        data: function () {
            return {
                indegriens: [],
                newitem:'',
            }
        },
        mounted() {

            axios.get('/getIngredients')
                .then((resp) => {
                    this.indegriens = resp.data;
                })
                .catch((err) => {
                    console.log(err);
                    alert("Could not load companies");
                });
        },
        methods: {
            createIngredient(){

                if(this.newitem.length > 3)
                {
                    axios.post('/ingredients',JSON.stringify({
                        name:this.newitem
                    }),{ headers: {
                            "Content-Type": "application/json"
                        }}).then(res => {

                        this.ingredients.unshift(res.data);
                        this.newitem = '';
                        alert('ss');
                    }).catch(  ).finally(()=>{
                        this.$forceUpdate()
                })
                }
                else {
                    alert('Имя должно содержать больше 3 символов');
                }
            },
            deleteIngredient(id, index) {

                axios.delete('/ingredients/' + id)
                    .then((resp) => {
                        this.indegriens.splice(index,1);
                    })
                    .catch((err) => {
                        console.log(err);
                        alert("Could not load companies");
                    });
            },

            switchAvailable(id, index) {
                axios.get('/switchAvailableIngredients/' + id)
                    .then((resp) => {
                        this.indegriens[index].stock = !this.indegriens[index].stock;
                        console.log(resp);
                    })
                    .catch((err) => {
                        console.log(err);
                        alert("Could not load companies");
                    });
            }
        }
    }
</script>
<style scoped>
    .panelcreate{
        display: none!important;
    }
</style>
