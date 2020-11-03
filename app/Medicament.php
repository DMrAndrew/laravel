<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Medicament extends Model
{
    protected $table = "medicaments";
    protected $fillable = ['id', 'name', 'stock', 'count'];


    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * Проверяем доступность лекарства.
     * Используем при добавлении/удалении веществ в лекарство
     * @return bool
     */
    public function SelfCheckStock()
    {
        $stock = $this->ingredients()->where('stock', '=', 0)->get()->count();
        $this->stock = $stock === 0 ? 1 : 0;
        return $this->save();

    }

    /**
     *  Берём из сводной таблицы все лекарства у которых есть совпадения по веществам, считаем для каждого совпадения
     * через присоединение справа берём нужные лекарства из medicoments
     * у которых нет ни одного недоступного вещества ( мы всегда знаем о доступности лекарства
     * так как после свитча состояния/удаления/добавления вещества проверяются все составленные из него лекарства)
     * @param $array
     * @return array
     */
    static function searchByIngredients($array)
    {
        $in_query = implode(',', array_fill(0, count($array), '?'));

        $sql = "select
                       m.id,m.name, temp.overlap, m.count as total
                       from medicaments m
                       right JOIN (
                         SELECT medicament_id,count(medicament_id) as overlap
                         from ingredient_medicament
                         WHERE ingredient_id in ($in_query)
                         GROUP by medicament_id) temp
                       on m.id = temp.medicament_id
                       WHERE m.stock = 1";

        return DB::select($sql, $array);
    }

    public function updateIngredients($action, $ingredient)
    {
        $countElements = $this->ingredients()->where('ingredient_id', $ingredient->id)->get()->count() === 0;

        $message = '';
        switch ($action) {
            case 'attach':
                if ($countElements) {
                    $this->ingredients()->attach($ingredient->id);

                } else $message = 'Уже есь';
                break;

            case 'detach':
                if (!$countElements) {
                    $this->ingredients()->detach($ingredient->id);

                } else $message = 'Нет такого';
                break;


            default:
                $message = 'Нет такого действия';
        }

        $this->count = $this->ingredients()->count();
        $this->save();
        return $this->wasChanged('count') ? $this->SelfCheckStock() : $message;


    }


}
