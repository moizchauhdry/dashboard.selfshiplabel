<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ShippingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);

        return Inertia::render('Project/Index', [
            'projects' => $projects,
        ]);
    }

    public function markup($project_id)
    {
        $shipping_services = ShippingService::where('project_id', $project_id)->get();

        return Inertia::render('Project/Markup', [
            'shipping_services' => $shipping_services,
            'project_id' => $project_id,
        ]);
    }

    public function updateMarkup(Request $request)
    {
        $services = $request->input('shipping_services');
        $project_id = $request->input('project_id');

        foreach ($services as $service) {
            $shipping_service = ShippingService::find($service['id']);
            $shipping_service->update([
                'markup_percentage' => $service['markup_percentage'],
                'project_id' => $project_id,
            ]);
        }

        return redirect()->back()->with('success', 'The markup percentage update successfully.');
    }
}
