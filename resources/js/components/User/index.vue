<template>
    <div>

        <div class="container">

            <div id="autocomplete" class="autocomplete">
                <input class="autocomplete-input" />
                <ul class="autocomplete-result-list"></ul>
            </div>

            <div style="display: flex;justify-content: start;margin-top: 10px">
                <span v-for="(item,index) in searchQuery" class="badge badge-secondary" style="margin-right: 5px">{{item.name}}
                    <span @click="searchQuery.splice(index,1)"
                          class="badge badge-secondary"
                          style="cursor: pointer">X</span>
                </span>
               <span @click="searchQuery = []" style="margin-left: 4px;cursor: pointer" v-if="searchQuery.length >0"  class="badge badge-danger" >X</span>
            </div>

            <div id="accordion"  style="margin-top: 30px">
                <div class="card" v-for="(medicament,indexM) in medicaments" :key="indexM" style="margin-bottom: 4px">
                    <div class="card-header" :id="'heading'+indexM"  >
                        <div style="display: flex;justify-content:start; align-items: center">
                            <span class="badge badge-light">{{medicament.overlap +' / '+medicament.total}}</span>
                            <h5 class="mb-0"  style="margin-right: 20px;margin-left: 20px">

                                <span>   {{medicament.name}}</span>

                            </h5>

                            <span v-for="(item,index4) in medicament.ingredients"  :key="index4"
                                  :class="'badge '+(inSerachQuery(item)?'badge-success':'badge-light')"
                                  style="margin-right: 3px">
                              {{item.name}}
                            </span>



                        </div>
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
                searchQuery:[],
                message:'',
            }
        },
        watch:{
            searchQuery(value){
                if(value.length>1)
                {
                    this.searchIngredient();
                }

            }
        },
        methods:{

            inSerachQuery(item){
                return this.searchQuery.find( (element)=>{
                    return element.id === item.id
                });
            },
            searchIngredient(){
                axios.post('/searchMedicaments',JSON.stringify(
                    {data:this.searchQuery}
                ), { headers: {
                        "Content-Type": "application/json"
                    }}).then(res => {
                       this.medicaments = res.data;

                }).catch(err => {
                    this.medicaments = [];

                }).finally(()=> {this.$forceUpdate()})
            },

        },
        mounted() {
            const el = document.getElementById('autocomplete');
            new Autocomplete(el, {

                search: input => {
                    return new Promise(resolve => {
                        if (input.length < 1) {
                            return resolve([])
                        }
                        axios.get('/autocompliteIngredients/' + input).then(res => {
                            resolve(res.data)
                        })
                    })
                },
                    getResultValue: result => result.name,

                onSubmit: result => {
                    this.searchQuery.push(result)
                    document.getElementsByClassName('autocomplete-input')[0].value = '';
                    if(this.searchQuery.length<2)
                    {
                        document.getElementsByClassName('autocomplete-input')[0].placeholder = 'не ленись, добавь веществ';
                    }
                    else
                    {
                        document.getElementsByClassName('autocomplete-input')[0].placeholder = '';

                    }



                }
                })

        }
    }
</script>
