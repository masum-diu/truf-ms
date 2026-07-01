<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Booking;
use App\Models\Turf;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Gulshan', 'slug' => 'gulshan', 'description' => 'Gulshan 1 & 2'],
            ['name' => 'Banani', 'slug' => 'banani', 'description' => 'Banani & Niketan'],
            ['name' => 'Dhanmondi', 'slug' => 'dhanmondi', 'description' => 'Dhanmondi Lake area'],
            ['name' => 'Uttara', 'slug' => 'uttara', 'description' => 'Uttara Sector 1-18'],
            ['name' => 'Mirpur', 'slug' => 'mirpur', 'description' => 'Mirpur 1-14'],
            ['name' => 'Mohammadpur', 'slug' => 'mohammadpur', 'description' => 'Mohammadpur & Shyamoli'],
            ['name' => 'Baridhara', 'slug' => 'baridhara', 'description' => 'Baridhara DOHS'],
            ['name' => 'Bashundhara', 'slug' => 'bashundhara', 'description' => 'Bashundhara R/A'],
            ['name' => 'Motijheel', 'slug' => 'motijheel', 'description' => 'Motijheel & Old Dhaka'],
            ['name' => 'Khilgaon', 'slug' => 'khilgaon', 'description' => 'Khilgaon & Malibagh'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@turfbook.test',
            'phone' => '01700000000',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Karim User',
            'email' => 'user@turfbook.test',
            'phone' => '01700000002',
            'password' => 'password',
            'role' => 'user',
        ]);

        $vendorTurfs = [
            ['vendor' => ['name' => 'Rahim Turf Vendor', 'email' => 'vendor@turfbook.test', 'phone' => '01700000001'], 'area' => 'gulshan', 'name' => 'Gulshan Sports Arena', 'address' => 'Gulshan 2, Road 54', 'price' => 2500, 'size' => '7-a-side'],
            ['vendor' => ['name' => 'Banani Sports', 'email' => 'vendor2@turfbook.test', 'phone' => '01700000003'], 'area' => 'banani', 'name' => 'Banani Football Ground', 'address' => 'Banani, Road 11', 'price' => 2000, 'size' => '7-a-side'],
            ['vendor' => ['name' => 'Dhanmondi Turf Co', 'email' => 'vendor3@turfbook.test', 'phone' => '01700000004'], 'area' => 'dhanmondi', 'name' => 'Dhanmondi Turf Center', 'address' => 'Dhanmondi 27', 'price' => 1800, 'size' => '5-a-side'],
            ['vendor' => ['name' => 'Uttara Sports Hub', 'email' => 'vendor4@turfbook.test', 'phone' => '01700000005'], 'area' => 'uttara', 'name' => 'Uttara Sports Hub', 'address' => 'Uttara Sector 7', 'price' => 1500, 'size' => '7-a-side'],
            ['vendor' => ['name' => 'Mirpur Turf', 'email' => 'vendor5@turfbook.test', 'phone' => '01700000006'], 'area' => 'mirpur', 'name' => 'Mirpur Stadium Turf', 'address' => 'Mirpur 10', 'price' => 1200, 'size' => '7-a-side'],
        ];

        $firstTurf = null;

        foreach ($vendorTurfs as $data) {
            $vendor = User::create([
                ...$data['vendor'],
                'password' => 'password',
                'role' => 'vendor',
            ]);

            $area = Area::where('slug', $data['area'])->first();

            $dayPrice = $data['price'];
            $nightPrice = (int) round($data['price'] * 1.2);
            $offdayPrice = (int) round($data['price'] * 1.35);

            $turf = Turf::create([
                'owner_id' => $vendor->id,
                'area_id' => $area->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'address' => $data['address'],
                'description' => 'Quality turf with modern facilities. Suitable for football, cricket, and other sports.',
                'price_per_hour' => min($dayPrice, $nightPrice, $offdayPrice),
                'day_price' => $dayPrice,
                'night_price' => $nightPrice,
                'offday_price' => $offdayPrice,
                'surface_type' => 'artificial_grass',
                'size' => $data['size'],
                'amenities' => ['Parking', 'Floodlights', 'Dressing Room', 'Water'],
                'open_time' => '06:00',
                'close_time' => '23:00',
                'is_active' => true,
            ]);

            $firstTurf ??= $turf;
        }

        User::create([
            'name' => 'New Vendor (No Turf)',
            'email' => 'vendor-new@turfbook.test',
            'phone' => '01700000007',
            'password' => 'password',
            'role' => 'vendor',
        ]);

        Booking::create([
            'user_id' => $user->id,
            'turf_id' => $firstTurf->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '19:30',
            'total_price' => $firstTurf->slotPrice(now()->addDay(), 'night', 90),
            'status' => 'confirmed',
            'customer_name' => $user->name,
            'customer_phone' => $user->phone,
        ]);
    }
}
