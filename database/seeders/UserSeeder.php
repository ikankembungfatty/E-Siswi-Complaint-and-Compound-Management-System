<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // HEPA Staff
        User::create([
            'name' => 'HEPA Administrator',
            'email' => 'hepa@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'hepa_staff',
            'phone' => '03-12345678',
        ]);

        // Wardens
        User::create([
            'name' => 'Puan Fatimah Warden',
            'email' => 'warden1@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'warden',
            'phone' => '012-3456789',
        ]);

        User::create([
            'name' => 'Puan Aminah Warden',
            'email' => 'warden2@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'warden',
            'phone' => '012-9876543',
        ]);

        // Technicians
        User::create([
            'name' => 'Ahmad Teknisian',
            'email' => 'tech1@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '011-1234567',
            'specialization' => 'Electrical',
            'is_available' => true,
        ]);

        User::create([
            'name' => 'Bakar Plumber',
            'email' => 'tech2@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '011-7654321',
            'specialization' => 'Plumbing',
            'is_available' => true,
        ]);

        User::create([
            'name' => 'Cheng IT Support',
            'email' => 'tech3@uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '011-5555555',
            'specialization' => 'IT',
            'is_available' => true,
        ]);

        // Students
        User::create([
            'name' => 'Nur Aisyah binti Ahmad',
            'email' => 'student1@student.uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'student_id' => 'STU2024001',
            'phone' => '013-1111111',
            'block' => 'A',
            'room' => 'A-201',
            'room_level' => '2',
        ]);

        User::create([
            'name' => 'Siti Nurul binti Hassan',
            'email' => 'student2@student.uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'student_id' => 'STU2024002',
            'phone' => '013-2222222',
            'block' => 'B',
            'room' => 'B-305',
            'room_level' => '3',
        ]);

        User::create([
            'name' => 'Farah Amira binti Osman',
            'email' => 'student3@student.uptm.edu.my',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'student_id' => 'STU2024003',
            'block' => 'C',
            'room' => 'C-102',
            'room_level' => '1',
        ]);
    }
}
