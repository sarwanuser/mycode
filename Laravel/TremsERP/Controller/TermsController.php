<?php

namespace App\Http\Controllers; 
use App\Http\Controllers\Controller;
use DB;
use App\GtEHead;
use App\GTEdetail;
use App\VendorMaster;
use App\Models\UserMaster;
use App\Models\UserUnit;
use App\Models\Security\FileMaster;
use App\Models\GitLog;
use App\Models\GitDeveloper;
use App\Models\Unit;
use App\Models\SEC\UserSession;
use App\FinYear;
use App\TermsModule;
use App\Models\Security\UserPriv;
use App\Models\erpmenumodel;
use Illuminate\Http\Request;
use App\Models\CompanyMaster\CompMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use Session;
use Validator;
use Cookie;
use Illuminate\Http\Response;
use Dirape\Token\Token;
use Config;
use Illuminate\Support\Facades\Hash; 


class TermsController extends Controller{

    /** 
     * Display a login page.
     *
     * @return \Illuminate\Http\Response
     */    
    public function login(){
	   
		$password = request('password'); // get the value of password field
		$hashed = Hash::make('123');
		$company_url = 'http://192.168.1.16:98';//url()->current();
		$company = CompMaster::where('erp_url', $company_url)->first();
        $units = Unit::select('code','name')->where('comp_code', $company->code)->get();
		$finyears = FinYear::select('fin_year_description','fin_year_code','default_fin_year')->orderBy('fr_dt', 'DESC')->get();
        return view('login1', compact('units','finyears','company'));  
    }

    /**
	 * This function use for admin login action
	 *
	 * @return admin login page
	 */
	public function loginAction(Request $request){
		$validator = Validator::make($request->all(), [
			'user' => 'required',
			'password' => 'required',
			'unit' => 'required',
		]);
		if ($validator->fails()) { 
			$error_msg = $validator->errors()->all();
			return back()->with('flash_error',$error_msg[0])->withInput();          
        }  

		try{
			
			$user = UserMaster::where('user_name', $request->user)->where('validity', 'Y')
				->where(function ($query) {
					$query->where(function($query){
						$query->orwhereNull('valid_from')
						  ->orwhereNull('valid_to');
					})->orWhere(function($query){
						$query->whereDate('valid_from', '<=', date("Y-m-d"))
						  ->whereDate('valid_to', '>=', date("Y-m-d"));
					});					
			})
			->first();
			
			if($user != ""){
				$user_sess = UserSession::where('user_id',$user->user_id)->where('machine',$request->ip())->where('logout_time',null)->first();
				if($user_sess != "")
				{
					//return back()->with('flash_error','Already Login , Please try again!')->withInput();
				}

				
				if(decrypt($user->user_pass) == $request->password){
					$check_unit = UserUnit::where('unit_cd', $request->unit)->where('user_master_id', $user->user_master_id)->get();
					$unit = Unit::select('code','name','unit_curr')->where('code', $request->unit)->first();
					$unitnamecode = $unit->name.'('.$unit->code.')';
					$log_token = rand();
					
					$log_data = array("name" => $user->user_name, "id" => $user->user_id, "emp_id" => $user->emp_id, "fin_year" => $request->fin_year, "unitcode" => $request->unit, "company_code" => $request->company_code,"unit" => $unitnamecode, "unitname" => $unit->name, "unit_curr" => $unit->unit_curr, "logtoken" => time(), "activemodule" => '', 'allowmenus' => '','search' => '', 'smanu' => 'open');

					$token = (new Token())->Unique('user_master', 'token', 60);

					$request->session()->put($token, $log_data);
					
					//$this->manageLoginSession();
					$this->manageLoginSession($token,$log_data);
					return redirect('/selectmodule?_ts='.$token);
				}else{
					return back()->with('flash_error','Invailid Password, Please try again!')->withInput();
				}
			}else{
				return back()->with('flash_error','Invailid User, Please try again!')->withInput();
			}
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		}
	}

	/**
	 * This function use for change the fin year for full ERP
	 *
	 * @return selectmodule page
	 */
	public function changeFinYear(Request $request){
		try{
			$token = @$_REQUEST['_ts'];
			if (session()->exists($token)) {
				session()->forget($token.'.fin_year');
           		$request->session()->put($token.'.fin_year', $request->fin_year);
				return redirect('/selectmodule?_ts='.$token);
			}else{
				return redirect('/');
			}
			
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		}
	}

	/**
	 * This function use for change the unit for full ERP
	 *
	 * @return selectmodule page
	 */
	public function changeUnit(Request $request){
		try{
			$token = @$_REQUEST['_ts'];
			if (session()->exists($token)) {
				session()->forget($token.'.unitcode');
				session()->forget($token.'.unit');
				session()->forget($token.'.unitname');
				
                $unit = Unit::select('code','name','unit_curr')->where('code', $request->unit)->first();
				$unitnamecode = $unit->name.'('.$unit->code.')';
				$request->session()->put($token.'.unitcode', $request->unit);
				$request->session()->put($token.'.unit', $unitnamecode);
				$request->session()->put($token.'.unitname', $unit->name);
				
				return redirect('/selectmodule?_ts='.$token);
			}else{
				return redirect('/');
			}
			
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		}
	}

    /**
	 * This function use for logout admin 
	 *
	 * @return admin login page
	 */
	public function logout(Request $request){
		if($request->flush == 1){
			session()->flush();
			return redirect('/');
		}
		
		$token = $_GET['_ts'];
		$log_data = '';
		//$this->manageLoginSession($token,$log_data);
		session()->forget($token);
		return redirect('/');
	}

    /** 
     * Display a dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(){
		$token = @$_REQUEST['_ts'];
        $user = session()->get($token);
        $empcd = $user['emp_id'];
		if(isset($user['activemodule']) && $user['activemodule'] == 'FIN'){
			return redirect('/fin/fin-dashboard?_ts='.$token);
				
		}elseif(isset($user['activemodule']) && $user['activemodule'] == 'SALE'){
			return redirect('/sale/sale-dashboard?_ts='.$token);
		}else{

		
		$alldata = DB::table('doc_app_view')->select(DB::raw('count(*) as cnt_appr'))->where('emp_cd', '=', $empcd)->get();
		$docrowctr = DB::table('doc_count_app_view')->select(DB::raw('count(*) as cnt_appr'))->where('emp_cd', '=', $empcd)->get();
		$saledata = DB::table('current_sales_view')->select('gr_amount')->distinct()->get();
		$purcdata = DB::select('select round(sum(sd.landed_cost::numeric)/ 100000,2) landed_cost
		from srv_heads sh ,srv_details sd ,po_head ph 
		where sh.srv_no =sd.srv_head_srv_no 
		and sd.po_no =ph.po_no and sd.po_head_amd_no = ph.amd_no::text 
		and sh.srv_dt >=(select parameter_value::date from mtl_parameter mp where key_no=14)');
		$doccountdata = DB::table('doc_count_app_view')->select('doc_type', 'doc_name', 'doc_pending','status','doc_status','doc_app_type')
		                          ->where('emp_cd', '=', $empcd)->distinct()->get();
		$topcustdata = DB::table('topcustomers')->select('cust_code', 'name', 'amount')->orderBy('amount', 'desc')->get();
		$topvenddata = DB::table('topvendors')->select('ven_cd', 'ven_name', 'landed_cost')->orderBy('landed_cost', 'desc')->get();
		$pmtdata = DB::select('select round(SUM((coalesce (apr_month,0)+coalesce (may_month,0)+coalesce (jun_month,0)+coalesce (jul_month,0)+coalesce (aug_month,0)+
		coalesce (sep_month,0)+coalesce (oct_month,0)+coalesce (nov_month,0)+coalesce (dec_month,0)+coalesce (jan_month,0)+
		coalesce (feb_month,0)+coalesce (mar_month,0))/100000),2) tot_due_pmt
		from paymt_adv_monthwise
		where fin_year=(select parameter_value from mtl_parameter mp where key_no=12)');
		$collecdata = DB::select('select round((coalesce (apr_month,0)+coalesce (may_month,0)+coalesce (jun_month,0)+coalesce (jul_month,0)+coalesce (aug_month,0)+
		coalesce (sep_month,0)+coalesce (oct_month,0)+coalesce (nov_month,0)+coalesce (dec_month,0)+coalesce (jan_month,0)+
		coalesce (feb_month,0)+coalesce (mar_month,0))/100000,2) tot_recvdue_pmt
		from collection_adv_monthwise
		where fin_year=(select parameter_value from mtl_parameter mp where key_no=12)');
		$taskctrdata = DB::table('doc_task_pending_view')->select('file_sname', 'unit_cd','doc_ctr')
		->where('user_name', '=', $user)->distinct()->get();

		// Bar chart
		/*$prod_catg_sale_view = DB::table('prod_catg_sale_view')->select('sale_type as label', 'amount as data')->get()->toArray();
		$colors= array("#4fb7fe", "#00cc99", "#0fb0c0", "#EF6F6C", "#ff9933",'');
		$count = 0;
		foreach($prod_catg_sale_view as $data){
			$data->color = $colors[$count];
			$count++;
		}
		$prod_catg_sale_view = json_encode($prod_catg_sale_view);*/

		// Multi line labels
		
		$token = $_GET['_ts'];
		$log_user = session()->get($token);
		
		$bar_chart_line = DB::table('prod_month_sale_view')->select('year_yy','amount')->where('unit_code', $log_user['unitcode'])->get()->toArray();

		$data_multi_chart = array();
		foreach($bar_chart_line as $bar_data){
			$data_multi_chart[] = array($bar_data->year_yy, $bar_data->amount);
		}
		//dd($bar_data);
		$data_multi_chart = json_encode($data_multi_chart);


		// Bar chart
		$bar_chart_datas = DB::table('current_pur_view')->select('monyy','pur_val')->get()->toArray();

		$bar_data = array();
		foreach($bar_chart_datas as $bar_chart_data){
			$bar_data[] = array($bar_chart_data->monyy, $bar_chart_data->pur_val);
		}
		//dd($bar_data);
		$bar_data = json_encode($bar_data);

        return view('dashboard',compact('data_multi_chart','bar_data','alldata','doccountdata','topcustdata','topvenddata','saledata','purcdata','collecdata','pmtdata','docrowctr','taskctrdata'));  
    	}
    }
	
	public function showappscreen($id){
		$token = $_GET['_ts'];
		$user = session()->get($token);
		$empcd = $user['emp_id'];
		$appdata = DB::table('doc_app_view')->where('doc_app_type',$id)->where('emp_cd', '=', $empcd)->get();
		session()->put($token.'.approvalscreen', $id);
        return view ("approvalscreen",compact('appdata'));
    }


/*public function getlastModule(Request $request){
		$token = @$_GET['_ts'];
        $prev = $request->currenturl;
        $pieces = explode("/", $prev);
        
        $curr_url = request()->segment(1);
        $path = '/'.$curr_url;
        $val = @$pieces[3];
        $piece = explode("?", $val);
        $path_url = '/'.$piece[0];
        
        $filename = FileMaster::select('file_sname','modl')->where('flow_path',$path_url)->first();
        if(isset($filename)){

            // set active module
            session()->forget($token.'.activemodule');
		    session()->put($token.'.activemodule', $filename->modl);
        }

        dd(session()->get($token));
}*/




	/**
	 * This function use for get the admin dashboard details 
	 *
	 * @return admin login page
	 */
	public function selectmodule(){
		$token = $_GET['_ts'];
		if (session()->exists($token)) {
			$log_user = session()->get($token);
			$modules = UserPriv::select('file_id')->where('user_id', $log_user['id'])->where(function($q) {
				$q->orWhere('p_add', 'Y')
				  ->orWhere('p_modify', 'Y')
				  ->orWhere('p_view', 'Y')
				  ->orWhere('p_del', 'Y');
			})->get();
			
			$file_master = array();
			foreach($modules as $module){
				$file_master[] = $module->file_id;
			}
			
			$access_modules = erpmenumodel::select('modl')
				->where('show_flag', '=', 'Y') 
				->whereIn('file_id', $file_master)
				->with('moduleDetails')
				->distinct('modl')
				->get();
			return view('selectmodule',compact('access_modules'));
		}else{
			return redirect('/');
		}
	}

	/**
	 * This function use for set active module
	 *
	 * @return page
	 */
	public function setActiveModule($module,$form = null){	

		try{
			$token = $_GET['_ts'];
			//dd($token);
			$user = session()->get($token);

			
			
			if($form != null){
				$path = '/'.$form;
				$filename = FileMaster::select('file_sname','modl')->where('flow_path',$path)->first();
                if(isset($filename)){
                	$module = $filename->modl;
                    
				}
			}
			if ($token != '') {
				
				session()->forget($token.'.activemodule');
				session()->put($token.'.activemodule', $module);

				if($module == 'FIN'){
					if($form != null){
						return response()->json(['url' => '/fin/fin-dashboard?_ts='.$token]);
					}else{
						return redirect('/fin/fin-dashboard?_ts='.$token);
					}
					
				}elseif($module == 'SALE'){
					if($form != null){
						return response(['url' => '/sale/sale-dashboard?_ts='.$token]);
					}else{
						return redirect('/sale/sale-dashboard?_ts='.$token);
					}

					
				}else{
					return redirect('/dashboard?_ts='.$token);
				}
				
			}else{
				return redirect('/');
			}
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		}
	}


	/**
	 * This function use for side menu open close manage sassion
	 *
	 * @para $cstatus String
	 * @return page
	 */
	public function setSideManuOCStatus(Request $request){		
		try{
			$token = $request->_ts;
			$cstatus = $request->current;
			$user = session()->get($token);
			if ($token != '') {
				if($cstatus == 'open'){
					$status = 'close';
				}else{
					$status = 'open';
				}
				session()->forget($token.'.smanu');
				session()->put($token.'.smanu', $status);
				return response()->json(['error' => 0, 'menu_now' => $status]);
			}else{
				return response()->json(['error' => 1]);
			}
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		}
	}

	/** 
     * Display a accessDenied page.
     *
     * @return \Illuminate\Http\Response
     */
    public function accessDenied(){
		try{
			$token = $_GET['_ts'];
			if (session()->exists($token)) {
				$log_user = session()->get($token);
				$user_name = $log_user['name'];
				return view('accessdenied', compact('user_name')); 
			}else{
				return redirect('/');
			}
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		} 
    }

	/** 
     * Get the assigned unit by user.
     *
     * @return \Illuminate\Http\Response
     */
	public function fetchUserUnit(Request $request){	 
		$user = $request->user_name;
		$user_detail = UserMaster::select('*')->where('user_name', $user)->first();
		$html = '';
		if($user_detail !=''){          
			$userUnit = UserUnit::where('user_id',$user_detail->user_id)->get();
			foreach ($userUnit as $key => $userUnits) {
			 $html .= '<option value='.$userUnits->getUnitDetail->code.'>'.$userUnits->getUnitDetail->name.'('.$userUnits->getUnitDetail->code.')</option>';
			}
       		$status = 1;
		}else{
			$status = 0;
		}
	  	return response()->json(['status' => $status, 'data' => $html]);
    }

	/** 
     * Add the log session data in DB.
     *
     * @return \Illuminate\Http\Response
     */
	public function manageLoginSession($token,$log_data){
		try{
			if (request()->is('loginAction')) {
				//$MAC = exec('getmac');
				//$MAC = strtok($MAC, ' ');
				$user_url = session()->get('_previous');
				$url = request()->ip();
				//dd($url);
				$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$csfr = session()->get('_token');
				//$token = @$_REQUEST['_ts'];
        		//$log_user = session()->get($token);
				$user_id = $log_data['id'];
				$unit_code= $log_data['unitcode'];
				$unit_name= $log_data['unitname'];
				$sess_no= $log_data['logtoken'];
				
				$UserSession = new UserSession();
				$UserSession->user_id = $user_id;
				$UserSession->login_time = date('Y-m-d H:i:s');
				$UserSession->session_no = $sess_no;
				$UserSession->machine = $url;
				$UserSession->login_unit = $unit_code;
				$UserSession->http_session_no = $token;
				$UserSession->http_user_agent = $http_user_agent;
				//$UserSession->mac_ip = $MAC;
				$UserSession->success = 'Yes';
				$UserSession->save();
			}
			elseif(request()->is('logout'))
			{
				//dd(session()->all());
				$csfr = session()->get('_token');

				$UserSession = UserSession::where('http_session_no', $token)->first();
				$UserSession->logout_time = date('Y-m-d H:i:s');
				$UserSession->update();
			}
			
			else{
				return redirect('/');
			}
		}catch(Exseption $e){
			return response()->json(['error' => $e->getMessage()]);
		} 
    }


	/** 
     * Get the log module t-codes
     *
     * @return \Illuminate\Http\Response
     */
	public function getTCodes(Request $request){	 
		$token = @$_REQUEST['_ts'];
        $log_user = session()->get($token);
		$tcode_allow_menus = UserPriv::where('user_id', $log_user['id'])->where(function($q) {
			$q->orWhere('p_add', 'Y')
			  ->orWhere('p_modify', 'Y')
			  ->orWhere('p_view', 'Y')
			  ->orWhere('p_del', 'Y');
		})->pluck('file_id')->toArray();
		//$menus_a = $log_user['allowmenus'];
		
		$tcodes = erpmenumodel::select('file_id','file_type','file_sname','file_menu_id','modl','flow_path','t_code')
			->whereIn('file_id', $tcode_allow_menus)
			->where('t_code', '!=', '')
			->where(function($query) use ($request) {
				$query->orWhere(DB::raw('LOWER(file_master_erp.t_code)'), 'LIKE', '%'.strtolower($request->char).'%');
				$query->orWhere(DB::raw('LOWER(file_master_erp.file_sname)'), 'LIKE', '%'.strtolower($request->char).'%');                  
			}) 
			->get();		
	  	return response()->json(['status' => 1, 'data' => $tcodes]);
    }

	// Get git log from git and save data in database.
	public function CreateGitLog(){
		// get all git developers
		$developers = GitDeveloper::All()->toArray();
		foreach($developers as $developer){
			// get git developers logs
			$output = shell_exec('git log --after="yesterday" --author="'.$developer['developer_name'].'" -p');
			$git_logs = explode('commit ',$output);
			foreach($git_logs as $git_log){
				if($git_log != ''){
					$git_log_data = new GitLog();
					$git_log_data->developer_name = $developer['developer_name'];
					$git_log_data->developer_email = $developer['developer_email'];
					$git_log_data->commit = $git_log;
					$git_log_data->save();
				}
			}
		}
	}
	
	// Get developers by git and create developer in database.
	public function CreateGitDevelopers(){
		$developers = shell_exec('git shortlog -sne --all');
		$developers = explode('     ',$developers);
		array_pop($developers);
		array_shift($developers);
		$d_details = [];
		
		// Delete all git developers
		GitDeveloper::query()->truncate();
		foreach($developers as $developer){
			// Add git developers
			$d_details = new GitDeveloper();
			$data = preg_split('/\t/', $developer);
			$d_details['total_commit'] = $data[0];
			$data = explode('<', $data[1]);
			$d_details['developer_name'] = trim($data[0]);
			$data = preg_split('/\n/', $data[1]);
			$d_details['developer_email'] = str_replace('>','',$data[0]);			
			$d_details->save();
		}
	}

	/** 
     * Get git log by developers wise.
     *
     * @return \Illuminate\Http\Response
     */
	public function gitLogs(){
		$logs = GitDeveloper::with('getCommits')->get();
		return view('gitlog',compact('logs'));
    }

	/** 
     * This function use for run approval proc
     *
	 * @para $p_doc_name, $p_doc_no, $p_amd, $p_doc_st, $p_emp)
     * @return \Illuminate\Http\Response
     */ 
	public function dprApproveDocument(Request $request){
		try{
			$p_doc_name = $request->p_doc_name;
			$p_doc_no = $request->p_doc_no;
			$p_amd = $request->p_amd;
			$p_doc_st = $request->p_doc_st;
			// $p_emp = $request->p_emp;
			$token = @$_REQUEST['_ts'];
        	$p_emp = session()->get($token);
			$p_emp = $p_emp['emp_id'];
			// dd($p_emp);
			$res = DB::select( DB::raw("call dpr_approve_document('$p_doc_name', '$p_doc_no', '$p_amd', '$p_doc_st', '$p_emp');") );
			return response()->json(['error' => 0, 'status' => 1, 'message' => 'Document Approved Successful! '.$p_doc_no]);
		}catch(Exseption $e){
			return response()->json(['error' => 1, 'message' => $e->getMessage()]);
		} 
    }
	
	
}