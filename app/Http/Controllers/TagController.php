<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function getList(Request $request)
    {
        $tag = Tag::get();
        if ($request->message) {
            $message = $request->message;
            return view('admin.tags.list', ['tag' => $tag, 'message' => $message]);
        }
        return view('admin.tags.list', ['tag' => $tag]);
    }
    public function getData(Request $request)
    {
        $tag = Tag::select('*');
        return dataTables::of($tag)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                <a class="btn btn-success" href="' . route("tag.add") . '" role="button">Thêm</a>
                <a class="btn btn-danger" onclick="return confirm(\'Bạn có chắc muốn xóa cơ sở này?\')" href="' . route("tag.delete", ["id" => $row->id]) . '" role="button">Xóa</a>
                <a class="btn btn-primary" href="' . route("tag.edit", ["id" => $row->id]) . '" role="button">Sửa</a>
                ';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    public function addTag()
    {
        return view('admin.tags.add');
    }

    public function saveAdd(Request $request)
    {
        $message = [
            'name.required' => 'Chưa nhập tên danh mục',
            'name.unique' => 'Tên danh mục đã tồn tại'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:tag'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = new Tag();
            $model->name = $request->name;

            $model->save();
            return response()->json(['success' => 'Thêm tag thành công']);
        }
    }

    public function editTag($id)
    {
        $model = Tag::find($id);
        if (!$model) {
            return redirect('admin/tags/listTag');
        }
        return view('admin.tags.edit', ['idTag' => $model]);
    }
    public function saveEdit($id, Request $request)
    {
        $message = [
            'name.required' => 'Chưa nhập tên danh mục',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = Tag::find($id);
            $model->name = $request->name;
            $model->save();
        }
        return response()->json(['success' => "Sửa danh mục thành công"]);
    }
    public function deleteTag($id)
    {
        Tag::destroy($id);
        return redirect('admin/tags/listTag');
    }
}