@forelse ($report as $key => $item)
    <tr>
        <th scope="row">{{ $key + 1 }}</th>
        <td>{{ $item['product_name'] }}</td>
        <td>{{ $item['total_quantity'] }}</td>
        <td>{{ formatRupiah($item['product']['current_price'])}}</td>
        <td>{{ formatRupiah($item['total_sales'])}}</td>
        {{-- <td><button onclick='showItems(@json($item->items))' type="button" class="badge bg-primary">lihat item ({{ is_array($item->items) ? count($item->items) : 0 }})</button></td> --}}
        {{-- <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d-m-Y H:i:s') }}</td> --}}
    </tr>
@empty
    <x-dataNotFound colspan="5" />
@endforelse