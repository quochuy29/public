<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        // dd($this->name);
        $ruleArr =  [
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Product::where('name', $value)->where('id', '!=', $this->id)->count() > 0) {
                        $fail('Tên sản phẩm đã tồn tại');
                    }
                },
            ],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required',
            'cate_id' => 'required',
            'status' => 'required',
            'code_sale' => 'required',
            'price' => 'required|min:1',
            'amount' => 'required|min:1',
            'nameTag' => 'required'

        ];
        if ($this->id == null) {
            $ruleArr['image'] = 'required|mimes:jpg,bmp,png,jpeg';
        } else {
            $ruleArr['image'] = 'mimes:jpg,bmp,png,jpeg';
        }
        return $ruleArr;
    }

    public function messages()
    {
        return [
            'image.required' => 'Chưa chọn hình ảnh',
            'image.image' => 'File upload phải là hình ảnh',
            'image.mimes' => 'File ảnh có đuôi là jpeg,png,jpg',
            'image.max' => 'Hình ảnh có dung lượng lớn nhất là 2MB',
            'name.required' => 'Chưa nhập tên sản phẩm',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'cate_id.required' => 'Chưa chọn danh mục sản phẩm',
            'status.required' => 'Chưa chọn trạng thái cho sản phẩm',
            'code_sale.required' => 'Chưa nhập mã giảm giá',
            'price.required' => 'Chưa nhập giá sản phẩm',
            'price.min' => 'Giá phải lớn hơn 0',
            'amount.required' => 'Chưa nhập số lượng',
            'amount.min' => 'Số lượng sản phẩm phải lớn hơn 0',
            'nameTag.required' => 'Chưa chọn thẻ tag'
        ];
    }
}