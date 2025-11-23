<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\MoventMaterial;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Production;
use App\Models\ProductionMaterial;
use App\Models\ProductionStage;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


    protected function handleRecordCreation(array $data): Order
    {
        return DB::transaction(function () use ($data) {

           $data = $this->form->getState();

            // 1️⃣ Створюємо замовлення
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'deadline' => $data['deadline'] ?? null,
                'notes' => $data['notes'] ?? null,
                'total_amount' => 0,
                'discount_amount' => 0,
                'paid_amount' => 0,
                'due_amount' => 0,
                'status' => 'очікується',
            ]);

            $total = 0;

            // // 2️⃣ Обробляємо кожен товар
            // foreach ($data['orderItems'] ?? [] as $itemData) {
            //     $productId = $itemData['product_id'] ?? null;
            //     $quantity = $itemData['quantity'] ?? 1;

            //     $template = Production::find($itemData['production']['template_id']) ?? null;

            //     if($template != null ){
            //          $unitPrice = $template->total_cost ?? 10000; // умовно 100.00 грн
            //     }
            //     else{
            //         $unitPrice = 0; // умовно 100.00 грн
            //     }


            //     if($productId != null ){
            //         $product = Product::find($productId);
            //         if($product != null ){
            //             $unitPrice = $product->price ?? $unitPrice;
            //         }
            //     }
            //     $totalPrice = $unitPrice * $quantity;
            //     // TODO: можеш додати логіку ціни з таблиці products


            //     $productionId = null;

            //     // 3️⃣ Якщо замовлено виробництво
            //     if (!empty($itemData['create_production'])) {
            //         $production = Production::create([
            //             'name' => $itemData['production_name'] ?? 'Виробництво без назви для замовлення #' . $order->id,
            //             'description' => $itemData['production_description'] ?? null,
            //             'order_id' => $order->id,
            //             'product_id' => $productId,
            //             'cost_price' => 0,
            //             'total_cost' => 0,
            //             'is_template' => false,
            //             'status' => 'в розробці',
            //         ]);

            //         $productionId = $production->id;

            //         // 4️⃣ Матеріали для виробництва
            //         $totalCost = 0;

            //         foreach ($itemData['production_materials'] ?? [] as $matData) {
            //             $materialId = $matData['material_id'];
            //             //$qty = $matData['quantity'] ?? 1;

            //             $template = \App\Models\Material::find($materialId);
            //             $qty = $template?->quantity ?? 1;
            //             // тут можна отримати ціну з таблиці materials
            //             $unitCost = $template?->cost_per_unit ?? 0;
            //             $sum = $unitCost * $qty;
            //             $totalCost += $sum;

            //             ProductionMaterial::create([
            //                 'production_id' => $productionId,
            //                 'material_id' => $materialId,
            //                 'quantity' => $qty,
            //                 'unit_cost' => $unitCost,
            //                 'total_cost' => $sum,
            //                 'status' => 'на складі',
            //             ]);


            //             // 5️⃣ Створюємо запис руху матеріалів
            //         //    MoventMaterial::create([
            //         //         'material_id' => $materialId,
            //         //         'from_warehouse_id' => 1,
            //         //         'to_warehouse_id' => null,
            //         //         'production_id' => $productionId,
            //         //         'quantity' => $qty,
            //         //         'status' => 'переміщено на виробництво',
            //         //     ]);
            //         }
            //         // Додаємо етап виробництва для використання матеріалів
            //         if($productionId !== null) {

            //         }
            //          ProductionStage::create([
            //             'production_id' => $productionId,
            //             'name' => 'Використання матеріалу #' . $materialId,
            //             'description' => 'Використано матеріал ID ' . $materialId . ' у кількості ' . $qty,
            //             'cost' => $sum,
            //             'duration' => 90, // можна додати логіку для обчислення тривалості
            //             'status' => 'завершено',
            //             'assigned_to' => null, // можна додати логіку для призначення відповідального
            //         ]);

            //         $production->update([
            //             'cost_price' => $totalCost,
            //             'total_cost' => $totalCost,
            //         ]);
            //     }




            //     // 6️⃣ Створюємо позицію замовлення
            //     OrderItem::create([
            //         'order_id' => $order->id,
            //         'product_id' => $productId,
            //         'production_id' => $productionId,
            //         'quantity' => $quantity,
            //         'unit_price' => $unitPrice,
            //         'total' => $totalPrice,
            //     ]);

            //     $total += $totalPrice;
            // }

            // 7️⃣ Оновлюємо суму замовлення
            $order->update([
                'total_amount' => $total,
                'due_amount' => $total,
            ]);

            return $order;
        });
    }
}
