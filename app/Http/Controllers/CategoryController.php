<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
    public function getList(Request $request)
    {
        $cate = Category::get();
        if ($request->message) {
            $message = $request->message;
            return view('admin.categories.listCate', ['categories' => $cate, 'message' => $message]);
        }
        return view('admin.categories.listCate', ['categories' => $cate]);
    }
    public function getData(Request $request)
    {
        $cate = Category::select('*');
        return dataTables::of($cate)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return '<img width="70" src="' . asset("storage/" . $row->image) . '" alt="">';
            })
            ->addColumn('action', function ($row) {
                return '
                <a class="btn btn-success" href="' . route("category.add") . '" role="button">Thêm</a>
                <a class="btn btn-danger" onclick="return confirm(\'Bạn có chắc muốn xóa cơ sở này?\')" href="' . route("category.delete", ["id" => $row->id]) . '" role="button">Xóa</a>
                <a class="btn btn-primary" href="' . route("category.edit", ["id" => $row->id]) . '" role="button">Sửa</a>
                ';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    public function addCate()
    {
        return view('admin.categories.add');
    }

    public function saveAdd(Request $request)
    {
        $message = [
            'image.required' => "Chưa upload ảnh danh mục",
            'image.image' => 'File upload phải là hình ảnh',
            'image.mimes' => 'File ảnh có đuôi là jpeg,png,jpg',
            'image.max' => 'Hình ảnh có dung lượng lớn nhất là 2MB',
            'name.required' => 'Chưa nhập tên danh mục',
            'name.unique' => 'Tên danh mục đã tồn tại'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'name' => 'required|unique:categories'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = new Category();
            $model->name = $request->name;

            if ($request->image != '') {
                $path = $request->file('image')->storeAs('public/images', uniqid() . '-' . $request->image->getClientOriginalName());
                $model->image = str_replace('public/', '', $path);
            }

            $model->save();
            return response()->json(['success' => 'Thêm danh mục thành công']);
        }
    }

    public function editCate($id)
    {
        $model = Category::find($id);
        if (!$model) {
            return redirect('admin/categories/listCate');
        }
        return view('admin.categories.edit', ['idCate' => $model]);
    }
    public function saveEdit($id, Request $request)
    {
        $message = [
            'image.image' => 'File upload phải là hình ảnh',
            'image.mimes' => 'File ảnh có đuôi là jpeg,png,jpg',
            'image.max' => 'Hình ảnh có dung lượng lớn nhất là 2MB',
            'name.required' => 'Chưa nhập tên danh mục',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'name' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = Category::find($id);
            $model->name = $request->name;
            if ($request->image != '') {
                $path = $request->file('image')->storeAs('public/images', uniqid() . '-' . $request->image->getClientOriginalName());
                $model->image = str_replace('public/', '', $path);
            }
            $model->save();
        }
        return response()->json(['success' => "Sửa danh mục thành công"]);
    }
    public function deleteCate($id)
    {
        Category::destroy($id);
        return redirect('admin/categories/listCate');
    }
}