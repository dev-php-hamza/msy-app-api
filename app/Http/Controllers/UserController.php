<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserInfo;
use App\Role;
use App\Country;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('checkAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.user.details',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (count($user) > 0 ) {
            $userInfo = UserInfo::whereUserId($user->id)->first();
            $fileName = $this->checkImageInSystem($userInfo->image);
            if ($fileName != false) {
              $fileToBeDeleted =  public_path().'/profile/images/'.$fileName;
              if (file_exists($fileToBeDeleted)) {
                @unlink($fileToBeDeleted);
              }
            }

            $user->delete();
            return redirect()->route('users.index')->with('message','User has been deleted successfully!');
        }else{
            return redirect()->route('users.index')->with('error','Kindly choose valid user');
        }
    }

    /**
     * Get image-name 
     *
     * @param   string - $urlStirng
     * @return  name: 5d820c85efcee.jpeg |false
     **/
    public function checkImageInSystem($urlStirng)
    {
      if (strpos($urlStirng, 'profile/images/') !== false) {
        $imageParts = explode('profile/images/', $urlStirng);
        return $imageParts[1];
      }
      return false;

    }
    public function checkAdmin(Request $request)
    {
        $email =  $request->input('email');
        $user = User::where('email', $email)->first();
        $response = [];
        $response['result'] = false;
        if(count((array)$user) > 0){
            $role = Role::where('id',$user->role_id)->first();
            if($role->name == 'admin')
            {
                $response['result'] = true;
            }
        }
        return response()->json($response);
    }

    public function searchUser(Request $request)
    {
        $input = $request->all();

        $query = User::query();

        if ($input['type'] == 'name') {
            $query = $query->where('first_name','LIKE' ,'%'.strtolower(request('term')).'%')->orWhere('first_name','LIKE' ,'%'. strtoupper(request('term')).'%')->orWhere('last_name','LIKE' ,'%'. strtolower(request('term')).'%')->orWhere('last_name','LIKE' ,'%'. strtoupper(request('term')).'%');
        }

        if ($input['type'] == 'email') {
            $query = $query->where('email','=' ,request('term'));
        }

        if ($input['type'] == 'phone') {
            $userInfos = UserInfo::where('phone_number', '=', $input['term'])->get();
            $userIds = array();
            foreach ($userInfos as $key => $userInfo) {
                $userIds[] = $userInfo->user_id;
            }
            $query = $query->whereIn('id', $userIds);
        }
        $users = $query->paginate(10);
        return view('admin.user.index',compact('users'));
    }

    public function exportUsers()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users.csv');

        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');

        // // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        $header = array('Name', 'Email', 'Gender', 'Date of Birth','Phone', 'City', 'Country', 'Created date');
        fputcsv($output, $header);

        //Get all orders in given date range
        $users = User::with('userInfo')->where('role_id', 2)->latest()->get();

        $rows = array();
        if (count($users) > 0) {
          foreach ($users as $key => $user) {
            $userInfo = $user['userInfo'];
            // $userCountry = Country::where('country_code', $userInfo->country)->first();
            if (isset($userInfo) && !empty($userInfo) && $userInfo != '') {
                $col = array();
                $col[0] = $user->fullName();
                $col[1] = $user->email;
                $col[2] = $userInfo->gender;
                $col[3] = isset($userInfo->date_of_birth)?date('d-M-Y', strtotime($userInfo->date_of_birth)):'';
                $col[4] = $userInfo->phone_number;
                $col[5] = $userInfo->city;
                $col[6] = $userInfo->country;
                $col[7] = date('d-M-Y', strtotime($user->created_at));
                array_push($rows, $col);
            }
          }
        }

        // // loop over the rows, outputting them
        foreach ($rows as $row) {
          fputcsv($output, $row);
        }
        
        fclose($output);
        die();
    }
}
