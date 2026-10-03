<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. User Roles Pivot Table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        // 3. Organizations Table (Foundation for future brokerages, builders, vendors, operators)
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->nullable(); // brokerage, builder, pg_company, coworking_company, vendor_company
            $table->string('city')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Organization Members Pivot Table
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('member'); // owner, admin, member, agent
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['organization_id', 'user_id']);
        });

        // 5. Add current_role to users table if not exists
        if (! Schema::hasColumn('users', 'current_role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('current_role')->nullable()->after('user_type');
            });
        }

        // 6. Seed Default Standard Housing365 Roles
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Property Owner',
                'description' => 'Lists residential or commercial properties for lease or sale with zero listing fee.',
                'is_active' => true,
            ],
            [
                'name' => 'tenant',
                'display_name' => 'Tenant',
                'description' => 'Discovers verified residential rental listings and requests viewings or agreements.',
                'is_active' => true,
            ],
            [
                'name' => 'broker',
                'display_name' => 'Real Estate Broker',
                'description' => 'Licensed real estate agent or broker managing client listings, deals, and mandates.',
                'is_active' => true,
            ],
            [
                'name' => 'builder',
                'display_name' => 'Builder / Developer',
                'description' => 'Real estate development company managing new projects, towers, inventory, and site visits.',
                'is_active' => true,
            ],
            [
                'name' => 'pg_operator',
                'display_name' => 'PG Operator',
                'description' => 'Operates Paying Guest accommodation, managing room sharing, meals, and resident occupancies.',
                'is_active' => true,
            ],
            [
                'name' => 'coliving_operator',
                'display_name' => 'Co-living Operator',
                'description' => 'Operates modern serviced community co-living units with integrated amenities.',
                'is_active' => true,
            ],
            [
                'name' => 'coworking_operator',
                'display_name' => 'Coworking Operator',
                'description' => 'Manages shared desks, dedicated desks, and private cabins for professionals and teams.',
                'is_active' => true,
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Property Service Vendor',
                'description' => 'Verified service provider delivering Housing365 Care repairs, cleaning, and maintenance.',
                'is_active' => true,
            ],
            [
                'name' => 'buyer',
                'display_name' => 'Property Buyer',
                'description' => 'Looking to purchase residential apartments, villas, plots, or commercial assets.',
                'is_active' => true,
            ],
            [
                'name' => 'seller',
                'display_name' => 'Property Seller',
                'description' => 'Owner selling apartments, villas, plots, or commercial properties in India.',
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Platform Administrator',
                'description' => 'Full administrative access to the Housing365 management panel and operations.',
                'is_active' => true,
            ],
        ];

        $now = now();
        foreach ($roles as $roleData) {
            DB::table('roles')->insert(array_merge($roleData, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 7. Safe Migration Strategy for Existing Users (Deterministic & Non-Destructive)
        $roleMap = DB::table('roles')->pluck('id', 'name')->toArray();
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $userRolesToAssign = [];

            // Check if user is admin
            if (str_starts_with($user->email, 'admin@') || $user->user_type === 'admin') {
                if (isset($roleMap['admin'])) {
                    $userRolesToAssign[] = $roleMap['admin'];
                }
            }

            // Check if user matches any properties as seller
            $hasProperties = false;
            if (! empty($user->phone)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                $hasProperties = DB::table('properties')
                    ->whereNotNull('seller_phone')
                    ->where('seller_phone', 'like', "%{$cleanPhone}%")
                    ->exists();
            }
            if (! $hasProperties && ! empty($user->email)) {
                $hasProperties = DB::table('properties')
                    ->where('seller_email', $user->email)
                    ->exists();
            }

            if ($hasProperties || $user->user_type === 'owner') {
                if (isset($roleMap['owner'])) {
                    $userRolesToAssign[] = $roleMap['owner'];
                }
            }

            // Check user_type mapping
            if ($user->user_type === 'buyer') {
                if (isset($roleMap['buyer'])) {
                    $userRolesToAssign[] = $roleMap['buyer'];
                }
                if (isset($roleMap['tenant'])) {
                    $userRolesToAssign[] = $roleMap['tenant'];
                }
            } elseif ($user->user_type === 'broker') {
                if (isset($roleMap['broker'])) {
                    $userRolesToAssign[] = $roleMap['broker'];
                }
            } elseif ($user->user_type === 'builder') {
                if (isset($roleMap['builder'])) {
                    $userRolesToAssign[] = $roleMap['builder'];
                }
            } elseif ($user->user_type === 'operator') {
                if (isset($roleMap['pg_operator'])) {
                    $userRolesToAssign[] = $roleMap['pg_operator'];
                }
                if (isset($roleMap['coliving_operator'])) {
                    $userRolesToAssign[] = $roleMap['coliving_operator'];
                }
            } elseif ($user->user_type === 'vendor') {
                if (isset($roleMap['vendor'])) {
                    $userRolesToAssign[] = $roleMap['vendor'];
                }
            } else {
                // Default tenant persona
                if (isset($roleMap['tenant'])) {
                    $userRolesToAssign[] = $roleMap['tenant'];
                }
            }

            // Deduplicate
            $userRolesToAssign = array_unique($userRolesToAssign);

            foreach ($userRolesToAssign as $roleId) {
                DB::table('user_roles')->insertOrIgnore([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Set current_role to primary role
            $primaryRoleName = 'tenant';
            if (in_array($roleMap['admin'] ?? null, $userRolesToAssign)) {
                $primaryRoleName = 'admin';
            } elseif (in_array($roleMap['owner'] ?? null, $userRolesToAssign)) {
                $primaryRoleName = 'owner';
            } elseif (in_array($roleMap['broker'] ?? null, $userRolesToAssign)) {
                $primaryRoleName = 'broker';
            } elseif (in_array($roleMap['builder'] ?? null, $userRolesToAssign)) {
                $primaryRoleName = 'builder';
            } elseif (in_array($roleMap['buyer'] ?? null, $userRolesToAssign)) {
                $primaryRoleName = 'buyer';
            }

            DB::table('users')->where('id', $user->id)->update([
                'current_role' => $primaryRoleName,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'current_role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('current_role');
            });
        }

        Schema::dropIfExists('organization_members');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }
};
