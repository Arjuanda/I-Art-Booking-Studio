<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

      <!-- 🔥 Card Total + Status -->
      <div class="bg-gray-800 p-4 rounded-xl text-white shadow-lg col-span-1 md:col-span-1">
          <p class="text-sm mb-2">Booking</p>
          <div class="grid grid-cols-2 gap-2 text-center text-sm">
            <div class="bg-gray-500 rounded-lg p-0">
                  <p>Total</p>
                  <p class="font-bold">{{ $total }}</p>
            </div>
            
            <div class="bg-yellow-500 rounded-lg p-0">
                  <p>Pending</p>
                  <p class="font-bold">{{ $pending }}</p>
              </div>

              <div class="bg-green-500 rounded-lg p-0">
                  <p>Approved</p>
                  <p class="font-bold">{{ $approved }}</p>
              </div>

              <div class="bg-red-500 rounded-lg p-0">
                  <p>Rejected</p>
                  <p class="font-bold">{{ $rejected }}</p>
              </div>
          </div>
      </div>

      <div class="bg-green-800 p-4 rounded-xl text-white shadow-lg">
          <p class="text-sm mb-2">User</p>
          <div class="grid grid-cols-2 gap-2 text-center text-sm">
            <div class="bg-gray-500 rounded-lg p-0">
                <p>Total</p>
                <p class="font-bold">{{ $admin + $karyawan }}</p>
            </div>
            <div class="bg-indigo-400 rounded-lg p-0">
                  <p>Admin</p>
                  <p class="font-bold">{{ $admin }}</p>
            </div>
            
            <div class="bg-teal-700 rounded-lg p-0">
                  <p>Karyawan</p>
                  <p class="font-bold">{{ $karyawan }}</p>
            </div>
        </div>
      </div>

      <div class="bg-blue-600 p-4 rounded-xl text-white shadow-lg">
          <p class="text-sm">Event</p>
          <h2 class="text-4xl font-bold">{{ $events }}</h2>
      </div>

      <div class="bg-pink-600 p-4 rounded-xl text-white shadow-lg">
          <p class="text-sm">Documentation</p>
          <h2 class="text-4xl font-bold">{{ $documentations }}</h2>
      </div>

  </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <div class="bg-white p-4 rounded-xl shadow-lg mb-2">
        <h3 class="text-black text-lg font-semibold mb-4">Jadwal Tertunda ({{ $pending }})</h3>

        <div class="overflow-x-auto h-45">
            <table class="w-full text-sm text-left text-gray-900">
                <thead class="text-xs uppercase bg-white text-gray-900">
                    <tr>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Jam</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingBookings as $item)
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-2">{{ $item->bookMaker->name }}</td>
                            <td class="px-4 py-2">{{ $item['start']->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $item['start']->format('H:i') }} - {{ $item['end']->format('H:i') }}</td>
                            <td class="px-4 py-2 flex gap-2">
                              <div class="flex flex-col md:flex-row gap-2">
                                <form action="{{ route('booking.approve', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-green-600 px-3 py-1 rounded text-white cursor-pointer">Approve</button>
                                </form>

                                <form action="{{ route('booking.reject', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-red-600 px-3 py-1 rounded text-white cursor-pointer">Reject</button>
                                </form>
                              </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center pt-14">Tidak ada Jadwal pending</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow-lg mb-2">
        <h3 class="text-black text-lg font-semibold mb-4">Jadwal Hari Ini</h3>
          <div class="overflow-y-auto h-45 scroll-hide">
            <table class="w-full text-sm text-left text-gray-900">
              <thead class="text-xs uppercase bg-white text-gray-900">
                <tr>
                  <th class="px-4 py-2">User</th>
                  <th class="px-4 py-2">Tanggal</th>
                  <th class="px-4 py-2">Jam</th>
                  <th class="px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody>

              @forelse ($todayBookings as $item)
                <tr class="border-b border-gray-700">
                  <td class="px-4 py-2">{{ $item->bookMaker->name }}</td>
                  <td class="px-4 py-2">{{ $item['start']->format('Y-m-d') }}</td>
                  <td class="px-4 py-2">{{ $item['start']->format('H:i') }} - {{ $item['end']->format('H:i') }}</td>
                  <td class="px-4 py-2 flex gap-2">
                    <span class="badge-status" data-value="{{ $item->display_status }}">{{ $item->display_status }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                    <td colspan="4" class="text-center pt-14">Tidak ada jadwal hari ini</td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-black text-lg font-semibold mb-4">Jam Sibuk</h3>

        @forelse ($peakHours as $item)
            <div class="flex justify-between text-gray-900 border-b border-gray-700 py-2">
                <span>{{ str_pad($item->hour, 2, '0', STR_PAD_LEFT) }}:00</span>
                <span>{{ $item->total }} booking</span>
            </div>
        @empty
            <p class="text-gray-400">Tidak ada data</p>
        @endforelse
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
    <h3 class="text-black text-lg font-semibold mb-4">User Paling Aktif</h3>

    @forelse ($topUsers as $item)
        <div class="flex justify-between text-gray-900 border-b border-gray-700 py-2">
            <span>{{ $item->bookMaker->name }}</span>
            <span>{{ $item->total }} booking</span>
        </div>
    @empty
        <p class="text-gray-400">Tidak ada data</p>
    @endforelse
</div>
</x-layout>