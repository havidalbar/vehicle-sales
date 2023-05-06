<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->request->get('vehicle_type') == NULL)
        {
            throw new HttpResponseException(response('vehicle_type is required', Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $vehicle_type = $this->request->get('vehicle_type');
        switch (strtolower($vehicle_type)) {
            case "car":
                return [
                    'year_release' => 'required|string',   
                    'color' => 'required|string',
                    'price' => 'required',
                    'passenger_capacity' => 'required',   
                    'type' => 'required|string',
                    'engine' => 'required|string'
                ];
                break;
            case "motorcycle":
                return [
                    'year_release' => 'required|string',   
                    'color' => 'required|string',
                    'price' => 'required',
                    'suspension_type' => 'required|string',   
                    'transmissi_type' => 'required|string',
                    'engine' => 'required|string'
                ];
                break;
        }
    }
}
