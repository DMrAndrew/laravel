<template>
    <div>

        <div class="container">
            <div class="form-group">
                <div class="form-inline">
                    <div @click="e => e.target.nextElementSibling.classList.toggle('panelcreate')" class="btn btn-success mb-2">Создать новое лекарство</div>
                    <div class="panelcreate" style="display: flex">
                        <div class="form-group mx-sm-3 mb-2">
                            <input v-model="newitem" type="text" class="form-control"  placeholder="название">
                        </div>
                        <button @click="createMedicoment()"  class="btn btn-primary mb-2">Создать</button>
                    </div>

                </div>
            </div>
            <div id="accordion">
                <div class="card" v-for="(medicament,index) in medicaments" :key="index">
                    <div class="card-header" :id="'heading'+index"  >
                        <div style="display: flex;justify-content: space-between">
                            <h5 class="mb-0" >
                                <button class="btn btn-link" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="true" :aria-controls="'heading'+index">
                                    {{medicament.name}}

                                </button>

                                <span :class="'badge '+ (medicament.stock?'badge-success':'badge-danger')">{{medicament.stock?'Доступно':'Недоступно'}}</span>
                                <span class="badge badge-light">{{medicament.count}}  <span @click="removeMedicament(medicament.id,index)"  style="cursor: pointer" aria-hidden="true">&times;</span></span>
                            </h5>

                            <button @click="addNewTo = index" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" >Добавить вещество</button>

                        </div>


                    </div>

                    <div :id="'collapse'+index" class="collapse show" :aria-labelledby="'heading'+index" data-parent="#accordion">
                        <div class="card-body">
                            <ul class="list-group">
                                <li v-for="(item,index3) in medicament.ingredients"
                                    :key="index3"
                                    :class="'list-group-item '+(item.stock?'list-group-item-success':'list-group-item-danger')">
                                    {{item.name}}
                                    <span @click="removeIngredients(medicament,item.id,index)"  style="cursor: pointer" aria-hidden="true">&times;</span>

                                </li>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span  aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <div v-for="(ingredient,index2) in ingredients" :key="index2" >
                                <li style="cursor: pointer" @click="AddIngrediend(ingredient)" :class="'list-group-item '+(ingredient.stock?'list-group-item-success':'list-group-item-danger')">{{ingredient.name}}</li>
                            </div>


                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data:()=>{
            return {
                ingredients:[],
                medicaments:[],
                addNewTo:false,
                newitem:'',
            }
        },
        methods:{
            removeMedicament(id,index){
                axios.delete('/medicaments/' + id)
                    .then((resp) => {
                        this.medicaments.splice(index,1);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },
            createMedicoment(){
                if(this.newitem.length > 3)
                {
                    axios.post('/medicaments',JSON.stringify({
                        name:this.newitem
                    }),{ headers: {
                            "Content-Type": "application/json"
                        }}).then(res => {

                        this.medicaments.unshift(res.data);
                        this.newitem = '';
                    }).catch(res=>{

                    }).finally(()=>{
                        this.$forceUpdate()
                    })
                }
                else {
                    alert('Имя должно содержать больше 3 символов');
                }
            },
            removeIngredients(medicament,in_id,index){
                axios.put('/medicaments', JSON.stringify(
                    {
                        medicament:medicament.id,
                        ingredient:in_id,
                        action:'detach'
                    },
                ), { headers: {
                        "Content-Type": "application/json"
                    }})
                    .then((resp) =>  {
                        if(resp.data.status === 'success')
                        {
                            this.medicaments[index] = resp.data.data;
                            this.$forceUpdate();
                        }
                        else
                        {
                            alert(resp.data.data);
                        }




                    })
                    .catch((err) =>  {

                        alert("Not found");
                    });
            },
            AddIngrediend(value)
            {
                axios.put('/medicaments', JSON.stringify(
                    {
                        medicament:this.medicaments[this.addNewTo].id,
                        ingredient:value.id,
                        action:'attach'
                    },
                ), { headers: {
                        "Content-Type": "application/json"
                    }})
                    .then((resp) =>  {
                        if(resp.data.status === 'success')
                        {
                            this.medicaments[this.addNewTo] = resp.data.data;
                            this.$forceUpdate();
                        }

                        console.log(resp);


                    })
                    .catch((err) =>  {
                        console.log(err);
                        alert("Уже есь");
                    });
            }
        },
        mounted() {
            axios.get('/getMedicaments')
                .then((resp) =>  {
                    this.medicaments = resp.data;

                })
                .catch((err) =>  {
                    console.log(err);

                });
            axios.get('/getIngredients')
                .then((resp1) =>  {
                    this.ingredients = resp1.data;
                })
                .catch((err) =>  {
                    console.log(err);

                });

        }
    }
</script>
<style scoped>
    .panelcreate{
        display: none!important;
    }
</style>
