<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function autocompliteIngredients($query)
    {
        return Ingredient::where('name', 'like', $query . '%')->get();
    }

    function prepare_data($request)
    {
        Validator::make($request->all(), [
            'data' => ['required', 'array', function ($attribute, $value, $fail) {
                if (count($value) < 2) {
                    $fail('не ленись, добавь веществ');
                }
            },],
            'data.*.id' => 'required|integer'
        ])->validate();

     return array_reduce($request->input('data'), function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        });
    }

    function attachIngredients($medicamentID)
    {
        $sql = " select i.id,i.name,i.stock from ingredients i right join (select ingredient_id from ingredient_medicament where medicament_id=?) temp on i.id = temp.ingredient_id  ";
        return DB::select($sql, [$medicamentID]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        //Валидируем и делаем массив id веществ по которым ищем
        $ingredients = $this->prepare_data($request);

        $response = [];

        if ($result = Medicament::searchByIngredients($ingredients)) {

            //Ищем элементы с полным совпадением
            $response = array_filter($result, function ($item) {
                return $item->overlap === $item->total;
            });

            if (!$response) {
                //Если нет с полным совпадением убираем те у кого меньше 2 совпадений
                $response = array_filter($result, function ($item) {
                    return $item->overlap > 1;
                });
                if ($response) {
                    //Сортируем по убыванию совпадающих если что-то нашлось
                    usort($response, function ($a, $b) {
                        if ($a->overlap === $b->overlap) {
                            return 0;
                        }
                        return ($a->overlap > $b->overlap) ? -1 : 1;
                    });
                }
            }

//            прикрепляем вещества
            foreach ($response as $item) {
                $item->ingredients = $this->attachIngredients($item->id);
            }

        }

        return response()->json($response ? $response : 'не найдено лекарств', $response ? 200 : 404);

    }


}
