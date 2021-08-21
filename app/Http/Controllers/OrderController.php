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
            }):[];
        //        d($ranks);
        return $this->resource([
            'user'   => UserResource::make($this->user),
            'orders' => Transformer::collection($orders),
            'ranks'  => Transformer::collection($ranks),
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
