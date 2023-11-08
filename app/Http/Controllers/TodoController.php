<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
  public function index()
  {
    $auth = Auth::user();
    $todos = Todo::where("user_id", $auth->id)->orderBy("created_at", "desc")->get();
    $data = [
      "auth" => $auth,
      "todos" => $todos
    ];

    return view("app.home", $data);
  }

  public function postAdd(Request $request)
  {
      $auth = Auth::user();

      $validator = Validator::make($request->all(), [
          'activity' => [
              'required',
              'string',
              'max:255',
              Rule::unique('todos')->where(function ($query) use ($auth) {
                  return $query->where('user_id', $auth->id);
              }),
          ],
      ]);

      $validator->after(function ($validator) use ($auth, $request) {
          $input = $request->all(); // Get all data from the request object
          if (Todo::where('activity', $input['activity'])
              ->where('user_id', $auth->id)->exists()) {
              $validator->errors()->add('activity', 'This activity already exists.');
          }
      });

      if ($validator->fails()) {
          return redirect()
              ->route('home')
              ->withErrors($validator)
              ->withInput()
              ->with('alertType', 'danger')
              ->with('alertMessage', 'Aktivitas sudah ditambahkan sebelumnya.');
      }

      Todo::create([
          "user_id" => $auth->id,
          "activity" => $request->activity,
      ]);

      return redirect()->route("home")
          ->with('alertType', 'success')
          ->with('alertMessage', 'Todo item added successfully.');
  }



  public function postEdit(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'id' => 'required|exists:todos',
          'activity' => 'required|string|max:255',
          'status' => 'required|boolean',
      ]);

      if ($validator->fails()) {
          return redirect()
              ->route('home')
              ->withErrors($validator)
              ->withInput();
      }

      $auth = Auth::user();
      $existingTodo = Todo::where('user_id', $auth->id)
          ->where('activity', $request->activity)
          ->where('id', '!=', $request->id)
          ->first();

      if ($existingTodo) {
          // Menampilkan pesan error jika aktivitas sudah ada
          return redirect()
              ->route('home')
              ->withInput()
              ->with('alertType', 'danger')
              ->with('alertMessage', 'Aktivitas ini sudah ada sebelumnya.');
      }

      $todo = Todo::where("id", $request->id)->where("user_id", $auth->id)->first();
      if ($todo) {
          $todo->activity = $request->activity;
          $todo->status = $request->status;
          $todo->save();
      }

      return redirect()->route("home")
          ->with('alertType', 'success')
          ->with('alertMessage', 'Todo item edited successfully.');
  }

  public function postDelete(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:todos',
    ]);

    if ($validator->fails()) {
      return redirect()
        ->route('home')
        ->withErrors($validator)
        ->withInput();
    }

    $auth = Auth::user();

    $todo = Todo::where("id", $request->id)->where("user_id", $auth->id)->first();
    if ($todo) {
      $todo->delete();
    }

    return redirect()->route("home")
      ->with('alertType', 'success')
      ->with('alertMessage', 'Todo item deleted successfully.');
  }
}
