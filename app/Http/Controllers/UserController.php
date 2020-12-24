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
use Carbon\Carbon;
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
        $user = User::find(auth()->id());

        $file = $request->file('avatar');

        $filename = $file->store($user->id, 'public');

        User::where('id', $user->id)->update(['avatar' => $filename]);

        return response(['filename' => $filename], 200);
    }

    public function changePassword(ChangePassword $request)
    {
        $user = User::find(auth()->id());

        try {
            $user = $user->update(['password' => bcrypt($request->password)]);

            return response(['success' => true], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfile(EditProfile $request)
    {
        $user = User::find(auth()->id());

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
        $user = User::find(auth()->id());

        $data = ['user_id' => $user->id, 'token' => $request->token];

        $exists = DeviceToken::where($data)->first();

        if (!$exists) {
            DeviceToken::create($data);

            return response(['success' => true], 200);
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

    public function getNotifications(Request $request)
    {
        $user = User::find(auth()->id());

        $notifications = $user
            ->notifications()
            ->where('created_at', '>', Carbon::now()->subDays(30))
            ->get();

        return response(['notifications' => $notifications], 200);
    }

    public function markNotificationAsRead(Request $request)
    {
        $user = User::find(auth()->id());

        $user
            ->notifications()
            ->where('id', $request->notification_id)
            ->first()
            ->markAsRead();

        return response(['status' => true], 200);
    }

    public function searchUsers(Request $request)
    {
        $keywords = $request->keywords;

        $users = User::query()
            ->where('name', 'ilike', "%$keywords%")
            ->orWhere('username', 'ilike', "%$keywords%")
            ->get();

        return response(['users' => $users], 200);
    }
}
