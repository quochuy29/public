<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use Aws\Organizations\Exception\OrganizationsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function listProduct(Request $request)
    {
        $product = Product::get();
        $cate = Category::get();
        if ($request->message) {
            $message = $request->message;
            return view('admin.products.list', ['products' => $product, 'message' => $message]);
        }
        return view('admin.products.list', ['products' => $product, 'category' => $cate]);
    }

    public function upload(Request $request)
    {
        $uploadImg = $request->file('file');
        $filename = time() . '.' . $uploadImg->extension();
        Image::make($uploadImg)->save(public_path('images/' . $filename));
        return json_encode(['location' => asset('images/' . $filename)]);
    }

    public function getData(Request $request)
    {
        $product = Product::select('*');
        return dataTables::of($product)
            ->orderColumn('cate_id', function ($row, $order) {
                return $row->orderBy('cate_id', $order);
            })
            ->orderColumn('status', function ($row, $order) {
                return $row->orderBy('status', $order);
            })
            ->addColumn('cate_id', function ($row) use ($request) {
                $category = Category::get();
                foreach ($category as $cate) {
                    if ($row->cate_id == $cate->id) {
                        return $cate->name;
                    }
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-primary">Active</span>';
                } elseif ($row->status == 0) {
                    return '<span class="badge badge-danger">Deactive</span>';
                } else {
                    return '<span class="badge badge-danger">S???p ra m???t</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-primary" href="' . route("product.detail", ["id" => $row->id]) . '" role="button">Xem chi ti???t</a>';
            })
            ->filter(function ($instance) use ($request) {
                if ($request->get('status') == '0' || $request->get('status') == '1') {
                    $instance->where('status', $request->get('status'));
                }

                if ($request->get('cate') != '') {
                    $instance->where('cate_id', $request->get('cate'));
                }

                if (!empty($request->get('search'))) {
                    if ($request->get('status') == '0' || $request->get('status') == '1') {
                        $instance->where('status', $request->get('status'));
                    }
                    if ($request->get('cate')) {
                        $instance->Where('cate_id', $request->get('cate'));
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('name', 'LIKE', "%$search%")
                                ->orWhere('detail', 'LIKE', "%$search%");
                        });
                    }
                }
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function addPro()
    {
        $cate = Category::get();
        $tag = Tag::get();
        return view('admin.products.add', compact('cate', 'tag'));
    }

    public function saveAdd(Request $request)
    {
        $message = [
            'image.required' => "Ch??a upload ???nh s???n ph???m",
            'image.image' => 'File upload ph???i l?? h??nh ???nh',
            'image.mimes' => 'File ???nh c?? ??u??i l?? jpeg,png,jpg',
            'image.max' => 'H??nh ???nh c?? dung l?????ng l???n nh???t l?? 2MB',
            'name.required' => 'Ch??a nh???p t??n s???n ph???m',
            'name.unique' => 'T??n s???n ph???m ???? t???n t???i',
            'cate_id.required' => 'Ch??a ch???n danh m???c s???n ph???m',
            'status.required' => 'Ch??a ch???n tr???ng th??i cho s???n ph???m',
            'code_sale.required' => 'Ch??a nh???p m?? gi???m gi??',
            'price.required' => 'Ch??a nh???p gi?? s???n ph???m',
            'price.min' => 'Gi?? ph???i l???n h??n 0',
            'amount.required' => 'Ch??a nh???p s??? l?????ng',
            'amount.min' => 'S??? l?????ng s???n ph???m ph???i l???n h??n 0',
            'tag.required' => 'Ch??a ch???n th??? tag s???n ph???m'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'name' => 'required|unique:products',
                'cate_id' => 'required',
                'status' => 'required',
                'code_sale' => 'required',
                'price' => 'required|min:1',
                'amount' => 'required|min:1',
                'tag' => 'required'
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = new Product();
            $model->name = $request->name;
            $model->cate_id = $request->cate_id;
            $model->status = $request->status;
            $model->code_sale = $request->code_sale;
            $model->price = $request->price;
            $model->detail = $request->detail;
            $model->amount = $request->amount;
            if ($request->image != '') {
                $path = $request->file('image')->storeAs('public/images', uniqid() . '-' . $request->image->getClientOriginalName());
                $model->image = str_replace('public/', '', $path);
            }
            $model->save();
            if ($request->tag) {
                foreach ($request->tag as $t) {
                    $mod = new ProductTag();
                    $mod->product_id = $model->id;
                    $mod->tag_id = $t;
                    $mod->save();
                }
            }

            return response()->json([
                'success' => "Th??m th??nh c??ng"
            ]);
        }
    }

    public function detailPro(Request $request)
    {
        $detail = Product::find($request->id);
        return view('admin.products.detail', ['detail' => $detail]);
    }

    public function editPro($id)
    {
        $edit = Product::find($id);
        $category = Category::get();
        return view('admin.products.edit', ['edit' => $edit, 'category' => $category]);
    }

    public function saveEdit(Request $request)
    {
        $message = [
            'image.image' => 'File upload ph???i l?? h??nh ???nh',
            'image.mimes' => 'File ???nh c?? ??u??i l?? jpeg,png,jpg',
            'image.max' => 'H??nh ???nh c?? dung l?????ng l???n nh???t l?? 2MB',
            'name.required' => 'Ch??a nh???p t??n s???n ph???m',
            'cate_id.required' => 'Ch??a ch???n danh m???c s???n ph???m',
            'status.required' => 'Ch??a ch???n tr???ng th??i cho s???n ph???m',
            'code_sale.required' => 'Ch??a nh???p m?? gi???m gi??',
            'price.required' => 'Ch??a nh???p gi?? s???n ph???m',
            'price.min' => 'Gi?? ph???i l???n h??n 0',
            'amount.required' => 'Ch??a nh???p s??? l?????ng',
            'amount.min' => 'S??? l?????ng s???n ph???m ph???i l???n h??n 0'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'name' => 'required',
                'cate_id' => 'required',
                'status' => 'required',
                'code_sale' => 'required',
                'price' => 'required|min:1',
                'amount' => 'required|min:1',
            ],
            $message
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        } else {
            $model = Product::find($request->id);
            $model->name = $request->name;
            $model->cate_id = $request->cate_id;
            $model->status = $request->status;
            $model->code_sale = $request->code_sale;
            $model->price = $request->price;
            $model->detail = $request->detail;
            $model->amount = $request->amount;
            if ($request->image != '') {
                $path = $request->file('image')->storeAs('public/images', uniqid() . '-' . $request->image->getClientOriginalName());
                $model->image = str_replace('public/', '', $path);
            }
            $model->save();
            return response()->json([
                'success' => "Th??m s???n ph???m th??nh c??ng"
            ]);
        }
    }

    public function deletePro()
    {
    }
}