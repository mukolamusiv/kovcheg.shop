<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Виписка з рахунку: {{ $account->name }}</title>

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

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Виписка з рахунку</h1>
    <h3>Назва рахунку: {{ $account->name }}</h3>
    <h3>Баланс: {{ number_format($account->balance / 100, 2) }} {{ $account->currency }}</h3>

    <div class="section">
        <h2>Транзакції</h2>
        <table>
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Тип транзакції</th>
                    <th>Опис</th>
                    <th class="text-right">Сума</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($account->transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('d.m.Y H:i') }}</td>
                        <td>{{ $transaction->transaction_type }}</td>
                        <td>{{ $transaction->description ?? '—' }}</td>
                        <td class="text-right">
                            @if ($transaction->transaction_type === 'витрати')
                                -{{ number_format($transaction->amount / 100, 2) }}
                            @else
                                +{{ number_format($transaction->amount / 100, 2) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Транзакції відсутні</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
