<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Validator;
use SebastianBergmann\GlobalState\Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $roleRepo;
    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }
    public function index()
    {
        $datas = $this->roleRepo->all();
        return view('admin/role/index', compact('datas'));
    }

    function getRoutesByGroup()
    {
        $routeGroups = [];

        $routesCollection = Route::getRoutes();
        $prefix = 'admin.';

        foreach ($routesCollection as $route) {
            $routeName = $route->getName();

            if ($routeName && strpos($routeName, $prefix) === 0 && $routeName != 'admin.index' && $routeName != 'admin.403') {
                $groupKey = explode('.', $routeName, 3);
                $groupKey = isset($groupKey[1]) ? $prefix . $groupKey[1] . '.index' : null;

                if ($groupKey) {
                    $routeGroups[$groupKey][] = $routeName;
                }
            }
        }
        return $routeGroups;
    }

    public function create()
    {
        $routeGroups = $this->getRoutesByGroup();
        return view('admin/role/create', compact('routeGroups'));
    }
    public function store(Request $request)
    {
        try {
            $input = $request->except('selected_routes');
            $validator = Validator::make($input, $this->roleRepo->validateCreate());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $input['active'] = isset($input['active']) ? 1 : 0;
            $input['created_by'] = Auth::user()->id;
            $input['permissons'] = implode(",", $request->selected_routes);
            $role = $this->roleRepo->create($input);
            return redirect()->route('admin.role.index')->with('sucess', 'Tạo mới thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        $data = $this->roleRepo->find($id);
        $routeGroups = $this->getRoutesByGroup();
        $permisions = [];
        if ($data->permissons) {
            $permisions = explode(',', $data->permissons);
            $permisions = array_unique($permisions);
        }
        return view('admin.role.edit', compact('data', 'permisions', 'routeGroups'));
    }
    public function update(Request $request, $id)
    {
        try {
            $input = $request->except('selected_routes');
            $validator = Validator::make($input, $this->roleRepo->validateUpdate($id));
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $input['permissons'] = implode(",", $request->selected_routes);
            $input['active'] = isset($input['active']) ? 1 : 0;
            $role = $this->roleRepo->update($input, $id);
            return redirect()->route('admin.role.index')->with('success', 'Cập nhật thành công');
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        $this->roleRepo->delete($id);
        return redirect()->route('admin.role.index')->with('success', 'Xóa thành công');
    }
}
