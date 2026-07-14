<?php

namespace App\Http\Controllers;

use App\Data\Orders\OrderCommentData;
use App\Data\Orders\OrderReturnData;
use App\Data\Orders\OrderUpdateFieldData;
use App\Http\Requests\Orders\OrderCommentRequest;
use App\Http\Requests\Orders\OrderListRequest;
use App\Http\Requests\Orders\OrderReturnRequest;
use App\Http\Requests\Orders\OrderUpdateFieldRequest;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\UseCases\Orders\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    public function index(OrderListRequest $request): JsonResponse
    {
        return $this->handleException(fn () =>
            OrderResource::collection($this->orders->getList($request->data()))
        );
    }

    public function show(Order $order): JsonResponse
    {
        return $this->handleException(fn () =>
            new OrderDetailResource($this->orders->getById($order->id))
        );
    }

    public function take(int $order, Request $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->take($order, $request->user()->id))
        );
    }

    public function return(Order $order, OrderReturnRequest $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->return(
                OrderReturnData::from([
                    'order' => $order,
                    'user_id' => $request->user()->id,
                    'comment' => $request->data()->comment,
                ]),
            ))
        );
    }

    public function updateField(Order $order, OrderUpdateFieldRequest $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->updateField(
                OrderUpdateFieldData::from([
                    'order' => $order,
                    'user_id' => $request->user()->id,
                    'field' => $request->input('field'),
                    'value' => $request->input('value'),
                ]),
            ))
        );
    }

    public function addComment(Order $order, OrderCommentRequest $request): JsonResponse
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->addComment(
                OrderCommentData::from([
                    'order' => $order,
                    'user_id' => $request->user()->id,
                    'body' => $request->input('body'),
                    'is_internal' => $request->boolean('is_internal', false),
                ]),
            ))
        );
    }
}
