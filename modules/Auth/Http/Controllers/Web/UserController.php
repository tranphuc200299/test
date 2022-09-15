<?php

namespace Modules\Auth\Http\Controllers\Web;

use Core\Facades\Breadcrumb\Breadcrumb;
use Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Modules\Auth\Entities\Models\Role;
use Modules\Auth\Entities\Models\User;
use Modules\Auth\Http\Requests\UserRequest;
use Modules\Auth\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        Breadcrumb::push(trans('auth::text.auth list_user'), route('cp.users.index'));
        $assign['users'] = $this->userService->getAll();
        if ($assign['users']->currentPage() > $assign['users']->lastPage())
            return redirect()->route('cp.users.index', ['page' => $assign['users']->lastPage()]);

        return view('auth::user.index', $assign);
    }

    public function backToMainUser()
    {
        if (Session::get('adminId')) {
            Auth::loginUsingId(Session::get('adminId'));

            return redirect()->route('cp.users.index');
        }

        return redirect()->route('cp');
    }

    public function loginAsUser(User $user)
    {
        Auth::loginUsingId($user->id);

        return redirect()->route('cp');
    }

    public function create()
    {
//        Breadcrumb::push('user')->push('create');
        Breadcrumb::push(trans('auth::text.auth create_user'), '');
        $assign['roles'] = Role::all();

        return view('auth::user.create', $assign);
    }

    public function store(UserRequest $request)
    {
        $data = $request->only(['full_name', 'user_name', 'password']);
        $data['password'] = Hash::make($data['password']);
        $userName = $data['user_name'];

        /* @var $user User */
        $user = $this->userService->create($data);

        if (!empty($user)) {
//            $email = $data['email'];
//            $password = $data['password'] ?? $this->userService->makePassword();
            $user->assignRole($request->get('role_id'));

            return redirect()->route('cp.users.index')->with('success', $userName . trans('core::message.notify.create success'));
        }

        return redirect()->route('cp.users.index');
    }

    public function show($id)
    {
        /* @var $assign ['user'] User */
//        $assign['user'] = $this->userService->findOr404($id);
//
//        $assign['roles'] = Role::all();
//        $assign['user']->loadMissing('roles');
//
//        return view('auth::user.show', $assign);
        abort(404);
    }

    public function edit($id)
    {
        $idLogin = Auth::id();
        Breadcrumb::push(($idLogin == $id) ? 'プロフィール設定' : 'アカウント編集', '');
        /* @var $assign ['user'] User */
        $assign['user'] = $this->userService->findOr404($id);

        $assign['roles'] = Role::all();
        $assign['user']->loadMissing('roles');

        return view('auth::user.edit', $assign);
    }

    public function update($id, UserRequest $request)
    {
        $assign['user'] = $this->userService->findOr404($id);
        $data = $request->only(['full_name', 'user_name', 'password']);
        $fullName = $data['user_name'];


        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->userService->update($id, $data);

//        $roleIds = array_filter($request->get('role_id'), function ($value) {
//            return !is_null($value) && $value !== '';
//        });
//
//        $user->roles()->sync($roleIds);

        return redirect()->route('cp.users.index')->with('success', $fullName . trans('core::message.notify.update success'));
    }

    public function destroy($id)
    {
        $assign['user'] = $this->userService->find($id);
        $count = $this->userService->getAll()->count();
        if ($assign['user']) {
            $count = $this->userService->getAll()->count();
            $assign['user']->loadMissing('roles');
            $fullName = $assign['user']['user_name'];
            if ($count > 0) {
                $assign['user']->delete();
            }
            return redirect()->back()->with('fail', $fullName . trans('core::message.notify.delete success'));
        }  else {
            return redirect()->back()->with('fail', trans('core::message.notify.delete user_fail'));
        }
    }
}
