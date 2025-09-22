<?php

namespace App\Http\Controllers;

use App\Models\BookingTerm;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingTermController extends Controller
{
    /**
     * Display a listing of booking terms
     */
    public function index()
    {
        $terms = BookingTerm::with('creator')
            ->ordered()
            ->paginate(10);
            
        $categoryOptions = BookingTerm::getCategoryOptions();
        $defaultTerms = BookingTerm::getDefaultTerms();
        
        return view('booking-terms.index', compact('terms', 'categoryOptions', 'defaultTerms'));
    }

    /**
     * Show the form for creating a new booking term
     */
    public function create()
    {
        $categoryOptions = BookingTerm::getCategoryOptions();
        return view('booking-terms.create', compact('categoryOptions'));
    }

    /**
     * Store a newly created booking term
     */
    public function store(Request $request)
    {
        $request->validate([
            'term_title' => 'required|string|max:255',
            'term_content' => 'required|string|max:2000',
            'term_category' => 'required|in:booking,payment,travel,responsibility,group,seat_selection',
            'sort_order' => 'integer|min:0|max:999',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        $term = BookingTerm::create([
            'term_title' => $request->term_title,
            'term_content' => $request->term_content,
            'term_category' => $request->term_category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'is_required' => $request->boolean('is_required', false),
            'additional_info' => $request->additional_info,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'สร้างเงื่อนไขการจองเรียบร้อยแล้ว',
            'redirect' => route('booking-terms.index')
        ]);
    }

    /**
     * Display the specified booking term
     */
    public function show(BookingTerm $bookingTerm)
    {
        $bookingTerm->load('creator');
        return view('booking-terms.show', compact('bookingTerm'));
    }

    /**
     * Show the form for editing the specified booking term
     */
    public function edit(BookingTerm $bookingTerm)
    {
        $categoryOptions = BookingTerm::getCategoryOptions();
        return view('booking-terms.edit', compact('bookingTerm', 'categoryOptions'));
    }

    /**
     * Update the specified booking term
     */
    public function update(Request $request, BookingTerm $bookingTerm)
    {
        try {
            $request->validate([
                'term_title' => 'required|string|max:255',
                'term_content' => 'required|string|max:2000',
                'term_category' => 'required|in:booking,payment,travel,responsibility,group,seat_selection',
                'sort_order' => 'integer|min:0|max:999',
                'is_active' => 'boolean',
                'is_required' => 'boolean',
                'additional_info' => 'nullable|string|max:1000',
            ]);

            $bookingTerm->update([
                'term_title' => $request->term_title,
                'term_content' => $request->term_content,
                'term_category' => $request->term_category,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'is_required' => $request->boolean('is_required', false),
                'additional_info' => $request->additional_info,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตเงื่อนไขการจองเรียบร้อยแล้ว'
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
     * Remove the specified booking term
     */
    public function destroy(BookingTerm $bookingTerm)
    {
        try {
            $bookingTerm->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบเงื่อนไขการจองเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle term status (active/inactive)
     */
    public function toggleStatus(BookingTerm $bookingTerm)
    {
        try {
            $bookingTerm->update(['is_active' => !$bookingTerm->is_active]);
            $message = $bookingTerm->is_active ? 'เปิดใช้เงื่อนไขเรียบร้อยแล้ว' : 'ปิดใช้เงื่อนไขเรียบร้อยแล้ว';

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $bookingTerm->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle required status
     */
    public function toggleRequired(BookingTerm $bookingTerm)
    {
        try {
            $bookingTerm->update(['is_required' => !$bookingTerm->is_required]);
            $message = $bookingTerm->is_required ? 'ตั้งเป็นเงื่อนไขบังคับเรียบร้อยแล้ว' : 'ยกเลิกเงื่อนไขบังคับเรียบร้อยแล้ว';

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_required' => $bookingTerm->is_required
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get term details for API
     */
    public function getTermDetails(BookingTerm $bookingTerm)
    {
        return response()->json([
            'success' => true,
            'term' => $bookingTerm->load('creator')
        ]);
    }

    /**
     * Get active terms for public display
     */
    public function getActiveTerms()
    {
        $terms = BookingTerm::active()
            ->ordered()
            ->get(['id', 'term_title', 'term_content', 'term_category', 'is_required', 'additional_info']);

        return response()->json([
            'success' => true,
            'terms' => $terms
        ]);
    }

    /**
     * Bulk create default terms
     */
    public function createDefaultTerms()
    {
        try {
            $defaultTerms = BookingTerm::getDefaultTerms();
            $createdCount = 0;

            foreach ($defaultTerms as $termData) {
                // Check if term already exists
                $existingTerm = BookingTerm::where('term_title', $termData['term_title'])
                    ->where('term_content', $termData['term_content'])
                    ->first();

                if (!$existingTerm) {
                    BookingTerm::create([
                        ...$termData,
                        'created_by' => auth()->id(),
                    ]);
                    $createdCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "สร้างเงื่อนไขเริ่มต้นเรียบร้อยแล้ว ({$createdCount} รายการ)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
