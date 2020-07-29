<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderStoreRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:sliders|max:50',
            'title_color' => 'required',
            'description' => 'max:250',
            'image' => 'required|image',
            'color' => 'required',
            'button_text' => 'required',
            'button_link' => 'required',
            'box_text' => 'max:12',
        ];
    }
}
