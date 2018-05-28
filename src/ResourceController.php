<?php

namespace Reggiebeatz71\ResourceController;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;

abstract class ResourceController extends Controller 
{

    /**
     * Returns a controllers model
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    protected abstract function model();


    /**
     * Returns a controllers model
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    protected abstract function storeRules();


    /**
     * Returns a controllers model
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    protected abstract function updateRules();


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all query parameters
        $query = $request->query();

        switch (count($query)) {
            // Just oone query parameter
            case 0 : 
                return $this->model()->all();

            // Just two query parameters
            case 1 :
                /**
                 * Either the query is a limit or its not a limit parameter
                 */

                // Its a limit parameter
                if ($request->query('limit') != null) {

                    return $this->model()->paginate($request->query('limit'));
                }

                // Its not a limit parameter
                else {
                    $key = $value = null;

                    foreach ($request->query() as $_key => $_value) {
                        $key = $_key;
                        $value = $_value;
                    }

                    return $this->model()->where($key, $value)->get();
                } 

            // Multiple query parameters
            default:
                $build = $this->model();
                $count = count($request->query());

                foreach ($request->query() as $_key => $_value) {
                    $build = $build->where($_key, $_value);
                }

                return $build->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->storeRules());

        if ($validation->fails()) 
        {
            $errors = $validation->errors();
            return response()->json($errors, 400);
        }

        else {
            $model = $this->model()->create($request->all());    
            return response()->json($model, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->model()->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validation = Validator::make($request->all(), $this->updateRules());

        if ($validation->fails()) 
        {
            $errors = $validation->errors();
            return response()->json($errors, 400);
        }

        else {
            $this->model()->find($id)->update($request->all());

            return $this->model()->find($id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = $this->model()->find($id);

        if (!empty($model)) {
            $model->delete();

            return [
                "status" => "success",
                "message" => "Item has been successfully deleted."
            ];;
        }

        return [
            "status" => "failed",
            "message" => "The item you're trying to delete does not exist."
        ];
    }
}