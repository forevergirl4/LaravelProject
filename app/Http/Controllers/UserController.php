<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function postSignUp(Request $request){
           $this->validate($request, [
               'email' => 'email|unique:users|required',
               'first_name' => 'max:120|required',
               'password' => 'min:4|required'
           ]);

            $email = $request['email'];
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $phone = $request['phone'];
            $password = bcrypt($request['password']);

            $user = new User();
            $user->email = $email;
            $user->first_name = $first_name;
            $user->password = $password;
            $user->last_name = $last_name;
            $user->phone = $phone;
            $user->save(); 
           	Auth::login($user);
            return redirect()->route('dashboard');
    }
	public function postSignIn(Request $request){
                $this->validate($request, [
                    'email' => 'required',
                    'password' => 'required'
                ]);
                if (Auth::attempt([ 'email' => $request['email'], 'password' => $request['password'] ])){
                    return redirect()->route('dashboard');
                }
                return redirect()->back();
        }
    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function getAccount()
    {
        return view('account', ['user' => Auth::user()]);
    }
    public function getProfilePage()
    {
        return view('profilepage', ['user' => Auth::user()]);
    }
    public function postSaveAccount(Request $request)
    {
        $this->validate($request, [
           'first_name' => 'required|max:120'
        ]);
        $user = Auth::user();
        $old_name = $user->first_name;
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->update();
        $file = $request->file('image');
        $filename = $request['first_name'] . '-' . $user->id . '.jpg';
        $old_filename = $old_name . '-' . $user->id . '.jpg';
        $update = false;
        if (Storage::disk('local')->has($old_filename)) {
            $old_file = Storage::disk('local')->get($old_filename);
            Storage::disk('local')->put($filename, $old_file);
            $update = true;
        }
        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
        }
        if ($update && $old_filename !== $filename) {
            Storage::delete($old_filename);
        }
        return redirect()->route('account');
    }
    public function getUserImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }
}