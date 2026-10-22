<?php

namespace App\Mcp\Tools\Concerns;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Support\ValidationMessages;

/**
 * Shared plumbing for MCP tools: authorize through the model policies, turn denials,
 * validation failures and missing models into tool errors, and serialize models as JSON.
 */
trait AuthorizesToolCalls
{
    /**
     * Run the callback and convert expected failures into error responses the agent can read.
     *
     * @param  Closure(): Response  $callback
     */
    protected function respond(Closure $callback): Response
    {
        try {
            return $callback();
        } catch (AuthorizationException $exception) {
            return Response::error($exception->getMessage());
        } catch (ValidationException $exception) {
            return Response::error(ValidationMessages::from($exception));
        } catch (ModelNotFoundException $exception) {
            return Response::error($this->notFoundMessage($exception));
        }
    }

    /**
     * Check a policy ability for the request user. Throws AuthorizationException on denial.
     *
     * @param  Model|class-string<Model>|array<int, mixed>  $arguments
     *
     * @throws AuthorizationException
     */
    protected function authorize(Request $request, string $ability, mixed $arguments): void
    {
        Gate::forUser($request->user())->authorize($ability, $arguments);
    }

    /**
     * @template TModel of Model
     *
     * @param  class-string<TModel>  $model
     * @return TModel
     *
     * @throws ModelNotFoundException<TModel>
     */
    protected function findByUlid(string $model, string $ulid): Model
    {
        $found = $model::query()->where('ulid', $ulid)->first();

        if ($found === null) {
            throw (new ModelNotFoundException)->setModel($model, [$ulid]);
        }

        return $found;
    }

    protected function modelResponse(Model $model): Response
    {
        return $this->jsonResponse($model->toArray());
    }

    /**
     * @param  iterable<Model>  $models
     */
    protected function modelsResponse(iterable $models): Response
    {
        $items = [];

        foreach ($models as $model) {
            $items[] = $model->toArray();
        }

        return $this->jsonResponse($items);
    }

    protected function jsonResponse(mixed $data): Response
    {
        return Response::text(json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param  ModelNotFoundException<Model>  $exception
     */
    private function notFoundMessage(ModelNotFoundException $exception): string
    {
        $ids = implode(', ', $exception->getIds());

        return $ids === ''
            ? class_basename($exception->getModel()).' not found.'
            : class_basename($exception->getModel())." [{$ids}] not found.";
    }
}
