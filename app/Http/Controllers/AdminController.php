<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\domain;
use App\Models\category;
use App\Models\sub_category;
use App\Models\product;
use App\Models\product_brand;
use App\Models\product_color;
use App\Models\product_size;
use App\Models\product_unit;
use App\Models\product_stock;
use App\Models\product_detail_image;
use App\Models\homepage_section;
use App\Models\homepage_product_list;
use App\Models\banner;
use App\Models\order;
use App\Models\order_details;
use App\Models\user;
use App\Models\courier_man;
use App\Models\warehouse;
use App\Models\warehouse_product;
use App\Models\area;
use App\Models\role;
use App\Models\company_info;
use App\Models\terms_and_condition;
use App\Models\delivery_charge;
use App\Models\role_permisiion;
use App\Models\deposit;
use App\Models\expense;
use App\Models\supplier;
use App\Models\purchase;
use App\Models\package;
use App\Models\package_product;
use Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Auth;
use Session;


use App\Models\product_required_filed;

class AdminController extends Controller
{

    protected function guard()
{
    return Auth::guard('admin');
}
   // public $role_permissions;
    public function permission ()
{

    $user_id = Auth::guard('admin')->user()->id;
    $user_role = Auth::guard('admin')->user()->role;
    $role_id = DB::table('roles')->where('name',$user_role)->first()->id;
    $role_permission = DB::table('role_permisiions')->where('role_id',$role_id)->pluck('content_name')->toArray();
    return $role_permission;

}

    //login startt
    public function login(Request $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {

           // file_put_contents('test2.txt',Auth::guard('admin')->user()->name);
            return redirect('admin');

         }
         else
         {
            return back()->with('error','Email and Password not match');
         }
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->to('admin_login');
    }

    //login end

    //dashboard start
    public function show_dashboard()
    {
        // $total_product = product::where('status',1)->get()->count();
        // $total_category = category::where('status',1)->get()->count();
        // $total_sub_category = sub_category::where('status',1)->get()->count();
        // $total_order = order::get()->count();

        // $revenue = 10;
        // $purchase = 20;
        // $expense = 30;
        // $return = 25;
        // $purchase_return = 10;
        // $profit = 10;
        // $payment_recieved = [41335.40,0.00,0.00,0.00,0.00,857.00,250.00];
        // $payment_sent = [44750.00,0.00,0.00,0.00,0.00,17000.00,0.00];
        // $month = ['January','February','March','April','May','November','December'];
        // $yearly_sale_amount = [10,20,30,40,50,60,70];
        // $yearly_purchase_amount = [10,20,30,40,50,60,70];
        // $recent_sale = 10;
        // $recent_purchase = 15;
        // $recent_quotation = 10;
        // $recent_payment = 15;
        // $best_selling_qty = 20;
        // $yearly_best_selling_qty = 15;
        // $yearly_best_selling_price= 10;










     //   return view('admin.dashboard.index',compact('total_product','total_category','total_sub_category','total_order'));
        return view('admin.dashboard.index');

    }


    //dashboard end

    //domain start
    public function show_all_domain()
    {
        $datas = domain::get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.domain.all_domain',['datas'=>$datas,]);


    }

    public function add_domain_ui()
    {
        return view('admin.domain.add_domain');
    }
    public function add_domain(Request $request)
    {
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/domain_image') , $image);
    $image = "image/domain_image/" . $image;
    domain::create(['name'=>$request->name,'image'=>$image]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);

       else
       {
        domain::create(['name'=>$request->name]);
       }
        return redirect()->route('show-all-domain')->with('success','Domain Added Successfully');


    }

    public function domain_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =domain::where('id', $id)->first()->status;
        if ($status == 1)
        {
            domain::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            domain::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_domain_content_ui(Request $request)
    {
        $id = $request->id;
        $data = domain::where('id',$id)->first();
        return view('admin.domain.edit_domain_content',['data'=>$data]);

    }
    public function edit_domain_image_ui(Request $request)
    {
        $id = $request->id;
        $data = domain::where('id',$id)->first();
        return view('admin.domain.edit_domain_image',['data'=>$data]);

    }
    public function update_domain_content(Request $request)
    {
        $id = $request->id;

        domain::where('id', $id)->update(['name' => $request->name]);

        return redirect()
            ->route('show-all-domain')
            ->with('success', "Data Updated Successfully");
    }
    public function update_domain_image(Request $request)
    {
        $id = $request->id;
        $previous_image = domain::where('id',$id)->first()->image;
        if($previous_image)
        {
           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }
        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/domain_image') , $image);
        $image = "image/domain_image/" . $image;

        domain::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-domain')->with('success','Image Updated Successfully');

    }

    public function domain_content_delete(Request $request)
    {
        $id = $request->id;
        domain::where('id', $id)->update(['delete_status'=>1]);
      //  update(['delete_status'=>1])

    }

    //domain end

    //category start

    // public function show_all_category()
    // {
    //     $datas = category::get();
    //     $i=1;
    //     foreach($datas as $data)
    //     {
    //         $data['sl_no'] = $i++;
    //     }
    //     return view('admin.category.all_category',['datas'=>$datas]);


    // }

    public function show_all_category(Request $request)
    {

        if ($request->ajax()) {
            $data = category::where('delete_status',0)->get();
            $i=1;
                foreach($data as $datas)
                {
                    $checked = $datas->status=='1'?'checked':'';
                    $datas['sl_no'] = $i++;

                    $datas['checked'] =$checked;

                }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($data){

                           $switch = "<label class='switch'> <input onclick='category_active_status(".$data->id.")' type='checkbox'".$data->checked."  /> <span class='slider round'></span> </label>";

                            return $switch;
                    })
                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_category_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="category_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })

                 ->addColumn('image_edit', function($data){


                    $button = ' <a href="edit_category_image/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                    return $button;
             })
                    ->rawColumns(['status','action','image_edit'])
                    ->make(true);
        }

        return view('admin.category.all_category');

    }




    public function add_category_ui()
    {
        return view('admin.category.add_category');
    }
    public function add_category(Request $request)
    {

        $rules = [
                'name'=>'required',
                'image'=>'required'


            ];
        $customMessages = [
            'name.required' => 'Category field is required.',
            'image.required'=>'Image filed is required'


        ];

        $validator = Validator::make( $request->all(), $rules, $customMessages );


        if($validator->fails())
        {
            return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
        }


        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/category_image') , $image);
    $image = "image/category_image/" . $image;
    category::create(['name'=>$request->name,'image'=>$image]);
        }
        else
        {
            category::create(['name'=>$request->name]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);


        return redirect()->route('show-all-category')->with('success','category Added Successfully');


    }

    public function category_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =category::where('id', $id)->first()->status;
        if ($status == 1)
        {
            category::where('id', $id)->update(['status' => 0]);
            sub_category::where('category_id',$id)->update(['status'=>0]);
            product::where('category_id',$id)->update(['status'=>0]);


        }
        else
        {
            category::where('id', $id)->update(['status' => 1]);
            sub_category::where('category_id',$id)->update(['status'=>1]);
            product::where('category_id',$id)->update(['status'=>1]);
        }
    }
    public function edit_category_content_ui(Request $request)
    {
        $id = $request->id;
        $data = category::where('id',$id)->first();
        return view('admin.category.edit_category_content',['data'=>$data]);

    }
    public function edit_category_image_ui(Request $request)
    {
        $id = $request->id;
        $data = category::where('id',$id)->first();
        return view('admin.category.edit_category_image',['data'=>$data]);

    }
    public function update_category_content(Request $request)
    {
        $id = $request->id;

        category::where('id', $id)->update(['name' => $request->name,'description'=>$request->description]);

        return redirect()
            ->route('show-all-category')
            ->with('success', "Data Updated Successfully");
    }
    public function update_category_image(Request $request)
    {
        $id = $request->id;
        $previous_image = category::where('id',$id)->first()->image;
        if($previous_image)
        {

           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }


        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/category_image') , $image);
        $image = "image/category_image/" . $image;

        category::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-category')->with('success','Image Updated Successfully');

    }

    public function category_content_delete(Request $request)
    {
        $id = $request->id;
        $sub_category_status = sub_category::where('category_id',$id)->where('delete_status',0)->first();
        $product_status = product::where('category_id',$id)->where('delete_status',0)->first();
        if($sub_category_status)
        {
            echo "sub_category_exist";
        }
        else if($product_status)
        {
            echo "product_exist";
        }

        else
        {
            category::where('id', $id)->update(['delete_status'=>1]);
        }

       // file_put_contents('test.txt',"hello ".$id);



    }
    //category end

    //User Start
    public function show_all_user()
    {
        $datas = user::where('role','<>','customer')->where('role','<>','courier_man')->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.user.all',['datas'=>$datas]);


    }

    public function add_user_ui()
    {
        $datas = role::get();
        return view('admin.user.add',compact('datas'));
    }
    public function add_user(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //         'mobile_number' => ['required', 'regex:/01[13-9]\d{8}$/'],
    //      ]);
    // if($validator->fails())
    // {
    //     return redirect()->back()->with('errors',collect($validator->errors()->all()));
    // }
        $user = new user();
        $user->name = $request->name;
        $user->contact_no = $request->contact_no;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;;
        $user->save();


        return redirect()->route('show-all-user')->with('success','User Added Successfully');


    }
    public function update_user_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required','confirmed'],
         ]);

    if($validator->fails())
    {
        return redirect()->back()->with('errors',collect($validator->errors()->all()));
    }

    user::where('id',$request->id)->update(['password'=>Hash::make($request->password)]);
    return redirect()
            ->route('show-all-user')
            ->with('success', "Data Updated Successfully");

    }
    public function reset_user_password_ui($id)
    {
        return view('admin.user.reset_password',compact('id'));
    }

    public function user_active_status(Request $request)
    {
        $id = $request->id;
        $status =user::where('id', $id)->first()->status;
        if ($status == 1)
        {
            user::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            user::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_user_content_ui(Request $request)
    {
        $id = $request->id;
        $data = user::where('id',$id)->first();
        $datas = role::get();
        return view('admin.user.edit',['data'=>$data,'datas'=>$datas]);

    }

    public function update_user_content(Request $request)
    {
        $id = $request->id;
      //  file_put_contents('test.txt',$request->name." ".$request->contact_no." ".$request->email." ".$request->role);

        user::where('id', $id)->update(['name' => $request->name,'contact_no'=>$request->contact_no,'email'=>$request->email,'role'=>$request->role]);

        return redirect()
            ->route('show-all-user')
            ->with('success', "Data Updated Successfully");
    }


    public function user_content_delete(Request $request)
    {
        $id = $request->id;
        user::where('id', $id)->update(['delete_status'=>1]);

    }
    //User End


    //All courier man Start

    //All courier_man End

    //Warehouse start
    public function show_all_warehouse()
    {
        $datas = warehouse::where('delete_status',0)->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        $permission = $this->permission();
        return view('admin.warehouse.all',['datas'=>$datas,'permission'=>$permission]);


    }

    public function warehouse_product_active_status(Request $request)
    {
        $id = $request->id;
        $status =warehouse_product::where('id', $id)->first()->status;
        if ($status == 1)
        {
            warehouse_product::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            warehouse_product::where('id', $id)->update(['status' => 1]);
        }
    }

    public function show_warehouse_product(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $products = warehouse_product::where('warehouse_id',$warehouse_id)->get();
        $warehouse_name = warehouse::where('id',$warehouse_id)->first()->name;
        $i=1;
        foreach($products as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.warehouse.product',compact('products','warehouse_name'));
    }


    public function add_warehouse_ui()
    {
        $datas = area::where('status',1)->get();
        return view('admin.warehouse.add',compact('datas'));
    }
    public function add_warehouse(Request $request)
    {
        $rules = [

            'area_id'=>'required',
            'name'=>'required',
            'address'=>'required',
            'contact_no'=>'required|regex:/01[13-9]\d{8}$/',



        ];
    $customMessages = [

        'area_id.required'=>'Area field is required',
        'name.required'=>'Name field is required',
        'address.required'=>'Address field is required',
        'contact_no.required'=>'Contact no field is required',
        'contact_no.regex'=>'Please enter a valid mobile number',




    ];

    $validator = Validator::make( $request->all(), $rules, $customMessages );


    if($validator->fails())
    {
        return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
    }
       warehouse::create($request->all());
        return redirect()->route('show-all-warehouse')->with('success','warehouse Added Successfully');


    }

    public function warehouse_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =warehouse::where('id', $id)->first()->status;
        if ($status == 1)
        {
            warehouse::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            warehouse::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_warehouse_content_ui(Request $request)
    {
        $id = $request->id;
        $data = warehouse::where('id',$id)->first();
        $areas = area::where('status',1)->get();
        return view('admin.warehouse.edit',['data'=>$data,'areas'=>$areas]);

    }

    public function update_warehouse_content(Request $request)
    {
        $id = $request->id;

        warehouse::where('id', $id)->update(['area_id'=>$request->area_id,'name' => $request->name,'contact_no'=>$request->contact_no,'address'=>$request->address]);

        return redirect()
            ->route('show-all-warehouse')
            ->with('success', "Data Updated Successfully");
    }


    public function warehouse_content_delete(Request $request)
    {
        $id = $request->id;
        $warehouse_status = warehouse_product::where('warehouse_id',$id)->where('delete_status',0)->first();
        if($warehouse_status)
        {
            echo "warehouse_exist";
        }
        else
        {
        warehouse::where('id', $id)->update(['delete_status'=>1]);
        }

    }

    //warehouse end

    //role start
    public function show_all_role()
    {
        $datas = role::where('delete_status',0)->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.role.all',['datas'=>$datas]);


    }

    public function add_role_ui()
    {
        $datas = role::get();
        return view('admin.role.add',compact('datas'));
    }
    public function add_role(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //         'mobile_number' => ['required', 'regex:/01[13-9]\d{8}$/'],
    //      ]);
    // if($validator->fails())
    // {
    //     return redirect()->back()->with('errors',collect($validator->errors()->all()));
    // }
       role::create($request->all());
        return redirect()->route('show-all-role')->with('success','role Added Successfully');


    }

    public function role_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =role::where('id', $id)->first()->status;
        if ($status == 1)
        {
            role::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            role::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_role_content_ui(Request $request)
    {
        $id = $request->id;
        $data = role::where('id',$id)->first();
        //$areas = area::where('status',1)->get();
        return view('admin.role.edit_content',['data'=>$data]);

    }

    public function update_role_content(Request $request)
    {
        $id = $request->id;

        role::where('id', $id)->update(['name' => $request->name,'description'=>$request->description]);

        return redirect()
            ->route('show-all-role')
            ->with('success', "Data Updated Successfully");
    }


    public function role_content_delete(Request $request)
    {
        $id = $request->id;
        role::where('id', $id)->update(['delete_status'=>1]);

    }

    public function set_permission(Request $request)
    {
        $role_id = $request->role_id;
        //file_put_contents('test.txt',$role_id);

        if($request->has('category_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'category_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','category_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('category_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'category_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','category_add')->where('role_id',$role_id)->delete();
        }


        if($request->has('category_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'category_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','category_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('category_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'category_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','category_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('sub_category_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'sub_category_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','sub_category_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('sub_category_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'sub_category_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','sub_category_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('sub_category_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'sub_category_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','sub_category_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('sub_category_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'sub_category_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','sub_category_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('product_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'product_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','product_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('product_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'product_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','product_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('product_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'product_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','product_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('product_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'product_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','product_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('homepage_content_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'homepage_content_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','homepage_content_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('homepage_content_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'homepage_content_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','homepage_content_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('homepage_content_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'homepage_content_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','homepage_content_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('homepage_content_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'homepage_content_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','homepage_content_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('banner_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'banner_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','banner_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('banner_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'banner_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','banner_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('banner_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'banner_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','banner_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('banner_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'banner_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','banner_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('new_order_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'new_order_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','new_order_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('new_order_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'new_order_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','new_order_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('new_order_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'new_order_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','new_order_edit')->where('role_id',$role_id)->delete();
        }
        if($request->has('new_order_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'new_order_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','new_order_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('all_order_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'all_order_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','all_order_view')->where('role_id',$role_id)->delete();
        }

        if($request->has('all_order_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'all_order_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','all_order_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('all_order_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'all_order_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','all_order_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('all_order_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'all_order_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','all_order_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('courier_man_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'courier_man_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','courier_man_view')->where('role_id',$role_id)->delete();
        }


        if($request->has('courier_man_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'courier_man_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','courier_man_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('courier_man_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'courier_man_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','courier_man_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('courier_man_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'courier_man_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','courier_man_delete')->where('role_id',$role_id)->delete();
        }

        if($request->has('area_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'area_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','area_view')->where('role_id',$role_id)->delete();
        }


        if($request->has('area_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'area_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','area_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('area_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'area_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','area_edit')->where('role_id',$role_id)->delete();
        }


        if($request->has('area_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'area_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','area_delete')->where('role_id',$role_id)->delete();
        }


        if($request->has('warehouse_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'warehouse_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','warehouse_view')->where('role_id',$role_id)->delete();
        }


        if($request->has('warehouse_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'warehouse_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','warehouse_add')->where('role_id',$role_id)->delete();
        }


        if($request->has('warehouse_edit'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'warehouse_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','warehouse_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('warehouse_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'warehouse_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','warehouse_delete')->where('role_id',$role_id)->delete();
        }


        if($request->has('delivery_charge_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'delivery_charge_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','delivery_charge_view')->where('role_id',$role_id)->delete();
        }


        if($request->has('delivery_charge_add'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'delivery_charge_add','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','delivery_charge_add')->where('role_id',$role_id)->delete();
        }

        if($request->has('delivery_charge_edit'))
        {
            file_put_contents('test2.txt','delivery charge');
            role_permisiion::updateOrCreate(['content_name'=>'delivery_charge_edit','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','delivery_charge_edit')->where('role_id',$role_id)->delete();
        }

        if($request->has('delivery_charge_delete'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'delivery_charge_delete','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','delivery_charge_delete')->where('role_id',$role_id)->delete();
        }


        if($request->has('dashboard_view'))
        {
            role_permisiion::updateOrCreate(['content_name'=>'dashboard_view','role_id'=>$role_id]);
        }
        else
        {
            role_permisiion::where('content_name','dashboard_view')->where('role_id',$role_id)->delete();
        }

        return redirect()->route('show-all-role')->with('success','Permission Updated Successfully');






    }

    //role end
    //permission start
    public function show_permission_form(Request $request)
    {
        $id = $request->id;
        $role_permission = role_permisiion::where('role_id',$id)->pluck('content_name')->toArray();
       // file_put_contents('test.txt',json_encode($role_permission));

        return view('admin.role.permission',compact('role_permission','id'));
    }

    //permission end
    //area start
    public function show_all_area()
    {
        $datas = area::where('delete_status',0)->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        $permission = $this->permission();
        return view('admin.area.all',['datas'=>$datas,'role_permission'=>$permission]);


    }

    public function add_area_ui()
    {
        $datas = area::get();
        return view('admin.area.add',compact('datas'));
    }
    public function add_area(Request $request)
    {
    //     $validator = Validator::make($request->all(), [
    //         'mobile_number' => ['required', 'regex:/01[13-9]\d{8}$/'],
    //      ]);
    // if($validator->fails())
    // {
    //     return redirect()->back()->with('errors',collect($validator->errors()->all()));
    // }
       area::create($request->all());
        return redirect()->route('show-all-area')->with('success','area Added Successfully');


    }

    public function area_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =area::where('id', $id)->first()->status;
        if ($status == 1)
        {
            area::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            area::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_area_content_ui(Request $request)
    {
        $id = $request->id;
        $data = area::where('id',$id)->first();
        //$areas = area::where('status',1)->get();
        return view('admin.area.edit_content',['data'=>$data]);

    }

    public function update_area_content(Request $request)
    {
        $id = $request->id;

        area::where('id', $id)->update(['name' => $request->name]);

        return redirect()
            ->route('show-all-area')
            ->with('success', "Data Updated Successfully");
    }


    public function area_content_delete(Request $request)
    {
        $id = $request->id;
        area::where('id', $id)->update(['delete_status'=>1]);

    }

    //area end

    //expense start
    public function show_all_expense(Request $request )
      {

        if ($request->ajax()) {
            $data = expense::where('delete_status',0)->get();
            $i=1;
                foreach($data as $datas)
                {

                    $datas['sl_no'] = $i++;
                    $datas['date'] = date("d-m-Y", strtotime($datas->expense_date));







                }

            return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_expense_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="expense_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })


                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.expense.all');


      }

    //expene end


      //deposit start
      public function show_all_deposit(Request $request )
      {

        if ($request->ajax()) {
            $data = deposit::where('delete_status',0)->where('status','pending')->get();
            $i=1;
                foreach($data as $datas)
                {

                    $datas['sl_no'] = $i++;
                    $datas['date'] = date("d-m-Y", strtotime($datas->created_at));
                    $datas['order_no'] = $datas->order->order_no;
                    $datas['deposit_received_by'] = $datas->deposit_received->name;
                    $datas['courier_man'] = $datas->order->user->name;
                   $datas['address']=  $datas->order->address->area->name.','.$datas->order->address->address;
                   $datas['total_bill'] = $datas->order->total_price+$datas->order->delivery_fee;






                }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('accept_payment',function($data){
                        $button = '';
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="accept_deposit_payment('.$data->id.')">Accept</a>';
                        return $button;
                    })

                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_deposit_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="deposit_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })


                    ->rawColumns(['action','accept_payment'])
                    ->make(true);
        }

        return view('admin.deposit.all');


      }

      public function add_expense_ui()
      {
          $datas = courier_man::get();
          return view('admin.expense.add',compact('datas'));
      }
      public function add_expense(Request $request)
      {
      //     $validator = Validator::make($request->all(), [
      //         'mobile_number' => ['required', 'regex:/01[13-9]\d{8}$/'],
      //      ]);
      // if($validator->fails())
      // {
      //     return redirect()->back()->with('errors',collect($validator->errors()->all()));
      // }

      $request['deposit_received_by'] = Auth::guard('admin')->user()->id;
         deposit::create($request->all());
          return redirect()->route('show-all-deposit')->with('success','deposit Added Successfully');


      }


      public function add_deposit_ui()
      {
          $datas = courier_man::get();
          return view('admin.deposit.add',compact('datas'));
      }
      public function add_deposit(Request $request)
      {
      //     $validator = Validator::make($request->all(), [
      //         'mobile_number' => ['required', 'regex:/01[13-9]\d{8}$/'],
      //      ]);
      // if($validator->fails())
      // {
      //     return redirect()->back()->with('errors',collect($validator->errors()->all()));
      // }

      $request['deposit_received_by'] = Auth::guard('admin')->user()->id;
         deposit::create($request->all());
          return redirect()->route('show-all-deposit')->with('success','deposit Added Successfully');


      }

      public function deposit_active_status_update(Request $request)
      {
          $id = $request->id;
          $status =deposit::where('id', $id)->first()->status;
          if ($status == 1)
          {
              deposit::where('id', $id)->update(['status' => 0]);
          }
          else
          {
              deposit::where('id', $id)->update(['status' => 1]);
          }
      }
      public function edit_deposit_content_ui(Request $request)
      {
          $id = $request->id;
        //  echo $id;
          //file_put_contents('test2.txt',$id);

          $datas = deposit::where('id',$id)->first();
         // $courier_man_id = $datas->courier_man;
          //file_put_contents('test2.txt',$id.' '.json_encode($datas));
          //$courier_men = courier_man::where('status',1)->get();

          //$deposits = deposit::where('status',1)->get();
          return view('admin.deposit.edit_content',compact('datas'));
          //return view('admin.deposit.edit_content',['data'=>$data,'courier_man'=>$courier_man]);

      }

      public function update_deposit_content(Request $request)
      {
          $id = $request->id;

          deposit::where('id', $id)->update(['deposit_amount' => $request->deposit_amount,'deposit_note'=>$request->deposit_note]);

          return redirect()
              ->route('show-all-deposit')
              ->with('success', "Data Updated Successfully");
      }


      public function deposit_content_delete(Request $request)
      {
          $id = $request->id;
          deposit::where('id', $id)->update(['delete_status'=>1]);

      }

      public function accept_deposit_payment(Request $request)
      {

        $id = $request->id;
        deposit::where('id', $id)->update(['status'=>'received']);

      }
      //deposit end

    //category with domain  start

    public function show_all_category_with_domain()
    {
        $datas = category::where('domain_id','!=',NULL)->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.category_with_domain.all_category_with_domain',['datas'=>$datas]);


    }

    public function add_category_with_domain_ui()
    {
        $datas = domain::get();
        return view('admin.category_with_domain.add_category_with_domain',['datas'=>$datas]);
    }
    public function add_category_with_domain(Request $request)
    {
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/category_image') , $image);
    $image = "image/category_image/" . $image;
    category::create(['name'=>$request->name,'image'=>$image,'domain_id'=>$request->domain_id]);
        }
        else{
            category::create(['name'=>$request->name,'domain_id'=>$request->domain_id]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);


        return redirect()->route('show-all-category-with-domain')->with('success','category Added Successfully');


    }

    public function category_with_domain_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =category::where('id', $id)->first()->status;
        if ($status == 1)
        {
            category::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            category::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_category_with_domain_content_ui(Request $request)
    {
        $id = $request->id;
        $content = category::where('id',$id)->first();
        $datas = domain::get();
        return view('admin.category_with_domain.edit_category_with_domain_content',['content'=>$content,'datas'=>$datas]);

    }
    public function edit_category_with_domain_image_ui(Request $request)
    {
        $id = $request->id;
        $data = category::where('id',$id)->first();
        return view('admin.category_with_domain.edit_category_with_domain_image',['data'=>$data]);

    }
    public function update_category_with_domain_content(Request $request)
    {
        $id = $request->id;

        category::where('id', $id)->update(['name' => $request->name,'domain_id'=>$request->domain_id]);

        return redirect()
            ->route('show-all-category-with-domain')
            ->with('success', "Data Updated Successfully");
    }
    public function update_category_with_domain_image(Request $request)
    {
        $id = $request->id;
        $previous_image = category::where('id',$id)->first()->image;
        if($previous_image)
        {
           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }
        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/category_image') , $image);
        $image = "image/category_image/" . $image;

        category::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-category-with-domain')->with('success','Image Updated Successfully');

    }

    public function category_with_domain_content_delete(Request $request)
    {
        $id = $request->id;
        category::where('id', $id)->update(['delete_status'=>1]);

    }

    //category with domain end

    //subcategory start

    // public function show_all_sub_category()
    // {
    //     $datas = sub_category::where('category_id','!=',NULL)->get();
    //     $i=1;
    //     foreach($datas as $data)
    //     {
    //         $data['sl_no'] = $i++;
    //     }
    //     return view('admin.sub_category.all2',['datas'=>$datas]);


    // }

    public function show_all_sub_category(Request $request)
    {

        if ($request->ajax()) {
            $data = sub_category::where('category_id','!=',NULL)->where('delete_status',0)->get();
            $i=1;
                foreach($data as $datas)
                {
                    $checked = $datas->status=='1'?'checked':'';
                    $datas['sl_no'] = $i++;
                    $datas['category_name'] = $datas->category->name;
                    $datas['checked'] =$checked;

                }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($data){

                           $switch = "<label class='switch'> <input onclick='sub_category_active_status(".$data->id.")' type='checkbox'".$data->checked."  /> <span class='slider round'></span> </label>";

                            return $switch;
                    })
                    ->addColumn('action', function($data){
                        $permission = $this->permission();
                        $button = '';
                        if(in_array('sub_category_edit',$permission))
                        $button .= ' <a href="edit_sub_category_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('sub_category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="sub_category_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })

                 ->addColumn('image_edit', function($data){


                    $button = ' <a href="edit_sub_category_image/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                    return $button;
             })
                    ->rawColumns(['status','action','image_edit'])
                    ->make(true);
        }

        return view('admin.sub_category.all2');

    }

    public function add_sub_category_ui()
    {
        $datas = category::get();
        return view('admin.sub_category.add',['datas'=>$datas]);
    }
    public function add_sub_category(Request $request)
    {

        $rules = [
            'name'=>'required',

            'category_id'=>'required',
            'image'=>'required',

        ];
    $customMessages = [
        'name.required' => 'Sub Category field is required.',

        'category_id.required' => 'Category field is required.',
        'image.required'=>'Image field is required'


    ];

    $validator = Validator::make( $request->all(), $rules, $customMessages );


    if($validator->fails())
    {
        return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
    }
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/sub_category_image') , $image);
    $image = "image/sub_category_image/" . $image;
    sub_category::create(['name'=>$request->name,'image'=>$image,'category_id'=>$request->category_id]);
        }
        else{
            sub_category::create(['name'=>$request->name,'domain_id'=>$request->domain_id]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);


        return redirect()->route('show-all-sub-category')->with('success','category Added Successfully');


    }

    public function sub_category_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =sub_category::where('id', $id)->first()->status;
        if ($status == 1)
        {
            sub_category::where('id', $id)->update(['status' => 0]);
            product::where('sub_category_id',$id)->update(['status' => 0]);
        }
        else
        {
            sub_category::where('id', $id)->update(['status' => 1]);
             product::where('sub_category_id',$id)->update(['status' => 1]);
        }
    }
    public function edit_sub_category_content_ui(Request $request)
    {
        $id = $request->id;
        $content = sub_category::where('id',$id)->first();
        $datas = category::get();
        return view('admin.sub_category.edit_content',['content'=>$content,'datas'=>$datas]);

    }
    public function edit_sub_category_image_ui(Request $request)
    {
        $id = $request->id;
        $data = sub_category::where('id',$id)->first();
        return view('admin.sub_category.edit_image',['data'=>$data]);

    }
    public function update_sub_category_content(Request $request)
    {
        $id = $request->id;

        sub_category::where('id', $id)->update(['name' => $request->name,'category_id'=>$request->category_id]);

        return redirect()
            ->route('show-all-sub-category')
            ->with('success', "Data Updated Successfully");
    }
    public function update_sub_category_image(Request $request)
    {
        $id = $request->id;
        $previous_image = sub_category::where('id',$id)->first()->image;
        if($previous_image)
        {
           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }
        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/sub_category_image') , $image);
        $image = "image/sub_category_image/" . $image;

        sub_category::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-sub-category')->with('success','Image Updated Successfully');

    }

    public function sub_category_content_delete(Request $request)
    {
        $id = $request->id;
        $product_status = product::where('sub_category_id',$id)->where('delete_status',0)->first();
        if($product_status)
        {
            echo "product_exist";
        }
        else
        {
        sub_category::where('id', $id)->update(['delete_status'=>1]);
        }

    }
    //subcategory end

    //brand start
    public function show_all_brand()
    {

        $datas = product_brand::where('sub_category_id','!=',NULL)->get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.brand.all',['datas'=>$datas]);


    }

    public function add_brand_ui()
    {
        $datas = sub_category::get();
        return view('admin.brand.add',['datas'=>$datas]);
    }
    public function add_brand(Request $request)
    {
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/brand_image') , $image);
    $image = "image/brand_image/" . $image;
    product_brand::create(['brand_name'=>$request->name,'image'=>$image,'sub_category_id'=>$request->sub_category_id]);
        }
        else{
            product_brand::create(['brand_name'=>$request->name,'sub_category_id'=>$request->sub_category_id]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);


        return redirect()->route('show-all-brand')->with('success','category Added Successfully');


    }

    public function brand_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =product_brand::where('id', $id)->first()->status;
        if ($status == 1)
        {
            product_brand::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            product_brand::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_brand_content_ui(Request $request)
    {
        $id = $request->id;
        $content = product_brand::where('id',$id)->first();
        $datas = category::get();
        return view('admin.brand.edit_content',['content'=>$content,'datas'=>$datas]);

    }
    public function edit_brand_image_ui(Request $request)
    {
        $id = $request->id;
        $data = product_brand::where('id',$id)->first();
        return view('admin.brand.edit_image',['data'=>$data]);

    }
    public function update_brand_content(Request $request)
    {
        $id = $request->id;

        product_brand::where('id', $id)->update(['brand_name' => $request->brand_name,'sub_category_id'=>$request->sub_category_id]);

        return redirect()
            ->route('show-all-brand')
            ->with('success', "Data Updated Successfully");
    }
    public function update_brand_image(Request $request)
    {
        $id = $request->id;
        $previous_image = product_brand::where('id',$id)->first()->image;
        if($previous_image)
        {
           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }
        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/brand_image') , $image);
        $image = "image/brand_image/" . $image;

        product_brand::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-brand')->with('success','Image Updated Successfully');

    }

    public function brand_content_delete(Request $request)
    {
        $id = $request->id;
        product_brand::where('id', $id)->update(['delete_status'=>1]);

    }

    //brand end

    //product start

    public function get_all_product(Request $request)
    {
        //file_put_contents('test.txt',$request." ".time());
        if ($request->ajax()) {
            $datas = product::with('category:id,name','sub_category:id,category_id,name','unit:id,unit_type,unit_quantity')->where('delete_status',0)->select(['*']);


            $i=1;
                foreach($datas as $data)
                {
                  $checked = $data->status=='1'?'checked':'';
                    $data->sl_no= $i++;
                   // $data['category_name'] = $data->sub_category->category->name;

                    $data->chekced =$checked;

                }

              //  file_put_contents('test.txt',$datas);

            return Datatables::eloquent($datas)

                    ->addIndexColumn()

                    ->addColumn('status', function($datas){

                            if($datas->status == 1)
                           $switch = "<label class='switch'> <input onclick='product_active_status(".$datas->id.")' type='checkbox' checked /> <span class='slider round'></span> </label>";
                           else
                           $switch = "<label class='switch'> <input onclick='product_active_status(".$datas->id.")' type='checkbox' /> <span class='slider round'></span> </label>";

                            return $switch;
                    })

                    ->addColumn('category_name', function($datas){
                        $permission = $this->permission();

                        if(in_array('product_edit',$permission))

                        $column = '<p onclick='.'edit('. $datas->id.',"category")'.'>'. $datas->category->name .'</p>';
                        else
                        $column = '<p >'. $datas->sub_category->category->name .'</p>';
                         return $column;
                 })

                 ->addColumn('sub_category_name', function($datas){

                    $permission = $this->permission();

                    if(in_array('product_edit',$permission))
                    $column = '<p onclick='.'edit('. $datas->id.',"sub_category")'.'>'. $datas->sub_category->name.'</p>';
                    else
                    $column = '<p >'. $datas->sub_category->name.'</p>';

                     return $column;
                 })
                 ->addColumn('product_name', function($datas){
                    $permission = $this->permission();

                    if(in_array('product_edit',$permission))
                    $column = '<p onclick='.'edit('. $datas->id.',"product_name")'.'>'. $datas->name .'</p>';
                    else
                    $column = '<p>'. $datas->name .'</p>';

                     return $column;
                 })



                //  ->addColumn('warehouse', function($datas){

                //     $column = '<p onclick='.'edit('. $datas->id.',"warehouse")'.'>'. $datas->warehouse ? $datas->warehouse->warehouse->name : 'No Name' .'</p>';

                //      return $column;
                //  })

                 ->addColumn('product_image', function($datas){
                    $permission = $this->permission();
                    $url = $datas->thumbnail_image;
                    $type = pathinfo($url, PATHINFO_EXTENSION);
                    $image = file_get_contents($url);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);

                    if(in_array('product_edit',$permission))
                    {
                    $column = '<img  onclick='.'edit('. $datas->id.',"product_image")'.'  src="'.$base64.'"  width="100px" class="img-thumbnail product-image lazy" />';
                    }
                    else
                    $column = '<img   src="../'.$datas->thumbnail_image.'" width="100px" class="img-thumbnail lazy" />';
                     return $column;
                 })
                 ->addColumn('product_price', function($datas){
                    $permission = $this->permission();

                    if(in_array('product_edit',$permission))
                    $column = '<p onclick='.'edit('. $datas->id.',"product_price")'.'>'. $datas->price .'</p>';
                    else
                    $column = '<p>'. $datas->price .'</p>';

                     return $column;
                 })
                 ->addColumn('product_unit_type', function($datas){
                    $permission = $this->permission();

                    if(in_array('product_edit',$permission))
                    $column = '<p onclick='.'edit('. $datas->id.',"product_unit_type")'.'>'. $datas->unit->unit_type .'</p>';
                    else
                    $column = '<p >'. $datas->unit->unit_type .'</p>';

                     return $column;
                 })
                 ->addColumn('product_unit_quantity', function($datas){

                    $column = '<p onclick='.'edit('. $datas->id.',"product_unit_quantity")'.'>'. $datas->unit->unit_quantity .'</p>';

                     return $column;
                 })
                 ->addColumn('produc_stock_amount', function($datas){
                    $permission = $this->permission();

                    if(in_array('product_edit',$permission))
                    $column = '<p >'. $datas->stock .'</p>';
                    else
                    $column = '<p >'. $datas->stock .'</p>';
                     return $column;
                 })
                 ->addColumn('action', function($data){

                    $permission = $this->permission();
                    $button = '';

                    if(in_array('category_delete',$permission))
                    $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="product_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                    else
                    $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                    return $button;
             })




                    ->rawColumns(['status','category_name','sub_category_name','product_name','product_image','product_price','product_unit_type','product_unit_quantity','produc_stock_amount','action'])
                    ->make(true);
        }

        return view('admin.product.all2');
    }


    // public function get_all_product()
    // {
    //     $datas = product::get();
    //     $i=1;
    //     $product = '';
    //     foreach($datas as $data)
    //     {
    //         $checked = $data->status=='1'?'checked':'';

    //         $product.='    <tr>



    //             <td><strong>'.$i++.'</strong></td>
    //             <td onclick='.'edit('. $data->id.',"category")'.'>'. $data->sub_category->category->name .'</td>
    //             <td onclick='.'edit('. $data->id.',"sub_category")'.'>'.$data->sub_category->name.'</td>

    //             <td onclick='.'edit('.$data->id.',"product_name")'.'>'.$data->name.'</td>
    //             <td onclick='.'edit('. $data->id.',"product_image")'.' ><img  width="50" src="../'.$data->thumbnail_image.'"  alt="Not Available"></td>
    //             <td onclick='.'edit('. $data->id.',"product_price")'.'>'. $data->price .'</td>

    //             <td onclick='.'edit('. $data->id.',"product_unit_type")'.'>'. $data->unit->unit_type .'</td>
    //             <td onclick='.'edit('. $data->id.',"product_unit_quantity")'.'>'. $data->unit->unit_quantity .'</td>
    //             <td onclick='.'edit('. $data->id.',"produc_stock_amount")'.'>'. $data->stock .'</td>
    //             <td> <label class="switch">
    //                 <input type="checkbox"  onclick="product_active_status('.$data->id.')" '.$checked.'>
    //                     <span class="slider round"></span>
    //                 </label></td>



    //         </tr>';

    //     }


    //     echo $product;




    // }

    public function show_all_product()
    {

        return view('admin.product.all2');


    }


    public function add_product_ui()
    {
       //$field_array = array();
       $description_status = 0;
       $detail_image_status = 0;
       $brand_status = 0;
       $size_status = 0;
       $color_status =0;
       $datas = product_required_filed::get();
        foreach($datas as $data)
        {
            if($data->name == "Product Description" && $data->status == 1)
            {

                $description_status = 1;
            }
           else if($data['name'] =="Product Detail Image" && $data['status']== 1)
            {
                $detail_image_status = 1;
            }
            else if($data['name'] == "Product Brand" && $data['status']== 1)
            {
                $brand_status = 1;
            }
            else if($data['name'] == "Product Size" && $data['status']== 1)
            {
                $size_status = 1;
            }
            else if($data['name'] == "Product Color" && $data['status'] == 1)
            {
                $color_status = 1;
            }


        }
        $warehouses = warehouse::where('status',1)->where('delete_status',0)->get();
        file_put_contents('test.txt',json_encode($warehouses));
        return view('admin.product.add',['description_status'=>$description_status,'detail_image_status'=>$detail_image_status,'brand_status'=>$brand_status,'color_status'=>$color_status,'size_status'=>$size_status,'warehouses'=>$warehouses]);
    }
    public function add_product(Request $request)

    {
        $rules = ['category_id'=>'required',
                 'warehouse_id'=>'required',
                 'name'=>'required',
                 'price'=>'required',
                 'thumbnail_image'=>'required',
                 'unit_type'=>'required',
                 'unit_quantity'=>'required',

                 'net_weight'=>'required',

            ];
        $customMessages = [
            'category_id.required' => 'Category field is required.',
            'warehouse_id.required' => 'Warehouse field is required.',
            'name.required' => 'Product name  field is required.',
            'price.required' => 'Product price field is required.',
            'thumbnail_image.required' => 'Product image field is required.',
            'unit_type.required' => 'Product unit type field is required.',
            'unit_quantity.required' => 'Product unit quantity field is required.',
            'net_weight.required' => 'Product net weight field is required.',



        ];
        $validator = Validator::make( $request->all(), $rules, $customMessages );

        // $validator = Validator::make($request->all(), [
        //     'category_id' => ['required'],
        //     'warehouse_id'=>['required'],
        //  ]);
    if($validator->fails())
    {
        return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
    }
    $product_unit = product_unit::create(['unit_quantity'=>$request->unit_quantity,'unit_type'=>$request->unit_type]);
        $image = time() . '.' . request()->thumbnail_image->getClientOriginalExtension();


        $request->thumbnail_image->move(public_path('../image/product_image') , $image);
        $image = "image/product_image/" . $image;
        if($request->description)
        {
            $description = $request->description;
        }
        else
        {
            $description = NULL;
        }
        $user_role = Auth::guard('admin')->user()->role;

        if($user_role == 'Admin' || $user_role == 'admin')
        {
            $product = product::create(['stock'=>0,'unit_id'=>$product_unit->id,'category_id'=>$request->category_id,'sub_category_id'=>$request->sub_category_id,'brand_id'=>$request->brand_id,'name'=>$request->name,'price'=>$request->price,'thumbnail_image'=>$image,'description'=>$description,'net_weight'=>$request->net_weight]);

        }
        else
        {
            $product = product::create(['stock'=>0,'category_id'=>$request->category_id,'sub_category_id'=>$request->sub_category_id,'brand_id'=>$request->brand_id,'name'=>$request->name,'price'=>$request->price,'thumbnail_image'=>$image,'description'=>$description,'net_weight'=>$request->net_weight,'status'=>0]);
        }

        //$product = product::create(['category_id'=>$request->category_id,'sub_category_id'=>$request->sub_category_id,'brand_id'=>$request->brand_id,'name'=>$request->name,'price'=>$request->price,'thumbnail_image'=>$image,'description'=>$description,'net_weight'=>$request->net_weight]);
       $product_id = $product->id;


     // product_stock::create(['product_id'=>$product_id,'stock_amount'=>'0']);
       warehouse_product::create(['product_id'=>$product_id,'warehouse_id'=>$request->warehouse_id]);




    return redirect()->route('show-all-product')->with('success','Product Added Successfully');


    }

    public function product_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =product::where('id', $id)->first()->status;
        if ($status == 1)
        {
            product::where('id', $id)->update(['status' => 0]);
            homepage_product_list::where('product_list',$id)->update(['status'=>0]);
        }
        else
        {
            product::where('id', $id)->update(['status' => 1]);
            homepage_product_list::where('product_list',$id)->update(['status'=>1]);
        }
    }
    public function edit_product_content_ui(Request $request)
    {
        $id = $request->id;
        $data = product::where('id',$id)->first();
        return view('admin.product.edit_content',['data'=>$data]);

    }
    public function edit_product_image_ui(Request $request)
    {
        $id = $request->id;
        $data = product::where('id',$id)->first();
        return view('admin.product.edit_image',['data'=>$data]);

    }
    public function update_product_content(Request $request)
    {
        $id = $request->id;

        product::where('id', $id)->update(['name' => $request->name]);

        return redirect()
            ->route('show-all-product')
            ->with('success', "Data Updated Successfully");
    }
    public function update_product_image(Request $request)
    {
        $id = $request->id;
        $previous_image = product::where('id',$id)->first()->image;
        if($previous_image)
        {
           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }
        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/product_image') , $image);
        $image = "image/product_image/" . $image;

        product::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-product')->with('success','Image Updated Successfully');

    }

    public function product_content_delete(Request $request)
    {
        $id = $request->id;
        product::where('id', $id)->update(['delete_status'=>1]);

    }

    public function get_category()
    {
        $categories = category::get();
        $data = '<option disabled selected>Select Category</option>';
        foreach($categories as $category)
        {

            $data.='<option value='.$category->id.'>'.$category->name.'</option>';
        }
        return $data;
    }

    public function get_sub_category(Request $request)
    {
        $category_id = $request->category_id;
        $sub_categories = sub_category::where('category_id',$category_id)->get();
        $data = '<option disabled selected>Select Sub Category</option>';
        foreach($sub_categories as $sub_category)
        {
            $data.='<option value='.$sub_category->id.'>'.$sub_category->name.'</option>';
        }
        return $data;
    }

    public function get_brand(Request $request)
    {
        $sub_category_id = $request->sub_category_id;
        $brands = product_brand::where('sub_category_id',$sub_category_id)->get();
        $data = '<option>Selet Brand</option>';
        foreach($brands as $brand)
        {
            $data.='<option value='.$brand->id.'>'.$brand->brand_name.'</option>';
        }
        return $data;
    }

    public function show_all_product_field()
    {
        $datas = product_required_filed::get();
        $i=1;
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        return view('admin.product.product-field',['datas'=>$datas]);
    }

    public function product_required_field_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =product_required_filed::where('id', $id)->first()->status;
        if ($status == 1)
        {
            product_required_filed::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            product_required_filed::where('id', $id)->update(['status' => 1]);
        }

    }

    public function get_product_update_modal(Request $request)
    {

        $column_name = $request->column_name;
        $product_id = $request->product_id;
        $data= product::where('id',$product_id)->first();
        //file_put_contents('test.txt',$column_name);

        $data ='  <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="product_content">';
        if($column_name == 'category')
        {


            $contents = category::get();
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">

                <label>Category</label>
                <select class="form-control select2 category" id="value" name="category_id">
                <option>Select Category</option>
                ';
                foreach($contents  as $content)
                {
                    $data.='<option value="'.$content->id.'">'.$content->name.'</option>';
                }
                $data.='

                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select class="form-control select2 sub_category" id="value" name="sub_category_id">
                <option>Select Category First</option>
                </select>
             </div>

        </div>';
        }



        if($column_name == 'sub_category')
        {
            $product = product::where('id',$product_id)->first();
            $category_id = $product->sub_category->category->id;
            //file_put_contents('test.txt',$category_id);


            $contents = sub_category::where('category_id',$category_id)->get();


            $data.='<div class="col-lg-12 col-md-12 col-sm-12">

            <div class="form-group">
                <label>Sub Category</label>
                <select class="form-control select2 sub_category " id="value" name="sub_category_id">
                <option>Select Sub Category</option>
                ';
                foreach($contents  as $content)
                {
                    $data.='<option value="'.$content->id.'">'.$content->name.'</option>';
                }
                $data.='
                </select>
            </div>

        </div>';
        }
        if($column_name =='brand_category')
        {
            $product = product::where('id',$product_id)->first();
            $contents = product_brand::where('sub_category_id',$product->sub_category_id)->get();
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Brand</label>
                <select class="form-control select2 brand" id="value" name="sub_category_id">
                ';
                foreach($contents  as $content)
                {
                    $data.='<option value="'.$content->id.'">'.$content->brand_name.'</option>';
                }
                $data.='

                </select>
            </div>
        </div>';
        }

        if($column_name =='warehouse')
        {
           // $product = product::where('id',$product_id)->first();
            $contents = warehouse::where('status',1)->get();
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Warehouse</label>
                <select class="form-control select2 warehouse_id" id="value" name="warehouse_id">
                ';
                foreach($contents  as $content)
                {
                    $data.='<option value="'.$content->id.'">'.$content->name.'</option>';
                }
                $data.='

                </select>
            </div>
        </div>';
        }
        if($column_name == 'product_name')
        {
            $product_name = product::where('id',$product_id)->first()->name;
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" id="value" name="value" value="'.$product_name.'"  />
            </div>
        </div>';
        }




        if($column_name == 'product_image')
        {
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
                         <div class="form-group">
                                    <div class="field" align="left">
                                        <label class="form-label">Product Thumbnail Image</label>
                                        <input type="file" id="files" name="thumbnail_image"  accept="image/*" multiple />
                                    </div>
                            </div>
        </div>
        <script src="../assets/admin/js/single_image_preview.js?'.time().'"></script>
        ';
        }

        if($column_name == 'product_price')
        {
            $product_price = product::where('id',$product_id)->first()->price;
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Product Unit Price</label>
                <input type="text" class="form-control" id="value" name="name" value="'.$product_price.'"  />
            </div>
        </div>';
        }

        if($column_name == 'product_unit_type')
        {
            $product = product::where('id',$product_id)->first();
            $product_unit_type = $product->unit->unit_type;
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Product Unit Type</label>
                <input type="text" class="form-control" id="value" name="name" value="'.$product_unit_type.'"  />
            </div>
        </div>';
        }

        if($column_name == 'product_unit_quantity')
        {
            $product = product::where('id',$product_id)->first();
            $product_unit_quantity = $product->unit->unit_quantity;
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Product Unit Quanity</label>
                <input type="text" class="form-control" id="value" name="name" value="'.$product_unit_quantity.'"  />
            </div>
        </div>';
        }

        if($column_name == 'produc_stock_amount')
        {
            $product = product::where('id',$product_id)->first();
            $product_stock_amount = $product->stock;
            $data.='<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label>Product Stock Amount</label>
                <input type="text" class="form-control" id="value" name="name" value="'.$product_stock_amount.'"  />
            </div>
        </div>';
        }


        $data.=' </div>
        <div class="modal-footer">
            <input type="hidden" id="product_id" value="'.$product_id.'">
            <input type="hidden" id="column_name" value="'.$column_name.'">
            <button type="button" onclick="update_product_value()" class="btn btn-primary">Update</button>
        </div>

        <script src="../assets/admin/js/admin.js?'.time().'"></script>

          <script src="../assets/admin/js/select2.full.js?'.time().'"></script>
        <script src="../assets/admin/js/advanced-form-element.js?'.time().'"></script>
        ';



        return $data;
    }

    public function update_product_value(Request $request)
    {
        $column_name = $request->column_name;
        $product_id = $request->product_id;
        $input_value = $request->input_value;
       // file_put_contents('test.txt','hello');
        if($column_name == 'category')
        {

            $sub_category_id = $request->sub_category;
            $category_id = $request->category;
           // $brand_id = 1;

           //file_put_contents('test.txt',$sub_category_id." ".$category_id);

           product::where('id',$product_id)->update(['category_id'=>$category_id,'sub_category_id'=>$sub_category_id]);

        }
        if($column_name == 'sub_category')
        {
            $sub_category_id = $request->sub_category;

            //$brand_id = 1;
           // file_put_contents('test.txt',$product_id." ".$brand_id." ".$sub_category_id);
            product::where('id',$product_id)->update(['sub_category_id'=>$sub_category_id]);

        }




        if($column_name =='brand_category')
        {

            $brand_id = $request->brand_id;
           // file_put_contents('test.txt',$product_id." ".$brand_id." ".$sub_category_id);
            product::where('id',$product_id)->update(['sub_category_id'=>$sub_category_id]);
        }
        if($column_name == 'product_name')
        {
            product::where('id',$product_id)->update(['name'=>$input_value]);
        }


        if($column_name == 'product_image')
        {
            $image = time() . '.' . request()->thumbnail_image->getClientOriginalExtension();

            $request->thumbnail_image->move(public_path('../image/product_image') , $image);
            $image = "image/product_image/" . $image;
            product::where('id',$product_id)->update(['thumbnail_image'=>$image]);
        }

        if($column_name == 'product_price')
        {
            product::where('id',$product_id)->update(['price'=>$input_value]);
        }

        if($column_name == 'product_unit_type')
        {
            product_unit::where('product_id',$product_id)->update(['unit_type'=>$input_value]);
        }

        if($column_name == 'product_unit_quantity')
        {
            product_unit::where('product_id',$product_id)->update(['unit_quantity'=>$input_value]);
        }

        if($column_name == 'produc_stock_amount')
        {
            product_stock::where('product_id',$product_id)->update(['stock_amount'=>$input_value]);
        }

        if($column_name == 'warehouse')
        {
            $warehouse_id = $request->warehouse_id;
            warehouse_product::where('product_id',$product_id)->update(['warehouse_id'=>$warehouse_id]);
        }




    }



    //product end

    //purchase start
    public function show_all_purchase(Request $request)
    {

        if ($request->ajax()) {
            $data = purchase::get();
            $i=1;
                foreach($data as $datas)
                {
                    //$checked = $datas->status=='1'?'checked':'';
                    $datas['sl_no'] = $i++;
                    $datas->supplier = $datas->supplier->supplier_name;
                    $datas->product = $datas->product->name;
                    //file_put_contents('test.txt',$datas->product->name);

                   // $datas['checked'] =$checked;

                }


            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('supplier',function($data)
                    {
                        return $data->supplier;
                    })
                    ->addColumn('product',function($data)
                    {
                        return $data->product;
                    })
                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_purchase_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="purchase_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })


                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.purchase.all');


    }
    public function edit_purchase_content_ui(Request $request)
    {

        $id = $request->id;

        $datas = purchase::where('id',$id)->first();
        file_put_contents('test.txt',json_encode($datas));

        $suppliers = supplier::get();
        $products = product::where('delete_status',0)->get();
        return view('admin.purchase.edit',compact('datas','products','suppliers'));

    }
    public function update_purchase(Request $request)
    {
        purchase::where('id',$request->id)->update($request->except('_token'));
        return redirect()
        ->route('show-all-purchase')
        ->with('success', "Data Updated Successfully");
    }

    public function add_purchase_ui()
    {
        $products = product::where('delete_status',0)->get();
        $suppliers = supplier::get();
        return view('admin.purchase.add',compact('products','suppliers'));


    }

    public function add_purchase(Request $request)
    {
        $product_id = $request->product_id;
        $supplier_id = $request->supplier_id;
        $purchase_quantity = $request->product_quantity;
        $unit_purchasing_price = $request->unit_purchasing_price;
        $net_price = $purchase_quantity*$unit_purchasing_price;
        $total_price = $net_price+($net_price*($request->vat/100))-($net_price*($request->discount/100))+$request->shipping_cost;
        $purchase_note = $request->purchase_note;
        purchase::create([
            'supplier_id'=>$supplier_id,
            'product_id'=>$product_id,
            'product_quantity'=>$purchase_quantity,
            'unit_purchasing_price'=>$unit_purchasing_price,
            'net_price'=>$net_price,
            'discount'=>$request->discount,
            'vat'=>$request->vat,
            'shipping_cost'=>$request->shipping_cost,
            'purchase_note'=>$purchase_note,
            'total_price'=>$total_price


        ]);

        $remaining_stock = product_stock::where('product_id',$product_id)->first()->stock_amount;
        $stock = $remaining_stock+$purchase_quantity;
        product_stock::where('product_id',$product_id)->update(['stock_amount'=>$stock]);

            return back()->with('success','Data Addess Successfully');


    }

    //purchase end

    public function show_all_report(Request $request)
    {
        if ($request->ajax()) {
            $data = order::where('status','delivered')->where('delete_status',0)->get();
            $i=1;
                foreach($data as $datas)
                {
                    //$checked = $datas->status=='1'?'checked':'';
                    $datas['sl_no'] = $i++;

                }


            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_name',function($data)
                    {
                        return $data->user->name;
                    })
                    ->addColumn('address',function($data)
                    {
                        return $data->address->address;
                    })
                    ->addColumn('contact_no',function($data)
                    {
                        return $data->address->contact_no;
                    })
                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_category_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="category_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })


                    ->rawColumns(['customer_name,address,contact_no,action'])
                    ->make(true);
        }

        return view('admin.report.show');
    }
    public function show_courier_report(Request $request)
    {

        if(!$request->ajax())
        {



        $from_date = $request->from_date;
        $from_date =  date("Y-m-d", strtotime($from_date));
        Session::put('from_date',$from_date);
        $to_date = $request->to_date;
        $to_date =  date("Y-m-d", strtotime($to_date."+1 days"));
        Session::put('to_date',$to_date);


        }


        if ($request->ajax()) {
            $from_date = Session::get('from_date');
            $to_date = Session::get('to_date');
            $data = deposit::whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
            $i=1;
                foreach($data as $datas)
                {

                    $datas['sl_no'] = $i++;
                    $datas['date'] = date("d-m-Y", strtotime($datas->created_at));
                    $datas['order_no'] = $datas->order->order_no;
                    $datas['deposit_received_by'] = $datas->deposit_received->name;
                    $datas['courier_man'] = $datas->order->user->name;
                   $datas['address']=  $datas->order->address->area->name.','.$datas->order->address->address;
                   $datas['total_bill'] = $datas->order->total_price+$datas->order->delivery_fee;



                }

            return Datatables::of($data)
                    ->addIndexColumn()

                    ->make(true);
        }




        return view('admin.report.courier_man_report');






       // file_put_contents('test.txt',$from_date." ".$to_date);
    }
    public function show_order_report(Request $request)
    {

        if(!$request->ajax())
        {



        $from_date = $request->from_date;
        $from_date =  date("Y-m-d", strtotime($from_date));
        Session::put('from_date',$from_date);
        $to_date = $request->to_date;
        $to_date =  date("Y-m-d", strtotime($to_date."+1 days"));
        Session::put('to_date',$to_date);


        }




        if ($request->ajax()) {

           $from_date = Session::get('from_date');
           $to_date = Session::get('to_date');
           //file_put_contents('test2.txt',$from_date." ".$to_date);
            $data = order::whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->get();
           // file_put_contents('test.txt',json_encode($data));

            $i=1;
                foreach($data as $datas)
                {
                    //$checked = $datas->status=='1'?'checked':'';
                    $datas['sl_no'] = $i++;

                }


            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer_name',function($data)
                    {
                        return $data->user->name;
                    })
                    ->addColumn('address',function($data)
                    {
                        return $data->address->address;
                    })
                    ->addColumn('contact_no',function($data)
                    {
                        return $data->address->contact_no;
                    })
                    ->addColumn('order_date',function($data)
                    {
                        $date =  date("Y-m-d h:i:s", strtotime($data->created_at));
                        return $date;

                    })
                    ->addColumn('action', function($data){

                        $permission = $this->permission();
                        $button = '';
                        if(in_array('category_edit',$permission))
                        $button .= ' <a href="edit_category_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" onclick="access_alert()" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if(in_array('category_delete',$permission))
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="category_content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        else
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="access_alert()"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })


                    ->rawColumns(['customer_name,address,contact_no,action'])
                    ->make(true);
        }
        return view('admin.report.order_report');





       // file_put_contents('test.txt',$from_date." ".$to_date);
    }
    public function report_view($type)
    {
        return view('admin.report.date_view',['type'=>$type]);
    }





    //homepage section start

    public function show_all_homepage_section()
    {
        //echo "hello";
                //
        $datas = homepage_section::orderBy('section_order')->where('delete_status',0)->get();
        //echo "hello";
        $i=1;
        $permission = $this->permission();
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        //echo "hello";
        return view('admin.homepage_section.all',['datas'=>$datas,'permission'=>$permission]);


    }

    public function add_homepage_section_ui()
    {
        return view('admin.homepage_section.add');
    }

    public function add_package_ui()
    {
        return view('admin.package.add');
    }

    public function add_package(Request $request)
    {
        $rules = [
            'name'=>'required',
            'image'=>'required',
            'discount_percentage'=>'required'


            ];
        $customMessages = [
            'name.required' => 'Package name field is required.',
            'image.required'=>'Package image field is required',
            'discount_percentage.required'=>'Discount percentage filed is required',


        ];

        $validator = Validator::make( $request->all(), $rules, $customMessages );


        if($validator->fails())
        {
            return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
        }
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/package_image') , $image);
    $image = "image/package_image/" . $image;

        }
        package::create(['package_name'=>$request->name,'package_image'=>$image,'discount_percentage'=>$request->discount_percentage]);

        return redirect()->route('show_all_package')->with('success','Package Added Successfully');



    }

    public function delete_product_from_package(Request $request)
    {
        $id = $request->id;
        $package_product= package_product::where('id',$id)->first();
        $package = package::where('id',$package_product->package_id)->first();
        $total_price = $package->total_price;
        $discount = $package->discount_percentage;
        $total_price = $total_price-($package_product->product->price*$package_product->unit_quantity);
        //file_put_contents('test.txt',$total_price." ".$package_product->product->price." ".$package_product->unit_quantity);
        $discount_price = $total_price - floor(($total_price *  $discount)/100);

        package::where('id',$package_product->package_id)->update(['total_price'=>$total_price,'discount_price'=>$discount_price]);
        package_product::where('id', $id)->update(['delete_status'=>1]);



    }
    public function show_all_package()
    {
        $datas = package::where('delete_status',0)->get();
        //echo "hello";
        $i=1;
        $permission = $this->permission();
        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        //echo "hello";
        return view('admin.package.all',['datas'=>$datas,'permission'=>$permission]);

    }
    public function add_homepage_section(Request $request)
    {
        $rules = [
            'name'=>'required',
            'image'=>'required'


        ];
    $customMessages = [
        'name.required' => 'Section name field is required.',
        'image.required'=>'Section image field is required'


    ];

    $validator = Validator::make( $request->all(), $rules, $customMessages );


    if($validator->fails())
    {
        return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
    }

        $homepage_section = homepage_section::get();
        if(sizeof($homepage_section)>0)
        {
            $last_insert_id = homepage_section::orderBy('section_order','DESC')->first()->section_order;

        }
        else
        {
            $last_insert_id = 0;
        }
        if($request->image)
        {
        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/homepage_section_image') , $image);
    $image = "image/homepage_section_image/" . $image;
    homepage_section::create(['section_name'=>$request->name,'image'=>$image,'section_order'=>$last_insert_id+1]);
        }
       //file_put_contents('test.txt',$request->name." ".$request->image);
        else

        {
            homepage_section::create(['section_name'=>$request->name,'section_order'=>$last_insert_id+1]);
        }

        return redirect()->route('show-all-homepage_section')->with('success','homepage_section Added Successfully');


    }

    public function homepage_section_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =homepage_section::where('id', $id)->first()->status;
        if ($status == 1)
        {
            homepage_section::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            homepage_section::where('id', $id)->update(['status' => 1]);
        }
    }
    public function edit_homepage_section_content_ui(Request $request)
    {
        $id = $request->id;
        $data = homepage_section::where('id',$id)->first();
        return view('admin.homepage_section.edit_content',['data'=>$data]);

    }
    public function edit_homepage_section_image_ui(Request $request)
    {
        $id = $request->id;
        $data = homepage_section::where('id',$id)->first();
        return view('admin.homepage_section.edit_image',['data'=>$data]);

    }
    public function update_homepage_section_content(Request $request)
    {
        $id = $request->id;

        homepage_section::where('id', $id)->update(['section_name' => $request->name]);

        return redirect()
            ->route('show-all-homepage_section')
            ->with('success', "Data Updated Successfully");
    }
    public function update_homepage_section_image(Request $request)
    {
        $id = $request->id;
        $previous_image = homepage_section::where('id',$id)->first()->image;
        if($previous_image)
        {


                if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }



        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/homepage_section_image') , $image);
        $image = "image/homepage_section_image/" . $image;

        homepage_section::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-homepage_section')->with('success','Image Updated Successfully');

    }

    public function homepage_section_content_delete(Request $request)
    {
        $id = $request->id;
        homepage_section::where('id', $id)->update(['delete_status'=>1]);

    }

    public function update_homepage_content_order(Request $request)
    {
        $position = $request->position;
        //file_put_contents('test.txt',$position[0]);
        $homepage_section = homepage_section::get();
        for($i = 0 ;$i<sizeof($position);$i++)
        {
            homepage_section::where('id',$position[$i])->update(['section_order'=>$i+1]);
        }

    }
    //homepage section end

    //banner start
    public function show_all_banner()
    {
        $datas = banner::orderBy('order')->where('delete_status',0)->get();
        $i=1;

        foreach($datas as $data)
        {
            $data['sl_no'] = $i++;
        }
        $permission = $this->permission();
        return view('admin.banner.all',['datas'=>$datas,'role_permission'=>$permission]);


    }

    public function add_banner_ui()
    {
        return view('admin.banner.add');
    }
    public function add_banner(Request $request)
    {
        $rules = [

            'image'=>'required'


        ];
    $customMessages = [

        'image.required'=>'Banner image field is required'


    ];

    $validator = Validator::make( $request->all(), $rules, $customMessages );


    if($validator->fails())
    {
        return redirect()->back()->withInput()->with('errors',collect($validator->errors()->all()));
    }

        $banner = banner::get();
        if(sizeof($banner)>0)
        {
            $last_insert_id = banner::orderBy('order','DESC')->first()->order;

        }
        else
        {
            $last_insert_id = 0;
        }

        $image = time() . '.' . request()->image->getClientOriginalExtension();

    $request
        ->image
        ->move(public_path('../image/banner_image') , $image);
    $image = "image/banner_image/" . $image;
    banner::create(['image'=>$image,'order'=>$last_insert_id+1]);

       //file_put_contents('test.txt',$request->name." ".$request->image);


        return redirect()->route('show-all-banner')->with('success','banner Added Successfully');


    }

    public function banner_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =banner::where('id', $id)->first()->status;
        if ($status == 1)
        {
            banner::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            banner::where('id', $id)->update(['status' => 1]);
        }
    }

    public function edit_banner_image_ui(Request $request)
    {
        $id = $request->id;
        return view('admin.banner.edit_image',compact('id'));

    }

    public function update_banner_image(Request $request)
    {
        $id = $request->id;
        $previous_image = banner::where('id',$id)->first()->image;
        if($previous_image)
        {


                if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }



        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/banner_image') , $image);
        $image = "image/banner_image/" . $image;

        banner::where('id',$id)->update(['image'=>$image]);
        return redirect()->route('show-all-banner')->with('success','Image Updated Successfully');

    }

    public function banner_content_delete(Request $request)
    {
        $id = $request->id;
        banner::where('id', $id)->update(['delete_status'=>1]);

    }

    public function update_banner_order(Request $request)
    {
        $position = $request->position;
        //file_put_contents('test.txt',$position[0]);
        $banner = banner::get();
        for($i = 0 ;$i<sizeof($position);$i++)
        {
            banner::where('id',$position[$i])->update(['order'=>$i+1]);
        }

    }
    //banner end

    //product add to section start

    public function product_add_to_section_ui($id)
    {
        $product_list = product::where('delete_status',0)->get();
        foreach($product_list as $product)
        {
            $avail = homepage_product_list::where('homepage_section_id',$id)->where('product_list',$product->id)->first();
            if($avail)
            {
                $product['avail'] = 1;
            }
            else
            {
                $product['avail'] = 0;
            }
        }
        return view('admin.homepage_section.product_section_all',compact('product_list','id'));
    }

    public function product_add_to_package_ui($id)
    {
        $product_list = product::where('delete_status',0)->get();
        foreach($product_list as $product)
        {
            $avail = package_product::where('package_id',$id)->where('product_id',$product->id)->first();
            if($avail)
            {
                $product['avail'] = 1;
            }
            else
            {
                $product['avail'] = 0;
            }
        }
        return view('admin.package.package_product',compact('product_list','id'));
    }

    public function edit_package_content_ui(Request $request)
    {
        $id = $request->id;
        $data = package::where('id',$id)->first();
        return view('admin.package.edit_content',['data'=>$data]);

    }
    public function package_content_delete(Request $request)
    {
        $id = $request->id;
        package::where('id', $id)->update(['delete_status'=>1]);

    }

    public function edit_package_image_ui($id)
    {
        return view('admin.package.edit_image',['id'=>$id]);
    }

    public function update_package_image(Request $request)
    {
        $id = $request->id;
        $previous_image = package::where('id',$id)->first()->package_image;
        if($previous_image)
        {

           if(file_exists($previous_image))
           {
                unlink( base_path($previous_image));
           }


        }
        $image = time() . '.' . request()->image->getClientOriginalExtension();

        $request->image->move(public_path('../image/package_image') , $image);
        $image = "image/package_image/" . $image;

        package::where('id',$id)->update(['package_image'=>$image]);
        return redirect()->route('show_all_package')->with('success','Image Updated Successfully');
    }

    public function update_package_content(Request $request)
    {
        $id = $request->id;
        //file_put_contents('test.txt',$id);
        $package = package::where('id',$id)->first();
        $total_price = $package->total_price;
        $discount_price = $total_price-floor(($total_price*$request->discount_percentage)/100);
        package::where('id', $id)->update(['package_name' => $request->name,'discount_percentage'=>$request->discount_percentage,'discount_price'=>$discount_price]);

        return redirect()
            ->route('show_all_package')
            ->with('success', "Data Updated Successfully");
    }

    public function package_active_status_update(Request $request)
    {
        $id = $request->id;
        $status =package::where('id', $id)->first()->status;
        if ($status == 1)
        {
            package::where('id', $id)->update(['status' => 0]);
        }
        else
        {
            package::where('id', $id)->update(['status' => 1]);
        }
    }


    public function add_product_to_section(Request $request)
    {
            $product_id = $request->product_id;
            $discount_percentage = $request->discount_percentage;
            $homepage_section_id = $request->homepage_section_id;
            homepage_product_list::create(['homepage_section_id'=>$homepage_section_id,'product_list'=>$product_id,'discount_percentage'=>$discount_percentage]);

    }

    public function add_product_to_package(Request $request)
    {
        $product_id = $request->product_id;
        $unit_quantity = $request->unit_quantity;
        $package_id = $request->package_id;
        $package = package::where('id',$package_id)->first();
        $total_price = $package->total_price;
        $discount = $package->discount_percentage;
        $product_price = product::where('id',$product_id)->first()->price;
        $total_price = $total_price+$product_price*$unit_quantity;
        $discount_price = $total_price- floor(($total_price *  $discount)/100);

        package::where('id',$package_id)->update(['total_price'=>$total_price,'discount_price'=>$discount_price]);


        package_product::create(['package_id'=>$package_id,'product_id'=>$product_id,'unit_quantity'=>$unit_quantity]);
    }


    public function update_product_to_section(Request $request)
    {
            $product_id = $request->product_id;
            $product_percentage = $request->product_percentage;

            homepage_product_list::where('id',$product_id)->update(['discount_percentage'=>$product_percentage]);

            //homepage_product_list::update(['homepage_section_id'=>$homepage_section_id,'product_list'=>$product_id,'discount_percentage'=>$discount_percentage]);

    }

    public function update_product_to_package(Request $request)
    {
            $product_id = $request->product_id;
            $unit_quantity = $request->unit_quantity;
            $package_product= package_product::where('id',$product_id)->first();
            $existing_quantity = $package_product->unit_quantity;

            $package = package::where('id',$package_product->package_id)->first();
            $total_price = $package->total_price;
            //file_put_contents('test.txt',$total_price);
        $discount = $package->discount_percentage;

        $total_price = $total_price-($package_product->product->price*$existing_quantity);
        $total_price = $total_price+($package_product->product->price*$unit_quantity);

        $discount_price = $total_price - floor(($total_price *  $discount)/100);

        package::where('id',$package_product->package_id)->update(['total_price'=>$total_price,'discount_price'=>$discount_price]);

            package_product::where('id',$product_id)->update(['unit_quantity'=>$unit_quantity]);

            //homepage_product_list::update(['homepage_section_id'=>$homepage_section_id,'product_list'=>$product_id,'discount_percentage'=>$discount_percentage]);

    }

    public function get_all_package_product($id)
    {
      $product_list =   package_product::where('package_id',$id)->where('delete_status',0)->get();
      //file_put_contents('test.txt',json_encode($product_list));
        $data = '';

        foreach($product_list as $product)
        {

            $after_discount_price = 10;
            $data.='
            <div class="col-xl-3 col-xxl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card">
                    <img class="img" src="../../'.$product->product->thumbnail_image.'" alt=""  height="200px">
                    <div class="card-body">
                        <h4>'.$product->product->name.'</h4>
                        <ul class="list-group mb-3 list-group-flush">

                            <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">Product Price</span>
                               <strong>'.$product->product->price.'</strong></li>

                               <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">Unit Quantity</span>
                               <strong>'.$product->unit_quantity.'</strong></li>


                               <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">Price</span>
                               <strong>'.$product->unit_quantity * $product->product->price.'</strong></li>


                        </ul>
                        <a href="javascript:;" onclick = "edit_quantity_modal('.$product->unit_quantity.','.$product->id.')" class="btn btn-primary">Edit Product Quantity</a>
                        <a  href="javascript:void(0);" class="btn btn-lg btn-danger" onclick="delete_product_from_package('.$product->id.')"><i class="la la-trash-o"></i></a>
                    </div>
                </div>
            </div>
    ';
        }

        $all_product = '<label>Select Product</label>
        <select class="form-control select2" id="product_id" name="product_id">
            <option>Select Product</option>';
        $product_list = product::where('delete_status',0)->get();
        foreach($product_list as $product)
        {
            $avail = package_product::where('package_id',$id)->where('product_id',$product->id)->where('delete_status',0)->first();
            if($avail)
            {
                $product['avail'] = 1;
            }
            else
            {
                $product['avail'] = 0;
            }
        }
        foreach($product_list as $product)
        {
            if($product->avail == 0){
                 $all_product.='


                <option value="'.$product->id.'">'. $product->name.'</option>

       ';
            }
            else{
        $all_product.='


        <option value="'.$product->id .'" disabled>'.$product->name.'</option>

       ';
        }
    }
        $all_product.=' </select>

        <script src="../../assets/admin/js/select2.full.js"></script>
        <script src="../../assets/admin/js/advanced-form-element.js"></script>

        ';


        echo json_encode(['section_product'=>$data,'all_product'=>$all_product]);

    }



    public function get_all_homepage_section_product($id)
    {
      $product_list =   homepage_product_list::where('homepage_section_id',$id)->where('delete_status',0)->get();
        $data = '';
        foreach($product_list as $product)
        {
            $after_discount_price = $product->product->price- floor(($product->product->price * $product->discount_percentage)/100);
            $data.='
            <div class="col-xl-3 col-xxl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card">
                    <img class="img" src="../../'.$product->product->thumbnail_image.'" alt=""  height="200px">
                    <div class="card-body">
                        <h4>'.$product->product->name.'</h4>
                        <ul class="list-group mb-3 list-group-flush">
                            <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">Product Price</span>
                               <strong>'.$product->product->price.'</strong></li>

                               <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">Discount Percentage</span>
                               <strong>'.$product->discount_percentage.'</strong></li>

                               <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0 text-muted">After Discount Price</span>
                               <strong>'.$after_discount_price.'</strong></li>

                        </ul>
                        <a href="javascript:;" onclick = "edit_discount_price_modal('.$product->discount_percentage.','.$product->id.')" class="btn btn-primary">Edit Discount Percentage</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="delete_product_from_section('.$product->id.')"><i class="la la-trash-o"></i></a>
                    </div>
                </div>
            </div>
    ';
        }
        $all_product = '<label>Select Product</label>
        <select class="form-control select2" id="product_id" name="product_id">
            <option>Select Product</option>';
        $product_list = product::get();
        foreach($product_list as $product)
        {
            $avail = homepage_product_list::where('homepage_section_id',$id)->where('product_list',$product->id)->first();
            if($avail)
            {
                $product['avail'] = 1;
            }
            else
            {
                $product['avail'] = 0;
            }
        }
        foreach($product_list as $product)
        {
            if($product->avail == 0){
                 $all_product.='


                <option value="'.$product->id.'">'. $product->name.'</option>

       ';
            }
            else{
        $all_product.='


        <option value="'.$product->id .'" disabled>'.$product->name.'</option>

       ';
        }
    }
        $all_product.=' </select>

        <script src="../../assets/admin/js/select2.full.js"></script>
        <script src="../../assets/admin/js/advanced-form-element.js"></script>

        ';


        echo json_encode(['section_product'=>$data,'all_product'=>$all_product]);

    }

    public function delete_product_from_section(Request $request)
    {
        $id = $request->id;
        homepage_product_list::where('id', $id)->update(['delete_status'=>1]);

    }

    public function get_all_product_list($id)
    {
        $product_list = product::where('delete_status',0)->get();
        foreach($product_list as $product)
        {
            $avail = homepage_product_list::where('homepage_section_id',$id)->where('product_list',$product->id)->first();
            if($avail)
            {
                $product['avail'] = 1;
            }
            else
            {
                $product['avail'] = 0;
            }
        }


    }

    //product add to section end

    //order start
    public function new_order()
    {
        $order = order::where('status','!=','delivered')->where('status','!=','canceled')->orderBy(DB::raw('case when status= "pending" then 1 when status= "picked" then 2 end'))->get();
        $i=1;

         foreach($order as $data)
        {


            $order_no = $data->order_no;
            $order_detail = order_details::where('order_no',$order_no)->get();

            $sub_total = 0;
            for($j=0;$j<sizeof($order_detail);$j++)
            {
                $sub_total+=$order_detail[$j]->price*$order_detail[$j]->count;

                // array_push($order_details,$order_)
            }
          //  $order_date =  date("d-m-Y h:i:s", strtotime($order[$i]->created_at));
          // array_push($order_list,['order_no'=>$order[$i]->order_no,'order_date'=>$order_date,'status'=>$order[$i]->status,'delivery_address'=>$order[$i]->address->address,'subtotal'=>$sub_total,'product'=>$order_details]);



            $data['sl_no'] = $i++;
            $data['total_bill'] = $sub_total;
        }

        return view('admin.order.new',['datas'=>$order]);
    }

       public function all_order()
    {
        $order = order::where('status','!=','pending')->where('status','!=','picked')->where('delete_status',0)->orderBy(DB::raw('case when status= "delivered" then 1 when status= "canceled" then 2 end'))->get();
        $i=1;

         foreach($order as $data)
        {


            $order_no = $data->order_no;
            $order_detail = order_details::where('order_no',$order_no)->get();

            $sub_total = 0;
            for($j=0;$j<sizeof($order_detail);$j++)
            {
                $sub_total+=$order_detail[$j]->price*$order_detail[$j]->count;

                // array_push($order_details,$order_)
            }
          //  $order_date =  date("d-m-Y h:i:s", strtotime($order[$i]->created_at));
          // array_push($order_list,['order_no'=>$order[$i]->order_no,'order_date'=>$order_date,'status'=>$order[$i]->status,'delivery_address'=>$order[$i]->address->address,'subtotal'=>$sub_total,'product'=>$order_details]);



            $data['sl_no'] = $i++;
            $data['total_bill'] = $sub_total;
        }

        return view('admin.order.all',['datas'=>$order]);
    }

    public function show_order_product(Request $request)
    {
        $order_no = $request->order_no;
        $order = order::where('order_no',$order_no)->first();
        $order_details = order_details::where('order_no',$order_no)->get();
        $i=1;
          foreach($order_details as $data)
        {
            $data['sl_no'] = $i++;
        }
       // file_put_contents('test.txt',$order_no." ".json_encode($order_details));
        return view('admin.order.show_product',['datas'=>$order_details,'order'=>$order]);
    }
    public function update_order_status(Request $request)
    {
        $order_id = $request->order_id;
        $order_status = $request->order_status;
        file_put_contents('test.txt',$order_id." ".$order_status);
        order::where('id',$order_id)->update(['status'=>$order_status]);
        return redirect()
        ->route('new-order')
        ->with('success', "Data Updated Successfully");
    }

    public function change_order_status(Request $request)
    {
        $order_id = $request->id;

        //$order = order::where('id',$order_id)->get();

       // file_put_contents('test.txt',$order_no." ".json_encode($order_details));
        return view('admin.order.update_order_status',['order_id'=>$order_id]);
    }


    //order end
    //company info start
        public function show_all_company_info()
        {
                $data = company_info::first();

                return view('admin.company_info.all',compact('data'));
        }
        public function add_company_info(Request $request)
        {
            company_info::where('id',2)->update($request->except('_token'));
            return redirect()->route('show-all-company-info')->with('success','Information Updated Successfully');
        }
    //company info end

    //terms and condtion start

    public function show_all_terms()
    {
            $data = terms_and_condition::first();
            return view('admin.terms.all',compact('data'));
    }
    public function add_terms(Request $request)
    {
        terms_and_condition::where('id',1)->update($request->except('_token'));
        return redirect()->route('show-all-terms')->with('success','Information Updated Successfully');
    }


    //terms and condition end

    //delivery charge start
    public function show_all_delivery_charge()
    {
            $data = delivery_charge::first();
            return view('admin.delivery_charge.all',compact('data'));
    }
    public function add_delivery_charge(Request $request)
    {
        //delivery_charge::create($request->except('_token'));
       delivery_charge::where('id',1)->update($request->except('_token'));
        return redirect()->route('show-all-delivery-charge')->with('success','Information Updated Successfully');
    }

    //delivery charge end

    public function test()
    {
        $a = [
            "destinationResponses" => [
                  [
                     "timeStamp" => "20210422021739",
                     "address" => "tel:8801845318609",
                     "messageId" => "12104220217382905",
                     "statusDetail" => "Request was successfully processed.",
                     "statusCode" => "S1000"
                  ]
               ],
            "requestId" => "12104220217382905",
            "statusDetail" => "Request was successfully processed.",
            "version" => "1.0",
            "statusCode" => "S1000"
         ];

         $a = (object)$a;
         $b = json_decode(json_encode($a->destinationResponses));
         $b = $b[0];
         return $b->timeStamp;
        // return $b[0]->timeStamp;
        //return $a->destinationResponses;



    }


    public function update_unit()
    {
        $product = product::get();
        foreach($product as $data)
        {
            $unit_type = product_unit::where('product_id',$data->id)->first()->unit_type;
            $unit_quantity = product_unit::where('product_id',$data->id)->first()->unit_quantity;
            product::where('id',$data->id)->update(['unit_type'=>$unit_type,'unit_quantity'=>$unit_quantity]);
        }
    }

    public function update_stock()
    {
        $product = product::get();
        foreach($product as $data)
        {
            $stock = product_stock::where('product_id',$data->id)->first()->stock_amount;
            product::where('id',$data->id)->update(['stock'=>$stock]);
        }
    }




}
