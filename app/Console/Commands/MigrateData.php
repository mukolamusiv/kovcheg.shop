<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

            $this->customer();

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


    private function materials(){
        $this->info('🔄 Міграція матеріалів');
        // Логіка міграції матеріалів
        $materials = DB::connection('mysql_old')
            ->table('materials')
            ->get();


        foreach($materials as $material){
            $this->info('🔄 Міграція матеріалу '.$material->name);
            $existingMaterialId = DB::table('materials')
                ->where('name', $material->name)
                ->orWhere('slug', \Str::slug($material->name))
                ->value('id');

            if(!$existingMaterialId){
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
        $this->info('🔄 Міграція клієнтів');
        // Логіка міграції матеріалів
        $customers = DB::connection('mysql_old')
            ->table('customers')
            ->get();

            foreach($customers as $customer){
                $this->info('🔄 Міграція клієнта '.$customer->name);
                $existingCustomerId = DB::table('users')
                    ->where('name', $customer->name)
                    //->orWhere('slug', \Str::slug($customer->name))
                    ->value('id');
                if(!$existingCustomerId){
                    $user = new User([
                        'name'=>$customer->name,
                    ]);
                    $user->save();

                    $profile = new \App\Models\CustomerProfile([
                        'user_id' => $user->id,
                        'phone' => $customer->phone ?? null,
                        'address' => $customer->address ?? null,
                        //'date_of_birth' => $customer->date_of_birth ?? null,
                        'city' => $customer->city ?? null,
                        'note' => $customer->description ?? null,
                        //'measurements' => json_decode($customer->measurements ?? '[]', true),
                    ]);
                    $profile->save();
                    $this->info('✅ Міграція клієнта '.$customer->name.' завершена');
                }
            }
    }

    private function orders()
    {
        $this->info('🔄 Міграція замовлень');
        // Логіка міграції замовлень
        $orders = DB::connection('mysql_old')
            ->table('productions')
            ->get();

        foreach($orders as $order){
            $this->info('🔄 Міграція замовлення '.$order->id);
            // Логіка міграції кожного замовлення
            // ...
            $this->info('✅ Міграція замовлення '.$order->id.' завершена');
        }
    }
}
