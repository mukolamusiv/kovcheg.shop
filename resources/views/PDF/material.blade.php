<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Матеріал: {{ $material->name }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        h1, h2, h3 {
            margin: 5px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .row {
            display: flex;
            width: 100%;
            margin-bottom: 15px;
        }

        .col {
            flex: 1;
            padding-right: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
        }

        th {
            background: #f7f7f7;
        }

        .barcode {
            margin-top: 15px;
            text-align: center;
        }

        .photo {
            max-width: 200px;
            margin-bottom: 10px;
        }

        .barcode img {
            width: 260px;
            height: auto;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Картка матеріалу</h1>
    <h3>Назва: {{ $material->name }}</h3>

    <div class="section row">

        <div class="col">
            @if ($material->photo_path)
                <img class="photo" src="{{ public_path('storage/' . $material->photo_path) }}" alt="Фото матеріалу">
            @else
                <p><i>Фото відсутнє</i></p>
            @endif
        </div>

        <div class="col">
            <table>
                <tr>
                    <th>Категорія</th>
                    <td>{{ $material->category->name ?? 'Н/Д' }}</td>
                </tr>

                <tr>
                    <th>Опис</th>
                    <td>{{ $material->description ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Одиниця</th>
                    <td>{{ $material->unit }}</td>
                </tr>

                <tr>
                    <th>Ціна за одиницю</th>
                    <td>{{ number_format($material->cost_per_unit / 100, 2) }}</td>
                </tr>

                <tr>
                    <th>Залишок на складі</th>
                    <td>{{ number_format($material->stock_quantity / 100, 2) }} {{ $material->unit }}</td>
                </tr>

                <tr>
                    <th>Постачальник</th>
                    <td>{{ $material->supplier->name ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Штрих-код (EAN-13)</th>
                    <td>{{ $material->barcode }}</td>
                </tr>

            </table>
        </div>

    </div>

    <div class="barcode">
        <h3>Штрих-код:</h3>

        @if($material->barcode_image)
            <img src="{{ public_path('storage/'.$material->barcode_image) }}" alt="barcode">
        @else
            <p><i>Штрих-код не згенеровано.</i></p>
        @endif
    </div>

</div>
</body>
</html>
