<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $organizations = Organization::all();
        return response()->json(['success' => true, 'data' => $organizations]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, ['name' => 'required']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }

        $organization = Organization::create($input);
        return response()->json(['success' => true, 'data' => $organization]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $organization = Organization::find($id);

        if (is_null($organization)) {
            return response()->json(['success' => false, 'message' => 'organization not found.']);
        }

        return response()->json(['success' => true, 'data' => $organization]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()]);
        }
        $organization = Organization::find($id);
        if (is_null($organization)) {
            return response()->json(['success' => false, 'message' => 'Organization not found.']);
        }
        $organization->name = $input['name'];
        $organization->save();

        return response()->json(['success' => true, 'message' => 'Organization updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $organization = Organization::find($id);
        if (is_null($organization)) {
            return response()->json(['success' => false, 'message' => 'Organization not found.']);
        }

        $organization->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }
}
