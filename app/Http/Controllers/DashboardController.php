<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee;
use App\Models\FulltimeTimesheet;
use App\Models\ParttimeTimesheet;
use App\Models\StaffTimesheet;
use App\Models\UtilityTimesheet;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::all();
        
        // Get logged-in user's name from session
        $userName = $request->session()->get('user_name', 'Guest');
        
        // Get automated employee statistics
        $stats = $this->getEmployeeStatistics();
        
        return view('admin.dashboard', [
            'employees' => $employees,
            'userName' => $userName,
            'totalEmployees' => $stats['totalEmployees'],
            'totalFulltimeInstructors' => $stats['totalFulltimeInstructors'],
            'totalParttimeInstructors' => $stats['totalParttimeInstructors'],
            'totalStaff' => $stats['totalStaff'],
            'totalUtility' => $stats['totalUtility'],
        ]);
    }
    
    /**
     * Search for employees across all timesheet tables
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $results = collect();
        $queryLower = strtolower($query);
        
        // Check if query matches employee type keywords
        $typeMatches = [];
        if (str_contains('fulltime', $queryLower) || str_contains('full-time', $queryLower) || str_contains('full time', $queryLower)) {
            $typeMatches[] = 'fulltime';
        }
        if (str_contains('parttime', $queryLower) || str_contains('part-time', $queryLower) || str_contains('part time', $queryLower)) {
            $typeMatches[] = 'parttime';
        }
        if (str_contains('staff', $queryLower)) {
            $typeMatches[] = 'staff';
        }
        if (str_contains('utility', $queryLower)) {
            $typeMatches[] = 'utility';
        }
        
        // Search in FulltimeTimesheet (by name or if type matches)
        if (empty($typeMatches) || in_array('fulltime', $typeMatches)) {
            $fulltimeQuery = FulltimeTimesheet::select('employee_name', 'designation')->distinct();
            if (empty($typeMatches)) {
                $fulltimeQuery->where('employee_name', 'LIKE', "%{$query}%");
            }
            $fulltimeEmployees = $fulltimeQuery->get()->map(function ($employee) {
                return [
                    'name' => $employee->employee_name,
                    'type' => 'Full-time Instructor',
                    'designation' => $employee->designation,
                    'route' => route('fulltime.index')
                ];
            });
            $results = $results->concat($fulltimeEmployees);
        }
        
        // Search in ParttimeTimesheet (by name or if type matches)
        if (empty($typeMatches) || in_array('parttime', $typeMatches)) {
            $parttimeQuery = ParttimeTimesheet::select('employee_name', 'designation')->distinct();
            if (empty($typeMatches)) {
                $parttimeQuery->where('employee_name', 'LIKE', "%{$query}%");
            }
            $parttimeEmployees = $parttimeQuery->get()->map(function ($employee) {
                return [
                    'name' => $employee->employee_name,
                    'type' => 'Part-time Instructor',
                    'designation' => $employee->designation,
                    'route' => route('parttime.index')
                ];
            });
            $results = $results->concat($parttimeEmployees);
        }
        
        // Search in StaffTimesheet (by name or if type matches)
        if (empty($typeMatches) || in_array('staff', $typeMatches)) {
            $staffQuery = StaffTimesheet::select('employee_name', 'designation')->distinct();
            if (empty($typeMatches)) {
                $staffQuery->where('employee_name', 'LIKE', "%{$query}%");
            }
            $staffEmployees = $staffQuery->get()->map(function ($employee) {
                return [
                    'name' => $employee->employee_name,
                    'type' => 'Staff',
                    'designation' => $employee->designation,
                    'route' => route('staff.index')
                ];
            });
            $results = $results->concat($staffEmployees);
        }
        
        // Search in UtilityTimesheet (by name or if type matches)
        // Note: Skip utility search if columns don't exist in database
        if ((empty($typeMatches) || in_array('utility', $typeMatches)) && Schema::hasColumn('utility_timesheets', 'employee_name')) {
            $utilityQuery = UtilityTimesheet::select('employee_name', 'designation')->distinct();
            if (empty($typeMatches)) {
                $utilityQuery->where('employee_name', 'LIKE', "%{$query}%");
            }
            $utilityEmployees = $utilityQuery->get()->map(function ($employee) {
                return [
                    'name' => $employee->employee_name,
                    'type' => 'Utility',
                    'designation' => $employee->designation,
                    'route' => route('utility.index')
                ];
            });
            $results = $results->concat($utilityEmployees);
        }
        
        // Search in Employee table (always search by name, regardless of type matches)
        if (empty($typeMatches)) {
            $employees = Employee::where('name', 'LIKE', "%{$query}%")
                ->select('name', 'position')
                ->get()
                ->map(function ($employee) {
                    return [
                        'name' => $employee->name,
                        'type' => $employee->position,
                        'designation' => $employee->position,
                        'route' => route('employees.index')
                    ];
                });
            $results = $results->concat($employees);
        }
        
        // Remove duplicates, exclude specific entries, and limit results
        $results = $results
            // Exclude "John Smith" from suggestions
            ->reject(function ($item) {
                return isset($item['name']) && strtolower(trim($item['name'])) === 'john smith';
            })
            ->unique('name')
            ->take(15);
        
        return response()->json($results->values());
    }
    
    /**
     * Get comprehensive employee statistics with unique person counts
     *
     * @return array
     */
    private function getEmployeeStatistics()
    {
        // Count unique employees from timesheet tables (these are the actual working employees)
        $totalFulltimeInstructors = $this->countUniqueEmployees(FulltimeTimesheet::class);
        $totalParttimeInstructors = $this->countUniqueEmployees(ParttimeTimesheet::class);
        $totalStaff = $this->countUniqueEmployees(StaffTimesheet::class);
        $totalUtility = $this->countUniqueEmployees(UtilityTimesheet::class);
        
        // Calculate total unique employees across all timesheet tables
        $totalEmployees = $this->calculateTotalUniqueEmployees();
        
        return [
            'totalEmployees' => $totalEmployees,
            'totalFulltimeInstructors' => $totalFulltimeInstructors,
            'totalParttimeInstructors' => $totalParttimeInstructors,
            'totalStaff' => $totalStaff,
            'totalUtility' => $totalUtility,
        ];
    }

    /**
     * Count unique employees in a timesheet table by name (most reliable method)
     *
     * @param string $modelClass
     * @return int
     */
    private function countUniqueEmployees($modelClass)
    {
        try {
            $tableName = (new $modelClass)->getTable();
            
            // Check if the table has employee_name column
            if (!Schema::hasColumn($tableName, 'employee_name')) {
                // If no employee_name column, return 0 (can't count unique employees)
                return 0;
            }
            
            // Count unique employees by name (most reliable approach)
            // This handles cases where same person might have multiple records
            return $modelClass::whereNotNull('employee_name')
                ->where('employee_name', '!=', '')
                ->distinct('employee_name')
                ->count('employee_name');
                
        } catch (\Exception $e) {
            // If there's any error, return 0
            \Log::error("Error counting employees in {$modelClass}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Calculate total unique employees across all timesheet tables
     * Uses employee names to avoid double counting across different timesheet types
     *
     * @return int
     */
    private function calculateTotalUniqueEmployees()
    {
        try {
            $allEmployeeNames = collect();
            
            // Collect all unique employee names from all timesheet tables
            $tables = [
                FulltimeTimesheet::class,
                ParttimeTimesheet::class,
                StaffTimesheet::class,
                UtilityTimesheet::class
            ];
            
            foreach ($tables as $modelClass) {
                $tableName = (new $modelClass)->getTable();
                
                // Check if employee_name column exists
                if (Schema::hasColumn($tableName, 'employee_name')) {
                    $names = $modelClass::whereNotNull('employee_name')
                        ->where('employee_name', '!=', '')
                        ->distinct()
                        ->pluck('employee_name')
                        ->map(function($name) {
                            return trim(strtolower($name)); // Normalize names for comparison
                        })
                        ->filter(function($name) {
                            return !empty($name);
                        });
                    
                    $allEmployeeNames = $allEmployeeNames->merge($names);
                }
            }
            
            // Return count of unique names (case-insensitive)
            return $allEmployeeNames->unique()->count();
            
        } catch (\Exception $e) {
            // If there's any error, fallback to simple sum (may overcount but safer)
            \Log::error("Error calculating total unique employees: " . $e->getMessage());
            return $this->countUniqueEmployees(FulltimeTimesheet::class) +
                   $this->countUniqueEmployees(ParttimeTimesheet::class) +
                   $this->countUniqueEmployees(StaffTimesheet::class) +
                   $this->countUniqueEmployees(UtilityTimesheet::class);
        }
    }

    /**
     * API endpoint for real-time employee statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeeStats()
    {
        $stats = $this->getEmployeeStatistics();
        return response()->json($stats);
    }

    /**
     * Get detailed employee statistics for debugging
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailedEmployeeStats()
    {
        try {
            $stats = $this->getEmployeeStatistics();
            
            // Add detailed breakdown
            $detailed = [
                'summary' => $stats,
                'breakdown' => [
                    'fulltime_names' => $this->getEmployeeNames(FulltimeTimesheet::class),
                    'parttime_names' => $this->getEmployeeNames(ParttimeTimesheet::class),
                    'staff_names' => $this->getEmployeeNames(StaffTimesheet::class),
                    'utility_names' => $this->getEmployeeNames(UtilityTimesheet::class),
                ],
                'table_info' => [
                    'fulltime_table_exists' => Schema::hasTable('fulltime_timesheets'),
                    'parttime_table_exists' => Schema::hasTable('parttime_timesheets'),
                    'staff_table_exists' => Schema::hasTable('staff_timesheets'),
                    'utility_table_exists' => Schema::hasTable('utility_timesheets'),
                ]
            ];
            
            return response()->json($detailed);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get detailed statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee names from a specific timesheet table
     *
     * @param string $modelClass
     * @return array
     */
    private function getEmployeeNames($modelClass)
    {
        try {
            $tableName = (new $modelClass)->getTable();
            
            if (!Schema::hasColumn($tableName, 'employee_name')) {
                return [];
            }
            
            return $modelClass::whereNotNull('employee_name')
                ->where('employee_name', '!=', '')
                ->distinct()
                ->pluck('employee_name')
                ->sort()
                ->values()
                ->toArray();
                
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get instructors by rate range
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstructorsByRate(Request $request)
    {
        $rateRange = $request->get('rate_range');
        
        try {
            $instructors = collect();
            
            switch($rateRange) {
                case '130-150':
                    // Get fulltime instructors with rates between 130-150
                    $fulltimeInstructors = FulltimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->whereBetween('rate_per_hour', [130, 150])
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Full-time Instructor'
                            ];
                        });
                    
                    // Get parttime instructors with rates between 130-150
                    $parttimeInstructors = ParttimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->whereBetween('rate_per_hour', [130, 150])
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Part-time Instructor'
                            ];
                        });
                    
                    $instructors = $fulltimeInstructors->concat($parttimeInstructors);
                    break;
                    
                case '170-210':
                    // Get fulltime instructors with rates between 170-210
                    $fulltimeInstructors = FulltimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->whereBetween('rate_per_hour', [170, 210])
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Full-time Instructor'
                            ];
                        });
                    
                    // Get parttime instructors with rates between 170-210
                    $parttimeInstructors = ParttimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->whereBetween('rate_per_hour', [170, 210])
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Part-time Instructor'
                            ];
                        });
                    
                    $instructors = $fulltimeInstructors->concat($parttimeInstructors);
                    break;
                    
                case '220':
                    // Get instructors with rate exactly 220
                    $fulltimeInstructors = FulltimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->where('rate_per_hour', 220)
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Full-time Instructor'
                            ];
                        });
                    
                    $parttimeInstructors = ParttimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->where('rate_per_hour', 220)
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Part-time Instructor'
                            ];
                        });
                    
                    $instructors = $fulltimeInstructors->concat($parttimeInstructors);
                    break;
                    
                case '250':
                    // Get instructors with rate exactly 250
                    $fulltimeInstructors = FulltimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->where('rate_per_hour', 250)
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Full-time Instructor'
                            ];
                        });
                    
                    $parttimeInstructors = ParttimeTimesheet::select('employee_name', 'designation', 'rate_per_hour')
                        ->where('rate_per_hour', 250)
                        ->distinct('employee_name')
                        ->get()
                        ->map(function($instructor) {
                            return [
                                'name' => $instructor->employee_name,
                                'designation' => $instructor->designation,
                                'rate' => $instructor->rate_per_hour,
                                'type' => 'Part-time Instructor'
                            ];
                        });
                    
                    $instructors = $fulltimeInstructors->concat($parttimeInstructors);
                    break;
                    
                default:
                    return response()->json([
                        'error' => 'Invalid rate range',
                        'instructors' => []
                    ], 400);
            }
            
            // Remove duplicates based on employee name and sort by name
            $uniqueInstructors = $instructors->unique('name')->sortBy('name')->values();
            
            return response()->json([
                'rate_range' => $rateRange,
                'count' => $uniqueInstructors->count(),
                'instructors' => $uniqueInstructors
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch instructors by rate',
                'message' => $e->getMessage(),
                'instructors' => []
            ], 500);
        }
    }

    /**
     * Show master list for all employees including instructors by department
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function masterList(Request $request)
    {
        $selectedDepartment = $request->get('department', 'all');
        
        $employees = collect();
        
        // Get fulltime instructors by department (only if not filtering for staff/utility)
        if ($selectedDepartment === 'all' || in_array($selectedDepartment, ['BSIT', 'BSBA', 'BSHM', 'EDUCATION'])) {
            $fulltimeQuery = FulltimeTimesheet::query();
            
            if ($selectedDepartment !== 'all') {
                $fulltimeQuery->where('department', $selectedDepartment);
            }
            
            $fulltimeEmployees = $fulltimeQuery->select('id', 'employee_name', 'designation', 'department', 'created_at')
                ->orderBy('department')
                ->orderBy('employee_name')
                ->get()
                ->map(function ($employee) {
                    $employee->type = 'Full-time Instructor';
                    $employee->department = $employee->department ?? 'N/A';
                    return $employee;
                });
            
            $employees = $employees->concat($fulltimeEmployees);
        }
        
        // Get parttime instructors by department (only if not filtering for staff/utility)
        if ($selectedDepartment === 'all' || in_array($selectedDepartment, ['BSIT', 'BSBA', 'BSHM', 'EDUCATION'])) {
            $parttimeQuery = ParttimeTimesheet::query();
            
            if ($selectedDepartment !== 'all') {
                $parttimeQuery->where('department', $selectedDepartment);
            }
            
            $parttimeEmployees = $parttimeQuery->select('id', 'employee_name', 'designation', 'department', 'created_at')
                ->orderBy('department')
                ->orderBy('employee_name')
                ->get()
                ->map(function ($employee) {
                    $employee->type = 'Part-time Instructor';
                    $employee->department = $employee->department ?? 'N/A';
                    return $employee;
                });
            
            $employees = $employees->concat($parttimeEmployees);
        }
        
        // Get staff employees (only if filtering for all or staff)
        if ($selectedDepartment === 'all' || $selectedDepartment === 'staff') {
            $staffEmployees = StaffTimesheet::select('id', 'employee_name', 'designation', 'created_at')
                ->orderBy('employee_name')
                ->get()
                ->map(function ($employee) {
                    $employee->type = 'Staff';
                    $employee->department = null; // Staff don't have departments
                    return $employee;
                });
            
            $employees = $employees->concat($staffEmployees);
        }
        
        // Get utility employees (only if filtering for all or utility)
        if ($selectedDepartment === 'all' || $selectedDepartment === 'utility') {
            $utilityEmployees = UtilityTimesheet::select('id', 'employee_name', 'designation', 'created_at')
                ->orderBy('employee_name')
                ->get()
                ->map(function ($employee) {
                    $employee->type = 'Utility';
                    $employee->department = null; // Utility don't have departments
                    return $employee;
                });
            
            $employees = $employees->concat($utilityEmployees);
        }
        
        // Sort employees: first by department (instructors), then by name
        $employees = $employees->sortBy([
            ['department', 'asc'],
            ['employee_name', 'asc']
        ]);
        
        // Get available departments for filter
        $departments = ['BSIT', 'BSBA', 'BSHM', 'EDUCATION'];
        
        return view('admin.master-list', compact('employees', 'selectedDepartment', 'departments'));
    }

    /**
     * Delete selected employees from master list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSelected(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
            'type' => 'required|in:staff,utility,fulltime,parttime'
        ]);

        try {
            $ids = $request->input('ids');
            $type = $request->input('type');
            $deletedCount = 0;

            switch ($type) {
                case 'staff':
                    $deletedCount = StaffTimesheet::whereIn('id', $ids)->delete();
                    break;
                case 'utility':
                    $deletedCount = UtilityTimesheet::whereIn('id', $ids)->delete();
                    break;
                case 'fulltime':
                    $deletedCount = FulltimeTimesheet::whereIn('id', $ids)->delete();
                    break;
                case 'parttime':
                    $deletedCount = ParttimeTimesheet::whereIn('id', $ids)->delete();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} {$type} record(s).",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting records: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get print data for selected employees
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrintData(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
            'type' => 'required|in:staff,utility,fulltime,parttime'
        ]);

        try {
            $ids = $request->input('ids');
            $type = $request->input('type');
            $employees = collect();

            switch ($type) {
                case 'staff':
                    $employees = StaffTimesheet::whereIn('id', $ids)
                        ->orderBy('employee_name')
                        ->get()
                        ->map(function ($employee) {
                            $employee->type = 'Staff';
                            $employee->department = null; // Staff don't have departments
                            // Normalize the rate and days fields for consistency
                            $employee->rate_per_day = $employee->rate_per_hour ?? 0;
                            $employee->total_days = $employee->total_hour ?? 0;
                            return $employee;
                        });
                    break;
                case 'utility':
                    $employees = UtilityTimesheet::whereIn('id', $ids)
                        ->orderBy('employee_name')
                        ->get()
                        ->map(function ($employee) {
                            $employee->type = 'Utility';
                            $employee->department = null; // Utility don't have departments
                            return $employee;
                        });
                    break;
                case 'fulltime':
                    $employees = FulltimeTimesheet::whereIn('id', $ids)
                        ->orderBy('department')
                        ->orderBy('employee_name')
                        ->get()
                        ->map(function ($employee) {
                            $employee->type = 'Full-time Instructor';
                            $employee->department = $employee->department ?? 'N/A';
                            // Normalize the rate and days fields for consistency
                            $employee->rate_per_day = $employee->rate_per_hour ?? 0;
                            $employee->total_days = $employee->total_hour ?? 0;
                            return $employee;
                        });
                    break;
                case 'parttime':
                    $employees = ParttimeTimesheet::whereIn('id', $ids)
                        ->orderBy('department')
                        ->orderBy('employee_name')
                        ->get()
                        ->map(function ($employee) {
                            $employee->type = 'Part-time Instructor';
                            $employee->department = $employee->department ?? 'N/A';
                            // Normalize the rate and days fields for consistency
                            $employee->rate_per_day = $employee->rate_per_hour ?? 0;
                            $employee->total_days = $employee->total_hour ?? 0;
                            return $employee;
                        });
                    break;
            }

            return response()->json([
                'success' => true,
                'employees' => $employees,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting print data: ' . $e->getMessage()
            ], 500);
        }
    }
}