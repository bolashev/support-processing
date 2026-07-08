<?php

namespace App\Http\Controllers;

use DomainException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class Controller
{
    /**
     * Общий метод для обработки исключений.
     *
     * @param callable $callback Функция для выполнения с обработкой исключений
     * @param bool $isJson Указывает, нужно ли вернуть JSON-ответ
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function handleException(callable $callback, bool $isJson = true, callable|null $onError = null)
    {
        try {
            return $callback();
        } catch (ModelNotFoundException $exception) {
            return $this->handleError($exception, $isJson, 'Resource not found', Response::HTTP_NOT_FOUND, $onError);
        } catch (AuthenticationException $exception) {
            return $this->handleError($exception, $isJson, 'Unauthenticated', Response::HTTP_UNAUTHORIZED, $onError);
        } catch (DomainException $exception) {
            return $this->handleError($exception, $isJson, $exception->getMessage(), Response::HTTP_BAD_REQUEST, $onError);
        } catch (AccessDeniedHttpException $exception) {
            return $this->handleError($exception, $isJson, 'Access Denied', Response::HTTP_FORBIDDEN, $onError);
        } catch (NotFoundHttpException $exception) {
            return $this->handleError($exception, $isJson, 'Not Found', Response::HTTP_NOT_FOUND, $onError);
        } catch (Throwable $exception) {
            return $this->handleError($exception, $isJson, 'Server error', Response::HTTP_INTERNAL_SERVER_ERROR, $onError);
        }
    }

    /**
     * Обработка и возвращение ошибки.
     *
     * @param \Throwable $exception Исключение
     * @param bool $isJson Указывает, нужно ли вернуть JSON-ответ
     * @param string $message Сообщение для пользователя
     * @param int $statusCode HTTP статус код
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|JsonResponse
     */
    protected function handleError(Throwable $exception, bool $isJson, string $message, int $statusCode, callable|null $onError = null)
    {
        report($exception);
        if(!is_null($onError)) {
            $onError($exception);
        }
        if ($isJson) {
            return response()->json(['message' => $message], $statusCode);
        } else {
            return back()->withInput()->withErrors(['message' => $message]);
        }
    }

    /**
     * Обработка исключений с транзакцией.
     *
     * @param callable $callback Функция для выполнения с обработкой исключений
     * @param bool $isJson Указывает, нужно ли вернуть JSON-ответ
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    protected function handleTransaction(callable $callback, bool $isJson = true, callable|null $onError = null)
    {
        DB::beginTransaction();
        try {
            $response = $callback();
            DB::commit();
            return $response;
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, 'Resource not found', Response::HTTP_NOT_FOUND, $onError);
        } catch (AuthenticationException $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, 'Unauthenticated', Response::HTTP_UNAUTHORIZED, $onError);
        } catch (DomainException $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, $exception->getMessage(), Response::HTTP_BAD_REQUEST, $onError);
        } catch (AccessDeniedHttpException $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, 'Access Denied', Response::HTTP_FORBIDDEN, $onError);
        } catch (NotFoundHttpException $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, 'Not Found', Response::HTTP_NOT_FOUND, $onError);
        } catch (Throwable $exception) {
            DB::rollBack();
            return $this->handleError($exception, $isJson, 'Server error', Response::HTTP_INTERNAL_SERVER_ERROR, $onError);
        }
    }
}
