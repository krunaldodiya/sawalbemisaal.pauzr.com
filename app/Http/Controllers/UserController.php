<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassword;
use App\Http\Requests\EditProfile;
use App\Http\Requests\UserInfo;
use App\Http\Requests\SetToken;

use App\Repositories\UserRepositoryInterface;

use App\DeviceToken;
use App\Invitation;
use App\Wallet;
use App\User;

use Error;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function me(Request $request)
    {
        $user = $this->userRepositoryInterface->getUserById(auth()->id());

        return compact('user');
    }

    public function getUserById(UserInfo $request)
    {
        $user = $this->userRepositoryInterface->getUserById($request->user_id);

        return compact('user');
    }

    public function getWallet(Request $request)
    {
        $wallet = Wallet::with([
            'transactions' => function ($query) {
                return $query->where('status', 'success')->orderBy('created_at', 'desc');
            }
        ])
            ->where(['user_id' => auth()->id()])
            ->first();

        return compact('wallet');
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();

        $file = $request->file('avatar');

        $filename = $file->store($user->id, 'public');

        User::where('id', $user->id)->update(['avatar' => $filename]);

        return response(['filename' => $filename], 200);
    }

    public function changePassword(ChangePassword $request)
    {
        $user = auth()->user();

        try {
            $user = $user->update(['password' => bcrypt($request->password)]);

            return response(['success' => true], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfile(EditProfile $request)
    {
        $user = auth()->user();

        $data = $request->all();

        $data['status'] = true;

        try {
            $update = $user->update($data);

            $user = $this->userRepositoryInterface->getUserById($user->id);

            return compact('user');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setToken(SetToken $request)
    {
        $user = auth()->user();

        $data = ['user_id' => $user->id, 'token' => $request->token];

        $exists = DeviceToken::where($data)->first();

        if (!$exists) {
            return DeviceToken::create($data);
        }

        throw new Error("Token already exists", 401);
    }

    public function checkInvitation(Request $request)
    {
        $sender_id = $request->segment(4);
        $mobile = $request->segment(5);

        $exists = Invitation::where(['mobile' => $mobile])->first();

        if (!$exists) {
            Invitation::create([
                'sender_id' => $sender_id,
                'mobile' => $mobile
            ]);
        }

        return redirect("/refer");
    }
}
