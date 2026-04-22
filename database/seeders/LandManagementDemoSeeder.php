<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Arazi;
use App\Models\AraziDocument;
use App\Models\Customer;
use App\Models\CustomerBondPayment;
use App\Models\Investor;
use App\Models\Kisan;
use App\Models\KisanBond;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Plot;
use App\Models\Registry;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandManagementDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Payment::truncate();
        CustomerBondPayment::truncate();
        KisanBond::truncate();
        Registry::truncate();
        AraziDocument::truncate();
        Plot::truncate();
        Arazi::truncate();
        Customer::truncate();
        Agent::truncate();
        Kisan::truncate();
        Investor::truncate();
        Partner::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::transaction(function () {
            $kisans = $this->seedKisansAndArazis();
            $agents = $this->seedAgents();
            $customers = $this->seedCustomers();

            $this->seedRegistriesAndPayments($kisans, $agents, $customers);
            $this->seedKisanPayments($kisans);
            $this->seedKisanBonds();
            $this->seedCustomerBondPayments();
            $this->seedInvestors();
            $this->seedPartners();
        });
    }

    private function seedKisansAndArazis(): Collection
    {
        $kisans = collect();
        $plotSequence = 1001;

        foreach (range(1, 20) as $i) {
            $kisan = Kisan::create([
                'name' => fake()->name(),
                'mobile' => '98' . fake()->unique()->numerify('########'),
                'address' => fake()->address(),
            ]);

            $kisans->push($kisan);

            $araziCount = fake()->numberBetween(1, 3);
            foreach (range(1, $araziCount) as $j) {
                $size = fake()->randomFloat(2, 0.25, 12.50);

                $arazi = Arazi::create([
                    'legacy_arazi_code' => (string) fake()->randomElement(['152', '375KA', '174MI', '2011', '1413', '419']),
                    'kisan_id' => $kisan->id,
                    'location' => fake()->city() . ', ' . fake()->state(),
                    'plot_number' => 'PLT-' . $plotSequence,
                    'block' => fake()->randomElement(['A', 'B', 'C', 'D', 'E', 'F']),
                    'plot_type' => fake()->randomElement(['residential', 'commercial']),
                    'size' => $size,
                    'coordinates' => fake()->latitude() . ',' . fake()->longitude(),
                    'status' => 'available',
                ]);

                Plot::create([
                    'arazi_id' => $arazi->id,
                    'title' => 'Plot ' . $plotSequence,
                    'coordinates' => $arazi->coordinates,
                    'latitude' => fake()->latitude(22.0, 29.0),
                    'longitude' => fake()->longitude(74.0, 83.0),
                    'description' => fake()->sentence(),
                ]);

                if (fake()->boolean(65)) {
                    $ext = fake()->randomElement(['pdf', 'jpg', 'png']);
                    AraziDocument::create([
                        'arazi_id' => $arazi->id,
                        'document_name' => fake()->randomElement(['Khasra', 'Khatauni', 'Registry Copy', 'Mutation']) . ' ' . $plotSequence,
                        'file_path' => 'arazi-documents/demo-' . Str::lower(Str::random(12)) . '.' . $ext,
                        'mime_type' => $ext === 'pdf' ? 'application/pdf' : 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext),
                        'file_size' => fake()->numberBetween(60_000, 1_800_000),
                        'uploaded_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                }

                $plotSequence++;
            }
        }

        return $kisans;
    }

    private function seedAgents(): Collection
    {
        $agents = collect();

        foreach (range(1, 8) as $i) {
            $sponsor = $i > 1 ? $agents->random() : null;
            $agents->push(Agent::create([
                'form_code' => 'AG' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'name' => fake()->name(),
                'rank_title' => fake()->randomElement(['Diamond', 'Gold', 'Silver', 'Executive']),
                'mobile' => '97' . fake()->unique()->numerify('########'),
                'commission_percentage' => fake()->randomFloat(2, 1.00, 4.50),
                'legacy_percent' => fake()->randomFloat(2, 1.00, 10.00),
                'sponsor_agent_id' => $sponsor?->id,
            ]));
        }

        return $agents;
    }

    private function seedCustomers(): Collection
    {
        $customers = collect();

        foreach (range(1, 30) as $i) {
            $customers->push(Customer::create([
                'legacy_customer_code' => 'CREG' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'name' => fake()->name(),
                'mobile' => '96' . fake()->unique()->numerify('########'),
                'secondary_mobile' => '95' . fake()->unique()->numerify('########'),
                'id_document_no' => strtoupper(fake()->bothify('ID####??##')),
                'address' => fake()->address(),
            ]));
        }

        return $customers;
    }

    private function seedRegistriesAndPayments(Collection $kisans, Collection $agents, Collection $customers): void
    {
        $eligibleArazis = Arazi::query()->inRandomOrder()->take(24)->get();

        foreach ($eligibleArazis as $index => $arazi) {
            $customer = $customers->random();
            $agent = $agents->random();

            $status = fake()->randomElement(['pending', 'completed', 'cancelled']);
            $paymentStatus = match ($status) {
                'completed' => 'completed',
                'cancelled' => fake()->randomElement(['expired', 'partial']),
                default => fake()->randomElement(['pending', 'partial']),
            };

            $registryDate = Carbon::now()->subDays(fake()->numberBetween(3, 90));
            $dueDate = (clone $registryDate)->addDays(15);
            $advance = fake()->randomFloat(2, 15_000, 120_000);

            $registry = Registry::create([
                'registry_code' => 'REG-' . now()->format('ym') . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'customer_reg_no' => 'CREG' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'arazi_id' => $arazi->id,
                'agent_id' => $agent->id,
                'check_by_agent_id' => $agents->random()->id,
                'registry_date' => $registryDate->toDateString(),
                'booking_mode' => fake()->randomElement(['cash', 'emi', 'mixed']),
                'land_size' => $arazi->size,
                'registry_amount' => fake()->randomFloat(2, 120000, 650000),
                'payment_words' => 'Amount received as per agreement',
                'id_card_no' => $customer->id_document_no,
                'witness_name' => fake()->name(),
                'nominee_name' => fake()->name(),
                'broker_commission' => $agent->commission_percentage,
                'advance_amount' => $advance,
                'installment_amount' => fake()->randomFloat(2, 5000, 30000),
                'down_payment' => $advance,
                'due_date' => $dueDate->toDateString(),
                'expected_registry_date' => $dueDate->copy()->addDays(fake()->numberBetween(5, 20))->toDateString(),
                'status' => $status,
                'payment_status' => $paymentStatus,
                'lock_status' => $status === 'cancelled' ? 'unlock' : 'lock',
            ]);

            if (in_array($status, ['pending', 'completed'], true)) {
                $arazi->update(['status' => 'sold']);
            } else {
                $arazi->update(['status' => 'available']);
            }

            Payment::create([
                'reference_no' => 'PAY-ADV-' . strtoupper(Str::random(8)),
                'receipt_no' => 'RCPT-' . strtoupper(Str::random(7)),
                'source_table' => 'recipt1',
                'is_legacy' => true,
                'registry_id' => $registry->id,
                'customer_id' => $customer->id,
                'payment_type' => 'advance',
                'amount' => $advance,
                'payment_date' => $registryDate->toDateString(),
                'payment_method' => fake()->randomElement(['cash', 'bank', 'upi']),
                'notes' => 'Advance paid for booking.',
            ]);

            if ($status === 'completed') {
                Payment::create([
                    'reference_no' => 'PAY-FNL-' . strtoupper(Str::random(8)),
                    'receipt_no' => 'RCPT-' . strtoupper(Str::random(7)),
                    'source_table' => 'recipt1',
                    'is_legacy' => true,
                    'registry_id' => $registry->id,
                    'customer_id' => $customer->id,
                    'payment_type' => 'final',
                    'amount' => fake()->randomFloat(2, 70_000, 350_000),
                    'payment_date' => $dueDate->subDays(fake()->numberBetween(0, 8))->toDateString(),
                    'payment_method' => fake()->randomElement(['bank', 'rtgs', 'neft']),
                    'notes' => 'Final settlement completed.',
                ]);
            }
        }
    }

    private function seedKisanPayments(Collection $kisans): void
    {
        foreach (range(1, 18) as $i) {
            $kisan = $kisans->random();

            Payment::create([
                'reference_no' => 'PAY-KSN-' . strtoupper(Str::random(8)),
                'receipt_no' => 'KRCPT-' . strtoupper(Str::random(6)),
                'source_table' => 'kishanrecipt',
                'is_legacy' => true,
                'kisan_id' => $kisan->id,
                'payment_type' => fake()->randomElement(['installment', 'other']),
                'amount' => fake()->randomFloat(2, 8_000, 60_000),
                'payment_date' => fake()->dateTimeBetween('-120 days', 'now')->format('Y-m-d'),
                'payment_method' => fake()->randomElement(['cash', 'bank', 'upi']),
                'notes' => 'Kisan-side payment entry.',
            ]);
        }
    }

    private function seedKisanBonds(): void
    {
        $arazis = Arazi::with('kisan')->inRandomOrder()->take(18)->get();

        foreach ($arazis as $index => $arazi) {
            if (! $arazi->kisan) {
                continue;
            }

            KisanBond::create([
                'kisan_id' => $arazi->kisan_id,
                'arazi_id' => $arazi->id,
                'bond_no' => 'KB-' . now()->format('ym') . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'bond_date' => fake()->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
                'bond_amount' => fake()->randomFloat(2, 50_000, 450_000),
                'witness_name' => fake()->name(),
                'notes' => 'Kisan bond entry generated from demo seeder.',
            ]);
        }
    }

    private function seedCustomerBondPayments(): void
    {
        $registries = Registry::with('customer')->inRandomOrder()->take(22)->get();

        foreach ($registries as $index => $registry) {
            if (! $registry->customer) {
                continue;
            }

            $entries = fake()->numberBetween(1, 3);
            foreach (range(1, $entries) as $step) {
                CustomerBondPayment::create([
                    'registry_id' => $registry->id,
                    'customer_id' => $registry->customer_id,
                    'entry_no' => 'CBP-' . now()->format('ym') . '-' . str_pad((string) (($index * 3) + $step), 5, '0', STR_PAD_LEFT),
                    'entry_date' => fake()->dateTimeBetween('-8 months', 'now')->format('Y-m-d'),
                    'entry_type' => fake()->randomElement(['advance', 'installment', 'final']),
                    'amount' => fake()->randomFloat(2, 5_000, 120_000),
                    'payment_method' => fake()->randomElement(['cash', 'bank', 'upi', 'neft']),
                    'remarks' => 'Customer bond payment demo entry.',
                ]);
            }
        }
    }

    private function seedInvestors(): void
    {
        foreach (range(1, 12) as $i) {
            Investor::create([
                'name' => fake()->name(),
                'mobile' => '94' . fake()->unique()->numerify('########'),
                'address' => fake()->address(),
                'investment_amount' => fake()->randomFloat(2, 100_000, 5_000_000),
                'return_percentage' => fake()->randomFloat(2, 6, 24),
                'invested_on' => fake()->dateTimeBetween('-3 years', '-1 month')->format('Y-m-d'),
                'status' => fake()->randomElement(['active', 'closed']),
            ]);
        }
    }

    private function seedPartners(): void
    {
        foreach (range(1, 10) as $i) {
            Partner::create([
                'name' => fake()->name(),
                'mobile' => '93' . fake()->unique()->numerify('########'),
                'address' => fake()->address(),
                'share_percentage' => fake()->randomFloat(2, 1, 35),
                'capital_amount' => fake()->randomFloat(2, 50_000, 2_500_000),
                'joined_on' => fake()->dateTimeBetween('-4 years', '-2 months')->format('Y-m-d'),
                'status' => fake()->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
