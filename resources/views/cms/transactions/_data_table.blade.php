@forelse ($transactions as $key => $item)
    <tr>
        <th scope="row">{{ $transactions->firstItem() + $key }}</th>
        <td>{{ $item->transaction_id }}</td>
        <td>{{ formatRupiah($item->total_amount) }}</td>
        <td><button onclick='showItems(@json($item->items))' type="button" class="badge bg-primary">lihat item ({{ is_array($item->items) ? count($item->items) : 0 }})</button></td>
        <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d-m-Y H:i:s') }}</td>
    </tr>
@empty
    <x-dataNotFound colspan="5" />
@endforelse