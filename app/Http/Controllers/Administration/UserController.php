<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\StoreUserRequest;
use App\Http\Requests\Administration\UpdateUserRequest;
use App\Models\Song;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index', ['users' => User::withTrashed()->with('songs')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());

        if ($user) {
            $user->createVerificationCode();

            return redirect()->route('admin.users.index')->withSuccess("$user->name successfully created!");
        }

        return redirect()->route('admin.users.index')->withDanger("Failed to created new user: $request->name");
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.user.show', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->admin = $request->has('admin');
        $user->deleted_at = $request->has('banned') ? Carbon::now() : null;

        if ($user->save()) {
            return redirect()->back()->withSuccess("$user->name has been updated!");
        }

        return redirect()->back()->withDanger("Something went wrong while trying to update $user->name!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        foreach ($user->songs()->withTrashed()->get() as $song) {
            Song::destroyWithRelated($song);
        }

        if ($user->forceDelete()) {
            return redirect()->route('admin.users.index')->withInfo("Permanently deleted $user->name");
        }

        return redirect()->route('admin.users.index')->withDanger("Something went wrong while deleting $user->name");
    }
}
