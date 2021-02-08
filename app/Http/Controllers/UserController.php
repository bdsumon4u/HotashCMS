<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return $this->view([
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return $this->view();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()
            ->action([static::class, 'index'])
            ->with('success', 'User Has Been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $this->view();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return $this->view();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()
            ->action([static::class, 'index'])
            ->with('success', 'User Has Been Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()
            ->action([static::class, 'index'])
            ->with('success', 'User Has Been Deleted.');
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function restore(User $user)
    {
        $this->authorize('restore', $user);

        $user->restore();

        return redirect()
            ->action([static::class, 'index'])
            ->with('success', 'User Has Been Restored.');
    }

    /**
     * Permanently remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(User $user)
    {
        $this->authorize('forceDelete', $user);

        $user->forceDelete();

        return redirect()
            ->action([static::class, 'index'])
            ->with('success', 'User Has Been Permanently Deleted.');
    }
}
