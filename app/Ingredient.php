<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ingredient extends Model
{
    protected $table = "ingredients";
    protected $fillable = ['id', 'name', 'stock'];

    public function medicaments()
    {
        return $this->belongsToMany(Medicament::class);
    }

    /**
     * Проверяем на доступность все составленные из вещества лекарства
     * Используем только при свитче состояния вещества
     * @return bool
     */
    function checkStock()
    {
        if ($this->stock) {
            $sql = "update medicaments
                    set stock=1 where id in
                       (select medicament_id
                        from (SELECT temp.medicament_id,
                              COUNT(temp.medicament_id) as allraws,
                              sum(temp.stock) as goodraws
                              FROM (SELECT m.medicament_id, i.stock
                                    from ingredient_medicament m
                                    LEFT join ingredients i
                                    on m.ingredient_id = i.id
                                    where m.medicament_id in (SELECT medicament_id
                                                              from ingredient_medicament
                                                              where ingredient_id = ?
                                                              )
                                    ) temp
                              group by temp.medicament_id
                              ) temp1
                        where allraws = goodraws
                        )";

            return DB::select($sql, [$this->id]) ? true : false;


        }
        return $this->medicaments()->update(['stock' => '0']) ? true : false;
    }

    public function switchAvailable()
    {
        $this->stock = !$this->stock;
        $this->save();
        return $this->checkStock();


    }

}
