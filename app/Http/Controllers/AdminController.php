<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Medicament;
use App\pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class AdminController extends Controller
{

    public function createIngredient(Request $request){

        Validator::make($request->all(),[
            'name' => 'required|string|max:30'
        ])->validate();

        $item = Ingredient::create([
            'name' =>$request->input('name'),
            'stock' => 1
        ]);
        return response()->json($item,200);

    }
    public function getIngredient($id){

        return Ingredient::findOrFail($id);
    }
    public function getIngredients(){

        return Ingredient::all();
    }

    public function switchAvailableIngredients($id)
    {
        try {
            return Ingredient::find($id)->switchAvailable();
        }
        catch (Exception $exception)
        {
            return  $exception->getMessage();
        }
    }
    public function deleteIngredient($id){

        $item = Ingredient::find($id);
        if($item)
        {
            pivot::where('ingredient_id','=',$id)->delete();
            $item->delete();
            return response()->json(true, 200);
        }
        return response()->json('Not found', 404);
    }

    public function getMedicaments(){

        return Medicament::with('ingredients')->get()->sortByDesc('id');

    }
    public function createMedicament(Request $request){
        Validator::make($request->all(),[
            'name' => 'required|string|max:30'
        ])->validate();


        $item = new Medicament();
        $item->name = $request->input('name');
        $item->count = 0;
        $item->stock = 0;
        $item->save();
        return response()->json($item,200);

    }
    public function getMedicament($id){

        return Medicament::find($id);
    }
    public function updateMedicament(Request $request){
        try {

            $medicament = Medicament::find($request->input('medicament'));
            $ingredient = Ingredient::find($request->input('ingredient'));
            $action = $request->input('action');
            if($medicament && $ingredient && $action)
            {
                if($res = $medicament->updateIngredients($action,$ingredient) === true)
                {
                    return ['status'=>'success','data' => $medicament->load('ingredients')];
                }
                return  ['status'=>'error','data' => $res];

            }
            return  ['status'=>'error','data' => 'Not Found'];
        }
        catch (Exception $exception)
        {
            return response()->json('Not found',404);
        }

    }
    public function deleteMedicament($id){


        $item = Medicament::find($id);
        if($item)
        {
            pivot::where('medicament_id','=',$id)->delete();
            $item->delete();
            return response()->json(true, 200);
        }
        return response()->json('Not found', 404);
    }

}
