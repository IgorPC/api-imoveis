<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->paginate(10);

        return response()->json($categories, 200);
    }

    public function create()
    {
        //
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        try{

            $category = $this->category->create($data);

            return response()->json([
                'data'=> [
                    'msg' => 'Categoria cadastrada com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data'=> $category
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try{

            $category = $this->category->findOrFail($id);
            $category->update($data);

            return response()->json([
                'data'=> [
                    'msg' => 'Categoria foi atualizado com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try{

            $category = $this->category->findOrFail($id);
            $category->delete();

            return response()->json([
                'data'=> [
                    'msg' => 'Categoria foi removido com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function realStates($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data'=> $category->realStates
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }
}
