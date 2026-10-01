<?php

namespace App\Actions;

use App\Models\User;

class PromoteStudents
{
    public function __invoke(): int
    {
        $promoted = 0;
        $promotionYear = now()->year;

        User::query()
            ->where('approved', true)
            ->where('role_type', 'Ученик')
            ->whereNotNull('role_details')
            ->where(function ($query) use ($promotionYear) {
                $query->whereNull('student_promotion_year')
                    ->orWhere('student_promotion_year', '<', $promotionYear);
            })
            ->orderBy('id')
            ->chunkById(100, function ($students) use (&$promoted, $promotionYear) {
                foreach ($students as $student) {
                    if (! preg_match('/^(IV|III|II|I)-?([1-4])$/', $student->role_details, $matches)) {
                        continue;
                    }

                    if ($matches[1] === 'IV') {
                        $student->role_type = User::FORMER_STUDENT;
                        $student->role_details = null;
                    } else {
                        $nextGrade = ['I' => 'II', 'II' => 'III', 'III' => 'IV'][$matches[1]];
                        $student->role_details = $nextGrade . '-' . $matches[2];
                    }

                    $student->student_promotion_year = $promotionYear;
                    $student->save();
                    $promoted++;
                }
            });

        return $promoted;
    }
}