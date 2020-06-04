<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $RealStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->RealStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try{
            $photo = $this->RealStatePhoto->where('is_thumb', true)->where('real_state_id', $realStateId);

            if($photo->count()){
                $photo->first()->update(['is_thumb' => false]);
            }

            $photo = $this->RealStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);

            return response()->json([
                'data'=> [
                    'msg' => 'A Thumb foi atualizado com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($photoId)
    {
        try{

            $photo = $this->RealStatePhoto->findOrFail($photoId);

            if($photo->is_thumb){
                $message = new ApiMessages("Essa foto é uma thumb", ['error' => 'Selecione outra thumb e depois remova essa imagem']);
                return response()->json($message->getMessage(), 401);
            }

            if($photo){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data'=> [
                    'msg' => 'A foto foi removida com sucesso'
                ]
            ], 200);
        }catch (\Exception $e){
            $message = new ApiMessages($e->getMessage(), ['error' => $e->getMessage()]);
            return response()->json($message->getMessage(), 401);
        }
    }
}
