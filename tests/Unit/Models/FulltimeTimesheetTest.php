<?php

use App\Models\FulltimeTimesheet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('FulltimeTimesheet Model', function () {
    it('can create a fulltime timesheet with required fields', function () {
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'John Doe',
            'designation' => 'Senior Instructor',
            'prov_abr' => 'ABC123',
            'department' => 'CS',
            'days' => json_encode(['Monday', 'Tuesday', 'Wednesday']),
            'details' => 'Teaching advanced programming courses',
            'total_hour' => 40,
            'rate_per_hour' => 35.50,
            'deduction' => 100.00,
            'total_honorarium' => 1320.00,
        ]);

        expect($timesheet)->toBeInstanceOf(FulltimeTimesheet::class)
            ->and($timesheet->employee_name)->toBe('John Doe')
            ->and($timesheet->designation)->toBe('Senior Instructor')
            ->and($timesheet->prov_abr)->toBe('ABC123')
            ->and($timesheet->department)->toBe('CS')
            ->and($timesheet->details)->toBe('Teaching advanced programming courses')
            ->and($timesheet->total_hour)->toBe(40)
            ->and($timesheet->rate_per_hour)->toBe(35.50)
            ->and($timesheet->deduction)->toBe(100.00)
            ->and($timesheet->total_honorarium)->toBe(1320.00);
    });

    it('casts days as array', function () {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'Jane Smith',
            'designation' => 'Instructor',
            'prov_abr' => 'DEF456',
            'department' => 'MATH',
            'days' => $days,
            'details' => 'Teaching mathematics',
            'total_hour' => 35,
            'rate_per_hour' => 30.00,
            'deduction' => 50.00,
            'total_honorarium' => 1000.00,
        ]);

        expect($timesheet->days)->toBeArray()
            ->and($timesheet->days)->toBe($days)
            ->and($timesheet->days)->toHaveCount(5);
    });

    it('handles json string for days field', function () {
        $daysJson = json_encode(['Saturday', 'Sunday']);
        
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'Bob Wilson',
            'designation' => 'Weekend Instructor',
            'prov_abr' => 'GHI789',
            'department' => 'ENG',
            'days' => $daysJson,
            'details' => 'Weekend classes',
            'total_hour' => 16,
            'rate_per_hour' => 40.00,
            'deduction' => 0,
            'total_honorarium' => 640.00,
        ]);

        expect($timesheet->days)->toBeArray()
            ->and($timesheet->days)->toBe(['Saturday', 'Sunday'])
            ->and($timesheet->days)->toHaveCount(2);
    });

    it('has correct fillable attributes', function () {
        $timesheet = new FulltimeTimesheet();
        $expected = [
            'employee_name',
            'designation',
            'prov_abr',
            'department',
            'days',
            'details',
            'total_hour',
            'rate_per_hour',
            'deduction',
            'total_honorarium'
        ];
        
        expect($timesheet->getFillable())->toBe($expected);
    });

    it('has correct casts', function () {
        $timesheet = new FulltimeTimesheet();
        $expected = ['days' => 'array'];
        
        expect($timesheet->getCasts())->toMatchArray($expected);
    });

    it('can handle numeric values correctly', function () {
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'Alice Brown',
            'designation' => 'Professor',
            'prov_abr' => 'JKL012',
            'department' => 'PHY',
            'days' => ['Monday', 'Wednesday', 'Friday'],
            'details' => 'Physics lectures',
            'total_hour' => 24,
            'rate_per_hour' => 45.75,
            'deduction' => 125.50,
            'total_honorarium' => 972.50,
        ]);

        expect($timesheet->total_hour)->toBeInt()
            ->and($timesheet->rate_per_hour)->toBeFloat()
            ->and($timesheet->deduction)->toBeFloat()
            ->and($timesheet->total_honorarium)->toBeFloat();
    });

    it('can store empty days array', function () {
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'Charlie Davis',
            'designation' => 'Substitute',
            'prov_abr' => 'MNO345',
            'department' => 'CHEM',
            'days' => [],
            'details' => 'On-call substitute',
            'total_hour' => 0,
            'rate_per_hour' => 25.00,
            'deduction' => 0,
            'total_honorarium' => 0,
        ]);

        expect($timesheet->days)->toBeArray()
            ->and($timesheet->days)->toBeEmpty();
    });

    it('can handle null values in optional fields', function () {
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'David Evans',
            'designation' => 'Lecturer',
            'prov_abr' => 'PQR678',
            'department' => 'BIO',
            'days' => ['Tuesday', 'Thursday'],
            'details' => null,
            'total_hour' => 20,
            'rate_per_hour' => 28.00,
            'deduction' => null,
            'total_honorarium' => 560.00,
        ]);

        expect($timesheet->details)->toBeNull()
            ->and($timesheet->deduction)->toBeNull()
            ->and($timesheet->employee_name)->not->toBeNull();
    });

    it('has timestamps', function () {
        $timesheet = FulltimeTimesheet::create([
            'employee_name' => 'Test Employee',
            'designation' => 'Test Position',
            'prov_abr' => 'TEST123',
            'department' => 'TEST',
            'days' => ['Monday'],
            'details' => 'Test details',
            'total_hour' => 8,
            'rate_per_hour' => 20.00,
            'deduction' => 0,
            'total_honorarium' => 160.00,
        ]);

        expect($timesheet->created_at)->not->toBeNull()
            ->and($timesheet->updated_at)->not->toBeNull();
    });
});