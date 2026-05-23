<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MenuDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMenuRequest;
use App\Models\Menu;
use App\Services\MenuService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;
    protected $uploadService;

    public function __construct(MenuService $menuService, UploadService $uploadService)
    {
        $this->menuService = $menuService;
        $this->uploadService = $uploadService;
    }

    public function index(MenuDataTable $dataTable)
    {
        return $dataTable->render('menu.list');
    }

    public function formCreateMenu()
    {
        return view('menu.form');
    }

    public function formEditMenu(String $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('menu.index')->with('error', 'Menu not found.');
        }

        $menu->icon_url = $this->uploadService->getPublicUrl($menu->icon);

        return view('menu.form', compact('menu'));
    }

    public function createMenu(CreateMenuRequest $request)
    {
        $data = $request->validated();

        $menu = $this->menuService->createMenu($data['name'], $data['icon']);

        return response()->json([
            'status' => true,
            'message' => 'Menu created successfully.',
            'data' => $menu,
        ]);
    }

    public function deleteMenuById(String $id)
    {
        $this->menuService->deleteMenuById($id);

        return response()->json([
            'status' => true,
            'message' => 'Menu deleted successfully.',
        ]);
    }

    public function updateMenu(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
        } else {
            $icon = null;
        }

        $menu = $this->menuService->updateMenu($id, $data['name'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Menu updated successfully.',
            'data' => $menu,
        ]);
    }
}
