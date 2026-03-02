<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Decimal;
use Throwable;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Запуск покрокової міграції реєстру');

        DB::beginTransaction();

        try {
            // КРОК 1: Міграція реєстрів
            $this->materials();

            $this->category();

            $this->customer();



            //$this->orders();
            // Фіксуємо зміни
            DB::commit();
            $this->info('✅ Міграція успішно завершена');

            return Command::SUCCESS;

        } catch (Throwable $e) {
            // Відкочуємо транзакцію при помилці
            DB::rollBack();

            // Виводимо повідомлення про помилку
            $this->error('❌ Помилка під час міграції');
            $this->error($e->getMessage());

            // Логуємо помилку
            report($e);

            return Command::FAILURE;
        }

    }

    private function materials()
    {
        $this->info('🔄 Міграція матеріалів');
        // Логіка міграції матеріалів
        $materials = DB::connection('mysql_old')
            ->table('materials')
            ->get();

        foreach ($materials as $material) {
            $this->info('🔄 Міграція матеріалу '.$material->name);
            $existingMaterialId = DB::table('materials')
                ->where('name', $material->name)
                ->orWhere('slug', \Str::slug($material->name))
                ->value('id');

            if (! $existingMaterialId) {
                $this->info('🔄 Запис матеріалу у таблицю '.$material->name);

                $newMaterial = new Material([
                    'name' => $material->name,
                    'slug' => \Str::slug($material->name),
                    'description' => $material->description ?? '',
                    'cost_per_unit' => 0,
                    'unit' => $material->unit ?: 'метри погонні',
                    'stock_quantity' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $newMaterial->save();

                $this->info($newMaterial->id.'✅ Записано у таблицю '.$material->name);

                //  $newMaterialId = DB::table('materials')->insertGetId([
                //     //'old_id'     => $material->id,      // Зберігаємо старий ID
                //     'name'       => $material->name,    // Назва матеріалу
                //     'slug'       => $material->slug = \Str::slug($material->name),    // URL
                //     'description'=> $material->description ?? '', // Опис матеріалу
                //     //'barcode'    => $material->barcode ?: null, // Штрих-код
                //     //'barcode_image' => $material->barcode_image ?: null, // Зображення штрих-коду
                //     //'category_id'=> $material->category_id ?: null, // Приналежність до категорії
                //     'cost_per_unit' => 0, // Вартість за одиницю
                //     'unit'       => $material->unit ?: 'метри погонні', // Одиниця вимірювання
                //     'stock_quantity' => 0, // Кількість на складі
                //     //'supplier_id' => $material->supplier_id ?: null, // Постачальник
                //     //'photo_path' => $material->photo_path ?: null, // Фото
                //     'created_at'=> now(),
                //     'updated_at'=> now(),
                // ]);
            }
        }
    }

    private function customer()
    {
        $users = DB::connection('mysql_old')
            ->table('users')
            ->get();

         foreach ($users as $user) {
             $this->info('🔄 Міграція користувача '.$user->name);
                $existingUserId = DB::table('users')
                    ->where('name', $user->name)
                    // ->orWhere('slug', \Str::slug($user->name))
                    ->value('id');
                if (! $existingUserId) {
                    $newUser = DB::table('users')->insertGetId([
                        'name' => $user->name,
                        'email' => $user->email ?? null,
                        'password' => $user->password ?? null,
                        'role' => 'admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->info('✅ Міграція користувача '.$user->name.' завершена');
                }
         }
        $this->info('🔄 Міграція клієнтів');
        // Логіка міграції клієнтів
        $customers = DB::connection('mysql_old')
            ->table('customers')
            //->limit(100) // Додайте обмеження, якщо потрібно
            ->get();

        foreach ($customers as $customer) {
            $this->info('🔄 Міграція клієнта '.$customer->name);
            $existingCustomerId = DB::table('users')
                ->where('name', $customer->name)
                // ->orWhere('slug', \Str::slug($customer->name))
                ->value('id');
            if (! $existingCustomerId) {
                $user = new User([
                    'name' => $customer->name,
                ]);
                $user->save();

                $profile = new \App\Models\CustomerProfile([
                    'user_id' => $user->id,
                    'phone' => $customer->phone ?? null,
                    'address' => $customer->address ?? null,
                    // 'date_of_birth' => $customer->date_of_birth ?? null,
                    'city' => $customer->city ?? null,
                    'note' => $customer->description ?? null,
                    // 'measurements' => json_decode($customer->measurements ?? '[]', true),
                ]);
                $profile->save();
                $this->info('✅ Міграція клієнта '.$customer->name.' завершена');

                $this->orders($customer, $user);
            }
        }
    }

    private function category()
    {
        $this->info('🔄 Міграція категорій');
        // Логіка міграції категорій
        $categories = DB::connection('mysql_old')
            ->table('categories')
            ->get();

        foreach ($categories as $category) {
            $this->info('🔄 Міграція категорії '.$category->name);
            // Логіка міграції кожної категорії
            // ...

            $existingCategoryId = DB::table('categories')
                ->where('slug', \Str::slug($category->name))
                ->value('id');

            if (! $existingCategoryId) {
                $this->info('🔄 Запис категорії у таблицю '.$category->name);
                $newCategoryId = DB::table('categories')->insertGetId([
                    'name' => $category->name,
                    'slug' => \Str::slug($category->name),
                    'description' => $category->description ?? '',
                    'parent_id' => null, // Логіка визначення батьківської категорії, якщо потрібно
                    'type' => 'material', // Встановіть тип категорії, якщо потрібно
                    'is_site' => 0, // Встановіть значення для is_site, якщо потрібно
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->info('✅ Міграція категорії '.$category->name.' завершена');
        }
    }

    private function orders($customer, $newCustomer)
    {
        $this->info('🔄 Міграція замовлень');
        // Логіка міграції замовлень


        $orders = DB::connection('mysql_old')
            ->table('productions')
            ->where('customer_id', $customer->id)
            // ->with(['production_materials', 'production_stages','customers'])
            ->get();

            if(!$orders->isEmpty()) {


        $newOrder = new \App\Models\Order([
                'customer_id' => $newCustomer->id,
                'total_amount' => $order->price ?? 0,
                'discount_amount' => 0,
                'paid_amount' => $order->pay ?? 0,
                'due_amount' => ($order->price ?? 0) - ($order->pay ?? 0),
               // 'deadline' => $order->production_date ? \Carbon\Carbon::parse($order->production_date) : null,
                'shipping_method' => 'самовивіз',
                'notes' => $order->description ?? null,
                //'delivery' => 0,
                'status' => $order->status ?? 'автоматично створено',
            ]);
            $newOrder->save();

        foreach ($orders as $order) {
            $this->info('🔄 Міграція замовлення '.$order->name);

            // $existingOrderId = DB::table('orders')
            //     ->where('name', $order->name)
            //     ->value('id');

            // if ($existingOrderId) {
            //     $this->info('⚠️ Замовлення '.$order->name.' вже існує, пропускаємо міграцію');
            //     continue;
            // }
            // $customerId = DB::table('users')
            //     ->where('name', $customer->name ?? null)
            //     ->value('id');

            $newProduction = new \App\Models\Production([
                'name' => $order->name,
                'description' => $order->description ?? null,
                'quantity' => $order->quantity ?? 1,
                'status' => $order->status ?? 'автоматично створено',
                'order_id' => $newOrder->id,
                'product_id' => null,
                'is_template' => 0,
                'mark_up' => 2000, // Встановіть націнку за замовчуванням, якщо потрібно
            ]);

            $this->info('⚠️ Замовлення '.$order->name.' створено, ID: '.$newProduction->id);
            $newProduction->save();


            $productionMaterials = DB::connection('mysql_old')
                ->table('production_materials')
                ->where('production_id', $order->id)
                ->get();

            // Migrate order items


                foreach ($productionMaterials as $material) {
                    $this->info('🔄++++++ Міграція матеріалу '.$material->id.' для замовлення '.$order->id);
                    $materialInfo = DB::connection('mysql_old')
                        ->table('materials')
                        ->where('id', $material->material_id)
                        ->first();

                        // Знаходимо відповідний матеріал у новій базі даних за назвою
                    $newMaterialId = DB::table('materials')
                        ->where('name', $materialInfo->name)
                        ->value('id');

                        if (! $newMaterialId) {
                            $materialAdd = new Material([
                                'name' => $materialInfo->name,
                                'slug' => \Str::slug($materialInfo->name.'-'.$materialInfo->id),
                                'description' => $materialInfo->description ?? '',
                                'cost_per_unit' => $material->price ?? 0,
                                'unit' => $materialInfo->unit ?: 'метри погонні',
                                'stock_quantity' => $material->quantity ?? 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $materialAdd->save();
                            $newMaterialId = $materialAdd->id;
                            $this->info('🔄++++++ Матеріал '.$materialInfo->name.' додано у нову базу даних, ID: '.$newMaterialId);
                        }

                    $material = Material::find($newMaterialId);
                    //$material->stock_quantity = $material->stock_quantity + ($material->quantity ?? 100);
                    $material->stock_quantity = $material->quantity ?? 10; //(float)($material->stock_quantity + ($material->quantity ?? 100));
                    $material->cost_per_unit = $material->price ?? 100;
                    //$material->cost_per_unit = $material->price ?? 0;
                    $material->save();

                    $newProduction->materials()->create([
                        'material_id' => $newMaterialId,
                        'quantity' => $material->quantity ?? 1,
                        'unit_cost' => $material->unit_cost,
                        'total_cost' => $material->total_cost,
                        'status' => 'на складі',
                    ]);
                    $this->info('🔄 Міграція матеріалу '.$materialInfo->name.' для замовлення '.$order->id);
                }

            $productionStages = DB::connection('mysql_old')
                ->table('production_stages')
                ->where('production_id', $order->id)
                ->get();


            foreach ($productionStages as $stage) {
                $newProduction->stages()->create([
                    'name' => $stage->name,
                    'description' => $stage->description ?? null,
                    'status' => $stage->status ?? 'не розпочато',
                    'cost' => $stage->paid_worker ?? 0,
                ]);
                $this->info('🔄 Міграція етапу '.$stage->name.' для замовлення '.$order->id);
            }



            // $newProduction->calculateCostPrice();
            // $newProduction->save();
            $newOrder->orderItems()->create([
                'production_id' => $newProduction->id,
                'quantity' => $order->quantity ?? 1,
                'unit_price' => $order->price ?? 0,
                'total_price' => ($order->quantity ?? 1) * ($order->price ?? 0),
            ]);

            $newOrder->calculateTotals();
            $newOrder->save();

            // Логіка міграції кожного замовлення
            // ...
            $this->info('✅ Міграція замовлення '.$order->id.' завершена');
        }
        $this->info(' ✅✅✅✅✅✅✅✅✅✅ Замовлення додано для клієнта '.$customer->name);
            } else {
                $this->info('⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️ Немає замовлень для клієнта '.$customer->name);
            }

    }

}
