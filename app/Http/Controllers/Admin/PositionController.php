<?php

namespace App\Http\Controllers\Admin;

use App\Common\Traits\ApiResponses;
use App\Enums\GenderEnum;
use App\Enums\PositionRankEnum;
use App\Models\Position;
use Exception;
use Illuminate\Http\Request;
use App\Common\StringHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\PositionRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Common\FileUploads;

class PositionController extends Controller
{
    private $positionRepo ,$userRepo ;
    public function __construct(PositionRepository $positionRepo, UserRepository $userRepo)
    {
        $this->positionRepo = $positionRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $now = Carbon::now();
        // $categories = Category::whereNull('parent_id')->get();

        //$datas = $this->positionRepo->all();
        // $datas = $this->positionRepo->getParent();
        // $depth = 0;
        //return view('admin/position/index', compact('datas'));
        return view('admin/position/index');
    }

    public function getData()
    {
        try {
            $datas = $this->positionRepo->queryAll()->get();
            return $this->success($datas);
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

    public function create()
    {
        $positions = StringHelpers::getSelectOptions($this->positionRepo->all());
        $users = StringHelpers::getSelectOptions($this->userRepo->getActive());
        $positionRanks = StringHelpers::getSelectEnumOptions(PositionRankEnum::cases());
        return view('admin/position/create', compact('positionRanks', 'users', 'positions'));
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, $this->positionRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['created_by'] = Auth::user()->id;
            $input['active'] = isset($input['active']) ? 1 : 0;

            $this->positionRepo->create($input);
            return redirect()->route('admin.position.index')->with('success', 'Thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->positionRepo->find($id);
            $parents = StringHelpers::getSelectOptions($this->positionRepo->getExcept($id), $data->parent_id);
            $users = StringHelpers::getSelectOptions($this->userRepo->getActive(), $data->manager_by);
            if ($data) {
                $genders = StringHelpers::getSelectEnumOptions(GenderEnum::cases(), $data->gender);
                return view('admin/position/edit', compact('data', 'users', 'parents'));
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
            $data = $this->positionRepo->find($id);
            $validator = Validator::make($input, $this->positionRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['active'] = isset($input['active']) ? 1 : 0;
            $res = $data->update($input);
            return redirect()->route('admin.position.index')->with('success', 'Thành công');
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
            $this->positionRepo->destroy($id);
            return $this->success();
        } catch (Exception $ex) {
            return $this->error($ex->getMessage());
        }
    }

}
