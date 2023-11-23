<?php

namespace App\Http\Controllers\Admin;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Validator;
use App\Common\FileUploads;

class DepartmentController extends Controller
{
    use ApiResponses;
    private $departmentRepo,$userRepo;
    public function __construct(DepartmentRepository $departmentRepo, UserRepository $userRepo)
    {
        $this->departmentRepo = $departmentRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        // $categories = Category::whereNull('parent_id')->get();

        $datas = $this->departmentRepo->all();
        $datas = $this->departmentRepo->getParent();
        $depth = 0;
        return view('admin/department/index', compact('datas', 'depth'));
        // return view('admin/department/index');
    }

    public function getData()
    {
        try {
            $datas = $this->departmentRepo->all();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $departments = StringHelpers::getSelectOptions($this->departmentRepo->all());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases());
        return view('admin/department/create', compact('genders', 'users', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->departmentRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;

            $this->departmentRepo->create($input);
            return redirect()->route('admin.department.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->departmentRepo->find($id);
            $parents = StringHelpers::getSelectOptions($this->departmentRepo->getExcept($id), $data->parent_id);
            $users = StringHelpers::getSelectOptions($this->userRepo->getActive(), $data->manager_by);
            if ($data) {
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                return view('admin/department/edit', compact('data', 'users', 'parents'));
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
            $data = $this->departmentRepo->find($id);
            $validator = Validator::make($input, $this->departmentRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $res = $data->update($input);
            return redirect()->route('admin.department.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function delete()
    {
        dd('Xoá tạm');
        return 1;
    }

    public function destroy($id)
    {
        try {
            $this->departmentRepo->destroy($id);
            return $this->success();
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }
}
