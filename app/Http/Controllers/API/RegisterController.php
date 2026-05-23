<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['type'] = 1;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
   
            return response()->json([
                'token' => $user->createToken('MyApp')->plainTextToken,
                'data' => $user,
                'message' => 'User login successfully.'
            ]);
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
    
    public function loginUserDevice(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
            'device_type' => 'required',
            'device_brand' => 'required',
            'device_model' => 'required',
            'os_version' => 'required'
        ]);
    
        if ($validator->fails()) {
            Log::error('Validation Error', $validator->errors()->toArray());
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    
        $input = $request->all();
        $user_id = $user->id;
        $device_id = $input['device_id'];
    
        // Cek apakah sudah ada perangkat yang tercatat untuk user ini
        $existingDevice = UserDevice::where('user_id', $user_id)->first();
    
        Log::info('Checking existing device', [
            'user_id' => $user_id,
            'device_id' => $device_id,
            'existing_device' => $existingDevice ? $existingDevice->device_id : null
        ]);
    
        if ($existingDevice) {
            if ($existingDevice->device_id === $device_id) {
                Log::info('Login successful - Device recognized', ['user_id' => $user_id]);
                return $this->sendResponse([], 'Login successful. Device recognized.');
            } else {
                Log::warning('Login attempt from different device', [
                    'user_id' => $user_id,
                    'existing_device_id' => $existingDevice->device_id,
                    'new_device_id' => $device_id
                ]);
                return $this->sendError('Login Error.', 'Device is different');       

            }
        }
    
        // Periksa apakah device_id sudah digunakan oleh user lain
        $otherDevice = UserDevice::where('device_id', $device_id)->first();
        if ($otherDevice) {
            Log::warning('Device already registered to another user', [
                'current_user_id' => $user_id,
                'registered_user_id' => $otherDevice->user_id,
                'device_id' => $device_id
            ]);
            return $this->sendResponse([], 'Login successful. Device recognized.');
        }
    
        // Simpan perangkat baru
        UserDevice::create([
            'user_id' => $user_id,
            'device_id' => $device_id,
            'device_type' => $input['device_type'],
            'device_brand' => $input['device_brand'],
            'device_model' => $input['device_model'],
            'os_version' => $input['os_version'],
        ]);
    
        Log::info('New device registered', [
            'user_id' => $user_id,
            'device_id' => $device_id
        ]);
    
        return $this->sendResponse([], 'Login successful. Device registered.');
    }


    
    public function editProfile(Request $request): JsonResponse
    {
        $user = Auth::user();
    
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // Update nama jika ada
        if ($request->has('name')) {
            $user->name = $request->name;
        }
    
        // Update foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto yang lama jika ada
            if ($user->photo) {
                Storage::delete($user->photo);
            }
            
            // Simpan foto baru
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }
    
        $user->save();
    
        return $this->sendResponse($user, 'Profile updated successfully.');
    }
    
    // Fungsi untuk mengedit password
    public function editPassword(Request $request): JsonResponse
    {
        $user = Auth::user();
    
        // Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // Cek apakah password saat ini benar
        if (!password_verify($request->current_password, $user->password)) {
            return $this->sendError('Current password is incorrect.');
        }
    
        // Update password
        $user->password = bcrypt($request->new_password);
        $user->save();
    
        return $this->sendResponse([], 'Password updated successfully.');
    }
    
    public function getSettingContent(Request $request): JsonResponse
    {

        $key = $request->input('key');
        
        $phase = Setting::where('teks', $key)->first();

        return $this->sendResponse($phase, 'Setting Content retrieved successfully.');
    }

}