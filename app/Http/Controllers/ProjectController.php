<?php

namespace App\Http\Controllers;

use App\Models\Avp;
use App\Models\File;
use App\Models\Quote;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
  public function create($id)
  {
    $customer = Customer::find($id);

    if ($customer->user->id === Auth::user()->id) {
      return view('project.create', compact('customer'));
    }
  }

  public function store(Request $request)
  {
    $project = new Project;
    $value = $request->all();

    $rules = [
      'title' => 'required',
    ];

    $validator = Validator::make($value, $rules, [
      'title.required' => "Merci d'indiquer le titre du projet",
    ]);

    if ($validator->fails()) {
      return Redirect::back()
        ->withErrors($validator)
        ->withInput();
    } else {
      $project->title = $request->title;
      $project->customer_id = $request->customer_id;

      $project->save();

      return redirect()->route('customer.show', $project->customer_id)->with('success', "Le projet a bien été ajouté");
    }
  }

  public function list($id)
  {
    $customer = Customer::find($id);
    $projects = Project::where('customer_id', $id)->get();
    return view('project.list', compact('projects', 'customer'));
  }

  public function show($id)
  {

    $project = Project::find($id);
    $quote = Quote::where('project_id', $project->id)->first();
    $avp = Avp::where('project_id', $project->id)->first();
    return view('project.show', compact('project', 'quote', 'avp'));
  }

  public function delete(Request $request)
  {
    $customer = Customer::find($request->customer_id);
    $project = Project::find($request->project_id);

    if (Auth::user()->id === $customer->user->id) {
      $files = File::where('project_id', $project->id)->get();

      foreach ($files as $file) {
        Storage::delete("documents/$file->direction");
      }
      File::where('project_id', $project->id)->delete();
    }

    if ($project->delete()) {
      return redirect()->back()->with('success', "Le projet a été supprimé avec succès");
    }

    return redirect()->back()->with('error', "une erreur est survenue, suppression impossible");
  }
}
