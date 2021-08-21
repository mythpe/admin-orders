<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource as Transformer;
use App\Http\Resources\User\UserResource;
use App\Models\Order as Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        self::$indexTransformer = self::$controllerTransformer = Transformer::class;
        self::$controllerModel = Model::class;
        parent::__construct();
    }

    public function makeOrder()
    {
        /** @var User $user */
        $user = $this->user;
        if ($user && $user->canOpenOrder()) {
            $order = $user->orders()->create([
                'total' => (int)$this->request->get('total', 0),
            ]);
        }
        return $this->getOrders();
    }

    public function getOrders()
    {
        /** @var User $user */
        $user = $this->user;
        $setting = getSettings();
        $total = (int)Model::sum('total');

        $lmt_up = ($setting['lmt_up'] ?? 0);
        $lmt_dn = ($setting['lmt_dn'] ?? 0);

        $rst = ($setting['rst'] ?? 0);
        $rst_plus = ($setting['rst_plus'] ?? 0);
        $rst_minus = ($setting['rst_minus'] ?? 0);


        //$lmt_dn = 5000;
        //$total = 4000;
        if ($lmt_up > 0 && $total > 0 && $total >= $lmt_up) {
            $rst += 1;
            $rst_plus += 1;
            $setting['rst'] = $rst;
            $setting['rst_plus'] = $rst_plus;
            setting($setting)->save();
            Model::query()->delete();
        }

        if ($lmt_dn != 0 && $total < 0) {
            $lmt_dn = abs($lmt_dn);
            $total = abs($total);
            if ($total >= $lmt_dn) {
                $rst += 1;
                $rst_minus += 1;
                $setting['rst'] = $rst;
                $setting['rst_minus'] = $rst_minus;
                setting($setting)->save();
                Model::query()->delete();
            }
        }
        //d($total <= $lmt_dn, $total, $lmt_dn);

        //d($setting);
        $orders = $user->canCloseOrder() ? Model::latest()->get() : [];
        //        $ranks = Model::query()->groupBy('user_id')->get(['user_id']);
        //        $ranks = DB::table(Model::getModelTable())
        $ranks = $user->canRankOrder() ? Model::query()
            ->with('user')
            ->select(['user_id', DB::raw('SUM(total) as total')])
            ->groupBy('user_id')
            ->get()->map(function ($order, $index) {
                $order->id = $index + 1;
                return $order;
            }) : [];
        //        d($ranks);
        return $this->resource([
            'user'    => UserResource::make($this->user),
            'orders'  => Transformer::collection($orders),
            'ranks'   => Transformer::collection($ranks),
            'setting' => $setting,
        ]);
    }

    /**
     * @param array $rules
     * @param Model|null $model
     *
     * @return array
     */
    protected function requestRules(&$rules = [], &$model = null): array
    {
        $main = [
            'user_id' => [$this->requiredRule(), Rule::exists(User::getModelTable())],
            'total'   => ['required', 'integer'],
        ];
        return array_merge($main, $rules);
    }

}
