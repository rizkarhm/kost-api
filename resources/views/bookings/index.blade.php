<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Kost</th>
                            <th class="border px-6 py-4">Types</th>
                            <th class="border px-6 py-4">User</th>
                            <th class="border px-6 py-4">Duration</th>
                            {{-- <th class="border px-6 py-4">Price</th> --}}
                            <th class="border px-6 py-4">Total</th>
                            <th class="border px-6 py-4">Status</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $bookings as $item )

                        <tr>
                            <td class="border px-6 py-4">{{ $item->id }}</td>
                            <td class="border px-6 py-4">{{ $item->kost->name }}</td>
                            <td class="border px-6 py-4">{{ $item->kost->types }}</td>
                            <td class="border px-6 py-4">{{ $item->user->name }}</td>
                            <td class="border px-6 py-4">{{ $item->duration($item->id, $item->start_date, $item->end_date) }}<span> Months</span></td>
                            <td class="border px-6 py-4"><span>Rp</span>{{ number_format($item->total) }} </td>
                            <td class="border px-6 py-4">{{ $item->status }}</td>

                            <td class="border px-6 py-4 text-center">
                                <a href="{{ route('bookings.show', $item->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                    Detail
                                </a>
                                <form action="{{ route('bookings.destroy', $item->id) }}" method="POST" class="inline-block">
                                    {!! method_field('delete') . csrf_field() !!}
                                    <button type="submit" class=" inline-block bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="border text-center p-5">
                                    Data Tidak Ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>


{{-- Masih ada bug saat input transaksi, blm bisa hitung duratio * price --}}
