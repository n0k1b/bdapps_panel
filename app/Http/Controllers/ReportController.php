<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use App\Models\sub_category;
use App\Models\product;
use App\Models\area;
use App\Models\order;
use App\Models\user;
use DB;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Validator;
use Session;


class ReportController extends Controller
{
    //

    public function permission ()
    {

        $user_id = Auth::guard('admin')->user()->id;
        $user_role = Auth::guard('admin')->user()->role;
        $role_id = DB::table('roles')->where('name',$user_role)->first()->id;
        $role_permission = DB::table('role_permisiions')->where('role_id',$role_id)->pluck('content_name')->toArray();
        return $role_permission;

    }

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

    public function show_user_report(Request $request)
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
            $data = user::whereDate('created_at','>=',$from_date)->whereDate('created_at','<=',$to_date)->whereNull('password')->get();
            //file_put_contents('test.txt',"hello2");
            $i=1;
                foreach($data as $datas)
                {

                    $datas['sl_no'] = $i++;
                    $datas['date'] = date("d-m-Y H:i:s", strtotime($datas->created_at));


                }

            return Datatables::of($data)
                    ->addIndexColumn()

                    ->make(true);
        }




        return view('admin.report.user_report');






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




        return view('admin.report.show');

       // file_put_contents('test.txt',$from_date." ".$to_date);
    }
    public function report_view($type)
    {
        return view('admin.report.date_view',['type'=>$type]);
    }




}
