<?php

namespace App\Http\Controllers\Admin;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Repositories\DepartmentRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Validator;
use App\Common\FileUploads;

class UserController extends Controller
{
    use ApiResponses;
    private $userRepo, $departmentRepo,$positionRepo,$roleRepo;
    public function __construct(RoleRepository $roleRepo, UserRepository $userRepo, DepartmentRepository $departmentRepo, PositionRepository $positionRepo)
    {
        $this->userRepo = $userRepo;
        $this->departmentRepo = $departmentRepo;
        $this->positionRepo = $positionRepo;
        $this->roleRepo = $roleRepo;
    }

    public function index(Request $request)
    {
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $query = DB::table('users');
        if (isset($request->name) && !is_null($request->name)) {
            $query = $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if (isset($request->department_id) && !is_null($request->department_id)) {
            $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $request->department_id);
            $query->where('department_id', $request->department_id);
        }

        if (isset($request->position_id) && !is_null($request->position_id)) {
            $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $request->position_id);
            $query->where('position_id', $request->position_id);
        }

        $datas = $query->where('username', '!=', 'root')->paginate(15);
        //$datas = $this->userRepo->paginate($request);
        return view('admin/user/index', compact('datas', 'departments', 'positions'));
    }

    public function create()
    {
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $roles = StringHelpers::getSelectOptions($this->roleRepo->getActive());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        return view('admin/user/create', compact('genders', 'positions', 'departments', 'roles'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $input['role_id'] = implode(",", $input['role_id']);
            $validator = Validator::make($input, $this->userRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            if (isset($input['avatar'])) {
                $input['avatar'] = FileUploads::uploadImage($request, "avatars", "avatar");
            }
            $input['active'] = isset($input['active']) ? 1 : 0;

            $this->userRepo->create($input);
            return redirect()->route('admin.user.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->userRepo->find($id);
            if ($data) {
                $departments = StringHelpers::getSelectOptions($this->departmentRepo->all(), $data->department_id);
                $positions = StringHelpers::getSelectOptions($this->positionRepo->all(), $data->position_id);
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                $roles = StringHelpers::getSelectOptions($this->roleRepo->getActive(), explode(',', $data->role_id));
                return view('admin/user/edit', compact('data', 'genders', 'departments', 'positions', 'roles'));
            }
            return back()->with("error", "Không tìm thấy dữ liệu");
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $input['role_id'] = implode(",", $input['role_id']);
            $data = $this->userRepo->find($id);
            $validator = Validator::make($input, $this->userRepo->validateUpdate($id));
            if ($validator->fails()) {
                //dd($validator->errors()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (isset($input['avatar'])) {
                $input['avatar'] = FileUploads::uploadImage($request, "avatars", "avatar", $data->avatar);
            }
            $input['active'] = isset($input['active']) ? 1 : 0;

            $res = $data->update($input);
            return redirect()->route('admin.user.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function delete()
    {
        dd('oke');
        return 1;
    }

    public function destroy($id)
    {
        try {
            $this->userRepo->destroy($id);
            return redirect()->route('admin.user.index')->with('success', 'Xóa thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function getChangePassword($id)
    {
        try {
            $data = $this->userRepo->find($id);
            return view('admin.user.change_password', compact('data'));
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->userRepo->validateChangePassword());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $res = $this->userRepo->update($request->only('password'), $id);
            return redirect()->route('admin.user.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function getByDepartment(Request $request)
    {
        try {
            $departmentId = $request->departmentId;
            if ($departmentId != null) {
                $datas = $this->userRepo->getBy('department_id', $departmentId);
            } else {
                $datas = $this->userRepo->all();
            }
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->success($ex->getMessage());
        }
    }

}
