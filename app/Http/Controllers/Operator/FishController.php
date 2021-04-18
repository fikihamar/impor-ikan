<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

use App\Models\Fish;

class FishController extends Controller
{
  private function _validateData($request)
  {
	  return Validator::make($request->all(), [
		'name' => 'required|string|unique:fish',
		'description'=>'required|string',
		'price'=>'required|string|min:3',
		'type'=> 'in:FRESH,FROZEN|required',
		'min_order'=>'required|integer',
	]);
  }

  private function _getOne($id)
  {
	  return Fish::with('fish_image')->find($id);
  }
  public function all() {
		return Response::successWithData(
			"Get Fish Data Successcully",
			Fish::with('fish_image')->get()
		);
	}

	public function detail($id) {
		$data = $this->_getOne($id);
		if (!$data)
			return Response::error("Data Not Found", 404);

		return Response::successWithData(
			"Get Detail Fish Data Successfully",
			$data
		);
	}
	public function add(Request $r)
	{
		$validated = $this->_validateData($r);

		if ($validated->fails())
			return Response::errorWithData("Add Fish Failed",
				$validated->errors(),
				422
			);
		$fish = Fish::create([
			'name' => $r->name,
			'description' => $r->description,
			'type' => $r->type,
			'price' => $r->price,
			'min_order' => $r->min_order
		]);

		return Response::success("Add Fish Successfully");

	}
	public function edit(Request $r,$id)
	{
		$data = $this->_getOne($id);
		if (!$data)
			return Response::error("Data Not Found", 404);

		$validated = Validator::make($r->all(), [
			'name' => ['required','string',Rule::unique('fish')->ignore($id),],
			'description'=>'required|string',
			'price'=>'required|string|min:3',
			'type'=> 'in:FRESH,FROZEN|required',
			'min_order'=>'required|integer',
		]);;
		if ($validated->fails())
			return Response::errorWithData("Edit Fish Failed",
				$validated->errors(),
				422
			);

		$fish = Fish::where('id',$id)->update([
			'name' => $r->name,
			'description' => $r->description,
			'type' => $r->type,
			'price' => $r->price,
			'min_order' => $r->min_order
		]);

		return Response::success("Edit Fish Successfully");

	}
	public function delete($id)
	{
		$data=$this->_getOne($id);
		if (!$data) 
			return Response::error('Data Not Found',404);
		
		$data->delete();
		return Response::success('Delete Fish Success');
	}
}
