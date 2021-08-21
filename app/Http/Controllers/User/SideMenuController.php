<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Class SideMenuController
 *
 * @package App\Http\Controllers\User
 */
class SideMenuController extends Controller
{
    /**
     * get user side menu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sideMenu(): \Illuminate\Http\JsonResponse
    {
        $menu = array_merge($this->adminMenu());
        $this->menuMap($menu);
        return $this->resource($menu);
    }

    /**
     * @code all route names must be lowercase
     * @return array[]
     */
    protected function adminMenu(): array
    {
        return [
            // # Orders
            [
                'title' => 'Orders',
                'name'  => 'dashboard',
                'icon'  => 'dashboard',
                'admin' => !1,
            ],
            // # Users
            [
                'title' => trans_choice("choice.Users", 1),
                'name'  => 'user-index',
                'icon'  => 'person',
                'admin' => !0,
            ],
            // # Setting
            [
                'title' => __("global.setting"),
                'name'  => 'setting',
                'icon'  => 'settings',
                'admin' => !0,
            ],
        ];
    }

    protected function menuMap(&$menu)
    {
        /** @var User $user */
        $user = $this->user;
        foreach ($menu as $k => $v) {
            if (!$user->isAdmin() && $v['admin']) {
                unset($menu[$k]);
            }
            continue;
            if (array_key_exists('scopes', $v) && !$this->filterScope($v['scopes'])) {
                unset($menu[$k]);
                continue;
            }

            if (array_key_exists('permissions', $v) && !$this->filterPermissions($v['permissions'])) {
                unset($menu[$k]);
                continue;
            }

            if (array_key_exists('items', $v)) {
                $this->menuMap($menu[$k]['items']);
                if (empty($menu[$k]['items'])) {
                    unset($menu[$k]['items']);
                }
            }

            if (array_key_exists('scopes', $v)) {
                unset($menu[$k]['scopes']);
            }
            // if (array_key_exists('items',$menu[$k]) && empty($menu[$k]['items'])) unset($menu[$k]);
            if (!array_key_exists('items', $menu[$k]) && !array_key_exists('name', $menu[$k])) {
                unset($menu[$k]);
            }
        }
        $menu = array_values($menu);
    }

    protected function filterScope($scopes)
    {
        /** @var \App\Models\User $user */
        $user = $this->request->user();

        foreach ($scopes as $scope) {
            if ($this->user->tokenCan($scope)) {
                return true;
            }
        }
        return false;
    }

    protected function filterPermissions($permissions)
    {
        return true;
    }
}
