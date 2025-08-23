<?php

namespace [[rootNamespace]]\Http\Controllers\Api;

use AhsanDev\Support\Field;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use [[rootNamespace]]\Http\Filters\UserFilters;
use [[rootNamespace]]\Http\Requests\UserRequest;

class UserController implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:user:view', only: ['index']),
            new Middleware('can:user:create', only: ['create', 'store']),
            new Middleware('can:user:update', only: ['edit', 'update']),
            new Middleware('can:user:delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserFilters $filters)
    {
        return User::filter($filters)->simplePaginate();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user): Field
    {
        return $this->fields($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        return new UserRequest($request, $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): User
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Field
    {
        return $this->fields($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return new UserRequest($request, $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (in_array(1, $request->items, true)) {
            return ['message' => 'This User Cannot Be Deleted!'];
        }

        User::destroy($request->items);

        return [
            'message' => count($request->items) > 1
                ? 'Users Deleted Successfully!'
                : 'User Deleted Successfully!',
        ];
    }

    /**
     * Prepare the form fields for the resource.
     */
    protected function fields(User $model): Field
    {
        return Field::make()
            ->field('name', $model->name)
            ->field('email', $model->email);
    }
}
