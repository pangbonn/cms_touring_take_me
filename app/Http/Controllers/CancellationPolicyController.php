<?php

namespace App\Http\Controllers;

use App\Models\CancellationPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CancellationPolicyController extends Controller
{
    /**
     * Display a listing of cancellation policies
     */
    public function index()
    {
        $policies = CancellationPolicy::with('creator')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $defaultConditions = CancellationPolicy::getDefaultCancellationConditions();
        $forceMajeureTemplate = CancellationPolicy::getForceMajeureConditionsTemplate();
        $commonLocations = CancellationPolicy::getCommonLocations();
        
        return view('cancellation-policies.index', compact('policies', 'defaultConditions', 'forceMajeureTemplate', 'commonLocations'));
    }

    /**
     * Show the form for creating a new cancellation policy
     */
    public function create()
    {
        $defaultConditions = CancellationPolicy::getDefaultCancellationConditions();
        $forceMajeureTemplate = CancellationPolicy::getForceMajeureConditionsTemplate();
        $commonLocations = CancellationPolicy::getCommonLocations();
        
        return view('cancellation-policies.create', compact('defaultConditions', 'forceMajeureTemplate', 'commonLocations'));
    }

    /**
     * Store a newly created cancellation policy
     */
    public function store(Request $request)
    {
        $request->validate([
            'policy_name' => 'required|string|max:255',
            'policy_description' => 'nullable|string|max:1000',
            'policy_type' => 'required|in:standard,force_majeure,location_specific',
            'cancellation_conditions' => 'required|array',
            'cancellation_conditions.*.days_before' => 'required|integer|min:0',
            'cancellation_conditions.*.refund_percentage' => 'required|integer|min:0|max:100',
            'cancellation_conditions.*.description' => 'required|string|max:255',
            'force_majeure_conditions' => 'nullable|string|max:2000',
            'applicable_locations' => 'nullable|array',
            'applicable_locations.*' => 'string|max:255',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'priority' => 'integer|min:0|max:999',
        ]);

        // If this is set as default, remove default from other policies
        if ($request->is_default) {
            CancellationPolicy::where('is_default', true)->update(['is_default' => false]);
        }

        $policy = CancellationPolicy::create([
            'policy_name' => $request->policy_name,
            'policy_description' => $request->policy_description,
            'policy_type' => $request->policy_type,
            'cancellation_conditions' => $request->cancellation_conditions,
            'force_majeure_conditions' => $request->force_majeure_conditions,
            'applicable_locations' => $request->applicable_locations,
            'is_active' => $request->boolean('is_active', true),
            'is_default' => $request->boolean('is_default', false),
            'priority' => $request->priority ?? 0,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'สร้างนโยบายการยกเลิกเรียบร้อยแล้ว',
            'redirect' => route('cancellation-policies.index')
        ]);
    }

    /**
     * Display the specified cancellation policy
     */
    public function show(CancellationPolicy $cancellationPolicy)
    {
        $cancellationPolicy->load('creator');
        return view('cancellation-policies.show', compact('cancellationPolicy'));
    }

    /**
     * Show the form for editing the specified cancellation policy
     */
    public function edit(CancellationPolicy $cancellationPolicy)
    {
        $defaultConditions = CancellationPolicy::getDefaultCancellationConditions();
        $forceMajeureTemplate = CancellationPolicy::getForceMajeureConditionsTemplate();
        $commonLocations = CancellationPolicy::getCommonLocations();
        
        return view('cancellation-policies.edit', compact('cancellationPolicy', 'defaultConditions', 'forceMajeureTemplate', 'commonLocations'));
    }

    /**
     * Update the specified cancellation policy
     */
    public function update(Request $request, CancellationPolicy $cancellationPolicy)
    {
        try {
            $request->validate([
                'policy_name' => 'required|string|max:255',
                'policy_description' => 'nullable|string|max:1000',
                'policy_type' => 'required|in:standard,force_majeure,location_specific',
                'cancellation_conditions' => 'required|array',
                'cancellation_conditions.*.days_before' => 'required|integer|min:0',
                'cancellation_conditions.*.refund_percentage' => 'required|integer|min:0|max:100',
                'cancellation_conditions.*.description' => 'required|string|max:255',
                'force_majeure_conditions' => 'nullable|string|max:2000',
                'applicable_locations' => 'nullable|array',
                'applicable_locations.*' => 'string|max:255',
                'is_active' => 'boolean',
                'is_default' => 'boolean',
                'priority' => 'integer|min:0|max:999',
            ]);

            // If this is set as default, remove default from other policies
            if ($request->is_default && !$cancellationPolicy->is_default) {
                CancellationPolicy::where('is_default', true)->update(['is_default' => false]);
            }

            $cancellationPolicy->update([
                'policy_name' => $request->policy_name,
                'policy_description' => $request->policy_description,
                'policy_type' => $request->policy_type,
                'cancellation_conditions' => $request->cancellation_conditions,
                'force_majeure_conditions' => $request->force_majeure_conditions,
                'applicable_locations' => $request->applicable_locations,
                'is_active' => $request->boolean('is_active', true),
                'is_default' => $request->boolean('is_default', false),
                'priority' => $request->priority ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตนโยบายการยกเลิกเรียบร้อยแล้ว'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified cancellation policy
     */
    public function destroy(CancellationPolicy $cancellationPolicy)
    {
        try {
            // Prevent deletion of default policy
            if ($cancellationPolicy->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถลบนโยบายเริ่มต้นได้'
                ], 400);
            }

            $cancellationPolicy->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบนโยบายการยกเลิกเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle policy status (active/inactive)
     */
    public function toggleStatus(CancellationPolicy $cancellationPolicy)
    {
        try {
            // Prevent deactivating default policy
            if ($cancellationPolicy->is_default && $cancellationPolicy->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถปิดใช้นโยบายเริ่มต้นได้'
                ], 400);
            }

            $cancellationPolicy->update(['is_active' => !$cancellationPolicy->is_active]);
            $message = $cancellationPolicy->is_active ? 'เปิดใช้นโยบายเรียบร้อยแล้ว' : 'ปิดใช้นโยบายเรียบร้อยแล้ว';

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $cancellationPolicy->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set as default policy
     */
    public function setDefault(CancellationPolicy $cancellationPolicy)
    {
        try {
            // Remove default from other policies
            CancellationPolicy::where('is_default', true)->update(['is_default' => false]);
            
            // Set this policy as default
            $cancellationPolicy->update(['is_default' => true, 'is_active' => true]);

            return response()->json([
                'success' => true,
                'message' => 'ตั้งเป็นนโยบายเริ่มต้นเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get policy details for API
     */
    public function getPolicyDetails(CancellationPolicy $cancellationPolicy)
    {
        return response()->json([
            'success' => true,
            'policy' => $cancellationPolicy->load('creator')
        ]);
    }

    /**
     * Get active policies for selection
     */
    public function getActivePolicies()
    {
        $policies = CancellationPolicy::active()
            ->orderBy('priority', 'desc')
            ->orderBy('policy_name')
            ->get(['id', 'policy_name', 'policy_type', 'is_default']);

        return response()->json([
            'success' => true,
            'policies' => $policies
        ]);
    }
}
