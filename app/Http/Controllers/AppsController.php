<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppList;
use App\Models\Subscriber;
use Carbon\Carbon;
use DataTables;
use App\Classes\OtpSender;
use App\Classes\VerifyOtp;
use App\Classes\SubscriptionReceiver;
use App\Classes\Subscription;
use App\Classes\SubscriptionException;
use App\Classes\UssdReceiver;
use App\Classes\UssdSender;
use App\Classes\UssdException;
use App\Classes\Logger;
use App\Models\content;
use App\Classes\SMSSender;
use Log;
use Auth;
class AppsController extends Controller
{
    //
    
    
        public function ussd2() {
        //return $a;
         $ussdserverurl = 'https://developer.bdapps.com/ussd/send';
        try {
            $receiver = new UssdReceiver();
            $content = $receiver->getMessage(); // get the message content
            $address = $receiver->getAddress(); // get the ussdSender's address
            $requestId = $receiver->getRequestID(); // get the request ID
            $applicationId = $this->app_id; // get application ID
            //file_put_contents('test.txt',$applicationId);
            $app_password = $this->app_password;
            $subscription = new Subscription('https://developer.bdapps.com/subscription/send',$applicationId, $app_password);
            $ussdSender = new UssdSender($ussdserverurl, $applicationId, $app_password);
            $sender = new SMSSender("https://developer.bdapps.com/sms/send", $applicationId,$app_password);
           
           
            $encoding = $receiver->getEncoding(); // get the encoding value
            $version = $receiver->getVersion(); // get the version
            $sessionId = $receiver->getSessionId(); // get the session ID;
            $ussdOperation = $receiver->getUssdOperation(); // get the ussd operation
            //file_put_contents('status.txt',$address);
            $responseMsg = " Thanks for subscription. Please reply with 1 to activate";
            if ($ussdOperation == "mo-init") {
                try {
                    $ussdSender->ussd($sessionId, $responseMsg, $address, 'mt-fin');
                    $subscription->subscribe($address);
                  // file_put_contents('test.txt',json_encode($t));
                     $myfile = fopen("userMaskNumbers.txt", "a+") or die("Unable to open file!");
                fwrite($myfile,$address."\n");
                User::updateOrCreate([
                    'mask'=>$address
                    ]);

                   
                }
                catch(Exception $e) {
                }
            }
        }
        catch(Exception $e) {
          //file_put_contents('USSDERROR.txt', $e);
        }
    }
    
    public function ussd($app_name)
    {   
       
        
        $apps = AppList::where('app_name',$app_name)->first();
        $applicationId = $apps->app_id;
        $app_password = $apps->app_password;
        $id = $apps->id;
        //file_put_contents('test5.txt',$applicationId.' '.$app_password);
        $production = true;
        if ($production == false) {
            $ussdserverurl = 'http://localhost:7000/ussd/send';
        } else {
            $ussdserverurl = 'https://developer.bdapps.com/ussd/send';
        }
         try {
    
           
            $receiver = new UssdReceiver();
            $content = $receiver->getMessage(); // get the message content
            $address = $receiver->getAddress(); // get the ussdSender's address
            $requestId = $receiver->getRequestID(); // get the request ID
            //$applicationId = $this->app_id; // get application ID
            //file_put_contents('test.txt',$applicationId);
           //$app_password = $this->app_password;
           
            $ussdSender = new UssdSender($ussdserverurl, $applicationId, $app_password);
            $sender = new SMSSender("https://developer.bdapps.com/sms/send", $applicationId,$app_password);
           $subscription = new Subscription('https://developer.bdapps.com/subscription/send',$applicationId, $app_password);
             //file_put_contents('test6.txt',$ussdSender.' '.$sender.' '.$subscription);
            $encoding = $receiver->getEncoding(); // get the encoding value
            $version = $receiver->getVersion(); // get the version
            $sessionId = $receiver->getSessionId(); // get the session ID;
            $ussdOperation = $receiver->getUssdOperation(); // get the ussd operation
            //file_put_contents('status.txt',$address);
            $responseMsg = " Thanks for subscription. Please reply with 1 to activate";
            if ($ussdOperation == "mo-init") {
                try {
                    $ussdSender->ussd($sessionId, $responseMsg, $address, 'mt-fin');
                    $subscription->subscribe($address);
                    subscriber::create([
                        'app_id'=>$id,
                        'mask_no'=>$address,
                        'subscription_status'=>'Try'
                        ]);
                //   // file_put_contents('test.txt',json_encode($t));
                //      $myfile = fopen("userMaskNumbers.txt", "a+") or die("Unable to open file!");
                // fwrite($myfile,$address."\n");
                // User::updateOrCreate([
                //     'mask'=>$address
                //     ]);

                   
                }
                catch(Exception $e) {
                     //file_put_contents('test.txt',$e);
                }
            }
        }
        catch(Exception $e) {
            //file_put_contents('test.txt',$e);
        }
    }

    public function show_all_apps(Request $request)
    {

        if ($request->ajax()) {
            $data = AppList::get();
            $i=1;
                foreach($data as $datas)
                {
                   
                    $datas['sl_no'] = $i++;
                    $datas['ussd_url'] = 'https://bdappspro.com/api/ussd/'.$datas->app_name;
                    $datas['sms_url'] = 'https://bdappspro.com/api/sms/'.$datas->app_name;
                    $datas['subscription_notification_url'] = 'https://bdappspro.com/api/subscriptionNotification/'.$datas->app_name;
                    $datas['send_otp_endpoint'] = 'https://bdappspro.com/api/sendBdappsOtp//'.$datas->app_name;
                    $datas['verify_otp_endpoint'] = 'https://bdappspro.com/api/verifyBdappsOtp/'.$datas->app_name;
                    
                    

                }

            return Datatables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('action', function($data){

                        
                        $button = '';
                        $button .= ' <a href="edit_apps_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="app_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })

                
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.apps.all_apps');

    }

    public function add_apps_ui()
    {
        return view('admin.apps.add_apps');
    }

    public function add_content_ui()
    {
        date_default_timezone_set('Asia/Dhaka');
          $content_types = AppList::select('app_type')->distinct()->get();
        //$content_types = array_unique($content_types);
        // file_put_contents('test.txt',json_encode($content_types));
        $cur_date = date('d-m-Y');
        $app_id = 3;
        $content = content::where('app_id','=',$app_id)->orderBy('date',"DESC")->get();
        if($content->isEmpty())
        {
        date_default_timezone_set('Asia/Dhaka');
        $date = date('d-m-Y');
          
        }
        else{
            $date = $content[0]->date;
            if(strtotime($date) < strtotime($cur_date))
            {
                $date = $cur_date;
            }
            else{
            $date = date('d-m-Y', strtotime($date. '+ 1 days'));
            }
        }
       // file_put_contents('test.txt',$content);
        //return view('dashboard.add_content',);
        return view('admin.content.add',['date'=>$date,'content_types'=>$content_types]);
    }

    public function add_apps(Request $request)
    {

       
        AppList::create($request->except('_token'));
        return redirect()->route('show-all-apps')->with('success','Apps Added Successfully');


    }

    
    public function edit_apps_content_ui(Request $request)
    {
        $id = $request->id;
        $data = AppList::where('id',$id)->first();
        return view('admin.apps.edit_apps_content',['data'=>$data]);

    }
    public function edit_apps_image_ui(Request $request)
    {
        $id = $request->id;
        $data = AppList::where('id',$id)->first();
        return view('admin.apps.edit_apps_image',['data'=>$data]);

    }
    public function update_apps_content(Request $request)
    {
        $id = $request->id;

        AppList::where('id', $id)->update($request->except('_token'));

        return redirect()
            ->route('show-all-apps')
            ->with('success', "Data Updated Successfully");
    }
    public function report()
    {
        $apps = AppList::get();
        return view('admin.report.subscription_report',compact('apps'));
    }
    public function show_subscription_report(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->toDateTimeString();
        $end_date =  Carbon::parse($request->end_date)->addDays(1)->toDateTimeString();
        $app_id = $request->app_id;
        $type = $request->type;
        if($app_id)
        {
            if($app_id=='all')
            {
                if($type=='all')
                $data = Subscriber::whereBetween('created_at', [$start_date, $end_date])->latest()->get();
                else
                $data = Subscriber::where('subscription_status','LIKE',$type.'%')->whereBetween('created_at', [$start_date, $end_date])->latest()->get();
            }
            else{
                $data = Subscriber::where('subscription_status','LIKE',$type.'%')->where('app_id',$app_id)->whereBetween('created_at', [$start_date, $end_date])->latest()->get();
                
            }
        }
        else
        {
            $data = Subscriber::whereBetween('created_at', [$start_date, $end_date])->latest()->get();
        }
        

       // file_put_contents('test.txt',$start_date.' '.$end_date.' '.$app_id);
       // $data = Subscriber::get();
        $i=1;
        $subscriber = $data->where('subscription_status','Subscriber')->count();
        $unsubscriber = $data->where('subscription_status','Unsubscriber')->count();
        $pending_charge = $data->where('subscription_status','Pending Charge')->count();
      //  file_put_contents('test.txt',json_encode($subscriber));
        foreach($data as $datas)
        {
            //$checked = $datas->status=='1'?'checked':'';
            $datas['sl_no'] = $i++;
            $datas['subscriber'] = $subscriber ;
            $datas['unsubscriber'] = $unsubscriber;
            $datas['pending_charge'] = $pending_charge ;
           

        }

        return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function($data){
                        return Carbon::parse($data->created_at)->format('d-m-Y H:i:s');
         
                     })
                     ->addColumn('app_name', function($data){
                        return $data->apps->app_name;
         
                     })

                    ->make(true);
    }
    

    public function apps_content_delete(Request $request)
    {
        $id = $request->id;
        

       // file_put_contents('test.txt',"hello ".$id);



    }
    public function content()
    {
        return view('admin.content.index'); 
    }

    public function show_all_content(Request $request)
    {

        if ($request->ajax()) {
            $data = content::get();
            $i=1;
                foreach($data as $datas)
                {
                   
                    $datas['sl_no'] = $i++;
                   

                }

            return Datatables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('action', function($data){

                        
                        $button = '';
                        $button .= ' <a href="edit_content/'.$data->id.'" class="btn btn-sm btn-primary"><i class="la la-pencil"></i></a>';
                        $button .= ' <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="content_delete('.$data->id.')"><i class="la la-trash-o"></i></a>';
                        return $button;
                 })

                
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.content.index');

    }
    public function update_data(Request $request)
    {
        $date = $request->date;
        $count = $request->count;
        
        $date = date('d-m-Y', strtotime($date. '+'.$count.'days'));
        
        $data='<tr>
          	<td width = "70%"><textarea  class="form-control cont" id="content" name="contn" rows="6" cols="8" placeholder="Enter Content"></textarea></td>
									
										<td width = "20%"><input type="text" value="'.$date.'" name="date[]" class="form-control" disabled></td>
										<td><a href="javascript:void(0);"  class="remove"><i class="la la-times-circle" style="font-size: 33px;
                                        color: red;"></i></a></td>
        
        </tr>';
        return $data;
    }
    public function save_content(Request $request)
    {
        $content = $request->content;
        $content = explode(",",$content);
        $date = $request->date;
        $date = explode(",",$date);
        $content_type = $request->content_type;
       // $app_id = AppList::select('id')->get();

        for($i=0;$i<sizeof($content);$i++)
        
        {   
            if($content[$i])
             {
                
            content::create(['content'=>$content[$i],'date'=>$date[$i],'content_type'=>$content_type]);
             }
        }
    }

    public function select_app()
    {
        $datas = AppList::select('app_type')->distinct()->get();
        //file_put_contents('test.txt',json_encode($datas));
        return view('admin.content.select_app',compact('datas'));
    }

    public function select_app_regular_content()
    {
        $datas = AppList::get();
        //file_put_contents('test.txt',json_encode($datas));
        return view('admin.content.select_app_regular_content',compact('datas'));
    }
    public function app_type_submit(Request $request)
    {   
        $app_type = $request->app_type;
        //file_put_contents('test.txt',$app_type);
        date_default_timezone_set('Asia/Dhaka');
         // $content_types = AppList::select('app_type')->distinct()->get();
        //$content_types = array_unique($content_types);
        // file_put_contents('test.txt',json_encode($content_types));
        $cur_date = date('d-m-Y');
        $app_id = 3;
        $content = content::where('content_type','=',$app_type)->orderBy('id','DESC')->first();
        
        if($content)
        {
            $date = $content->date;
            if(strtotime($date) < strtotime($cur_date))
            {
                $date = $cur_date;
            }
            else{
            $date = date('d-m-Y', strtotime($date. '+ 1 days'));
            }

          
        }
        else{
          
        $date = date('d-m-Y');
        }
        return view('admin.content.add',compact('app_type','date'));
    }

    public function app_type_submit_regular_content(Request $request)
    {   
        $app_id = $request->app_id;
     
        
        return view('admin.content.send_regular_content',compact('app_id'));
    }

    public function save_content_regular(Request $request)
    {
        $app_id = $request->app_id;
        $content = $request->content;
        $apps = AppList::where('id',$app_id)->first();
        $AppId = $apps->app_id;
        $AppPassword = $apps->app_password;
        $server = 'https://developer.bdapps.com/sms/send';
        $sender = new SMSSender($server,$AppId,$AppPassword);
        $sender->setencoding('8');
        $x = $sender->broadcast($content);
        
        //file_put_contents('test.txt',$app_id.' '.$content);
    }
    public function edit_content(Request $request)
    {
        $id = $request->id;
        $data = content::where('id',$id)->first();
        return view('admin.content.edit_content',['data'=>$data]);
    }
    
    public function update_content(Request $request)
    {
        $id = $request->id;
        content::where('id',$id)->update(['content'=>$request->content]);
        return redirect()->route('show-all-content')->with('success','Content Added Successfully');
    }

    public function content_delete(Request $request)
    {
        $id = $request->id;
        content::where('id',$id)->delete();
    }
    public function app_delete(Request $request)
    {
        $id = $request->id;
        Subscriber::where('app_id',$id)->delete();
        AppList::where('id',$id)->delete();
       
    }
    public function index()
    {
        if(auth()->check())
        {
            return view('admin.dashboard.index');
        }
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // file_put_contents('test2.txt',Auth::guard('admin')->user()->name);
             return redirect('/');
 
          }
          else
          {
             return back()->with('error','Email and Password not match');
          }
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/');
    }
    
    
    public function verify_bdapps_otp(Request $request)
    {
        $app_name = $request->app_name;
        $apps = AppList::where('app_name',$app_name)->first();

        $otp = $request->otp;
        $reference_no = $request->reference_no;
        $verify_otp = new VerifyOtp($apps->app_id,$apps->app_password);
        $a = $verify_otp->verify_otp($otp,$reference_no);
        subscriber::create([
                        'app_id'=>$apps->id,
                        'mask_no'=>$a->subscriberId,
                        'subscription_status'=>$a->subscriptionStatus
                        ]);
        // $mask = $a->subscriberId;
        // $subscription_status = $a->subscriptionStatus;
        return json_encode($a);
        //$subscription_status = $request->subscriptionStatus;
        



    }
    public function send_bdapps_otp(Request $request)
    {
        $app_name = $request->app_name;
         $apps = AppList::where('app_name',$app_name)->first();
        //return $id;
         $address = $request->mobile;
         //return $apps->app_id.' '.$apps->app_password;
         $otp_sender = new OtpSender($apps->app_id,$apps->app_password);
         $a = $otp_sender->send_otp($address);
     
       // return $reference_no;
        return json_encode($a);
    }
    
        public function subscriptionNotification(Request $request)
    {
        
       //Log::info('hello');
        $receiver 	= new SubscriptionReceiver();
        $status = $receiver->getStatus();
        $application_id = $receiver->getApplicationId();
        $address = 'tel:'.$receiver->getsubscriberId();
        $timestamp = $receiver->getTimestamp();
        $app_id = AppList::where('app_id',$application_id)->first()->id;
        subscriber::where('app_id',$app_id)->where('mask_no',$address)->update(['subscription_status'=>$status]);
        
        Log::info($address);
       
 
    }


    

}
