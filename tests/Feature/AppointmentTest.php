<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_book_appointment()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $professional = HealthcareProfessional::factory()->create();

        $response = $this->postJson('/api/appointments', [
            'healthcare_professional_id' => $professional->id,
            'appointment_start_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'appointment_end_time' => now()->addDays(2)->addHour()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(201);
    }

    public function test_user_cannot_cancel_within_24_hours()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $professional = HealthcareProfessional::factory()->create();

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'healthcare_professional_id' => $professional->id,
            'appointment_start_time' => now()->addHours(10),
            'appointment_end_time' => now()->addHours(11),
            'status' => 'booked',
        ]);

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");
        $response->assertStatus(403);
    }

}
