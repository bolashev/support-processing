<?php

namespace App\Http\Controllers;

use App\Data\Archive\ArchiveListData;
use App\Data\Notes\NoteStoreData;
use App\Data\Notes\NoteUpdateData;
use App\Data\Orders\OrderCommentData;
use App\Data\Orders\OrderListData;
use App\Data\Orders\OrderReturnData;
use App\Data\Orders\OrderUpdateFieldData;
use App\Enums\OrderRequestStatus;
use App\Http\Requests\Archive\ArchiveListRequest;
use App\Http\Requests\Notes\NoteStoreRequest;
use App\Http\Requests\Notes\NoteUpdateRequest;
use App\Http\Requests\Orders\OrderCommentRequest;
use App\Http\Requests\Orders\OrderListRequest;
use App\Http\Requests\Orders\OrderReturnRequest;
use App\Http\Requests\Orders\OrderUpdateFieldRequest;
use App\Http\Resources\ArchiveOrderResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Note;
use App\Models\Order;
use App\UseCases\Archive\ArchiveService;
use App\UseCases\Notes\NoteService;
use App\UseCases\Orders\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    public function index(OrderListRequest $request)
    {
        return $this->handleException(fn () =>
            OrderResource::collection($this->orders->getList(
                new OrderListData(
                    search: $request['search'],
                    request_status: $request['request_status']
                        ? OrderRequestStatus::from($request['request_status'])
                        : null,
                    manager_ids: $request['manager_ids'],
                    shipped_sort: $request['shipped_sort'],
                    per_page: $request['per_page'] ?? 50,
                ),
            ))
        );
    }

    public function show(Order $order)
    {
        return $this->handleException(fn () =>
            new OrderDetailResource($this->orders->getById($order->id))
        );
    }

    public function take(int $order)
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->take($order, auth()->id()))
        );
    }

    public function return(Order $order, OrderReturnRequest $request)
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->return(
                new OrderReturnData(
                    order: $order,
                    user_id: auth()->id(),
                    comment: $request['comment'],
                ),
            ))
        );
    }

    public function updateField(Order $order, OrderUpdateFieldRequest $request)
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->updateField(
                new OrderUpdateFieldData(
                    order: $order,
                    user_id: auth()->id(),
                    field: $request['field'],
                    value: $request['value'],
                ),
            ))
        );
    }

    public function addComment(Order $order, OrderCommentRequest $request)
    {
        return $this->handleTransaction(fn () =>
            new OrderDetailResource($this->orders->addComment(
                new OrderCommentData(
                    order: $order,
                    user_id: auth()->id(),
                    body: $request['body'],
                    is_internal: $request->boolean('is_internal', false),
                ),
            ))
        );
    }
}
