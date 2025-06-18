<x-layout>
    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-xl shadow-xl text-center"
        role="alert">
        <strong class="block font-semibold text-lg mb-1">Success!</strong>
        <span class="block text-sm">{{ session('success') }}</span>
    </div>
    @endif



    <!-- Header -->
    <div class="flex justify-between items-center bg-[#dfe8e6] p-6 mb-5 rounded-md border border-gray-300 shadow">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-[#25b4b2]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="8" r="4" stroke="currentColor" fill="none" />
                <path d="M4 20v-1a4 4 0 014-4h8a4 4 0 014 4v1" stroke="currentColor" fill="none" />
                <path d="M18 14v-2a6 6 0 00-12 0v2" stroke="currentColor" fill="none" />
                <rect x="17" y="17" width="4" height="4" rx="1" fill="currentColor" opacity="0.2" />
                <path d="M19 19v-1" stroke="currentColor" stroke-linecap="round" />
            </svg>
            <div>
                <h1 class="font-bold text-3xl">Technician</h1>
                <p class="text-gray-600 text-sm">View and manage each technician's assigned and completed works below.</p>
            </div>
        </div>
        <div class="flex items-center space-x-6">
            <button id="add-tech-btn" class="bg-[#25b4b2] text-white px-4 py-2 rounded hover:bg-[#1e908f] transition cursor-pointer">+ Add Technician</button>
        </div>
    </div>

    <!-- PIN Modal -->
    <div id="pin-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-8 relative border border-gray-200">
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold" onclick="closePinModal()">&times;</button>
            <h2 class="text-xl font-bold text-[#25b4b2] mb-4">Enter 4-digit PIN</h2>
            <form id="pin-form">
                <div class="py-2 px-3 bg-white border border-gray-200 rounded-lg mb-4">
                    <div class="flex gap-x-5" data-hs-pin-input="">
                        <input class="block size-9.5 text-center border-gray-200 rounded-md sm:text-sm placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="○" data-hs-pin-input-item="" id="pin-input-1" autocomplete="off">
                        <input class="block size-9.5 text-center border-gray-200 rounded-md sm:text-sm placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="○" data-hs-pin-input-item="" id="pin-input-2" autocomplete="off">
                        <input class="block size-9.5 text-center border-gray-200 rounded-md sm:text-sm placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="○" data-hs-pin-input-item="" id="pin-input-3" autocomplete="off">
                        <input class="block size-9.5 text-center border-gray-200 rounded-md sm:text-sm placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="○" data-hs-pin-input-item="" id="pin-input-4" autocomplete="off">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition cursor-pointer" onclick="closePinModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#25b4b2] text-white hover:bg-[#1e908f] transition cursor-pointer">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Technician Modal -->
    <div id="add-tech-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative border border-gray-200">
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold" onclick="closeAddTechModal()">&times;</button>
            <h2 class="text-2xl font-bold text-[#25b4b2] mb-6">Add Technician</h2>
            <form id="add-tech-form" action="{{ route('progressAdd') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="tech-name">Name</label>
                    <input id="tech-name" name="tech_name" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="tech-company">Company</label>
                    <input id="tech-company" name="company" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition cursor-pointer" onclick="closeAddTechModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#25b4b2] text-white hover:bg-[#1e908f] transition cursor-pointer">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript (no more submit blocking) -->
    <script>
        document.getElementById('add-tech-btn').addEventListener('click', function() {
            document.getElementById('pin-modal').classList.remove('hidden');
            setTimeout(() => document.getElementById('pin-input-1').focus(), 100);
        });

        function closePinModal() {
            document.getElementById('pin-modal').classList.add('hidden');
            document.querySelectorAll('[data-hs-pin-input-item]').forEach(input => input.value = '');
        }

        function closeAddTechModal() {
            document.getElementById('add-tech-modal').classList.add('hidden');
            document.getElementById('add-tech-form').reset();
        }

        // PIN check logic
        document.getElementById('pin-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const pin = [
                document.getElementById('pin-input-1').value,
                document.getElementById('pin-input-2').value,
                document.getElementById('pin-input-3').value,
                document.getElementById('pin-input-4').value
            ].join('');
            if (pin === '1112') {
                closePinModal();
                document.getElementById('add-tech-modal').classList.remove('hidden');
            } else {
                alert('Incorrect PIN. Please try again.');
                document.querySelectorAll('[data-hs-pin-input-item]').forEach(input => input.value = '');
                document.getElementById('pin-input-1').focus();
            }
        });
    </script>


    <!-- Dynamic Tabs Start -->
    @php $firstTechId = $technicians->first()?->tech_id; @endphp

    <div x-data="{ tab: '{{ $firstTechId }}' }">
        <!-- Dropdown for small screens -->
        <select
            x-model="tab"
            class="sm:hidden py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
            aria-label="Technician Tabs">
            @foreach ($technicians as $tech)
            <option value="{{ $tech->tech_id }}">{{ $tech->tech_name }}</option>
            @endforeach
        </select>

        @if ($technicians->isNotEmpty())
        <!-- Tab buttons for desktop -->
        <div class="hidden sm:block border-b border-gray-200 mt-4">
            <nav class="flex gap-x-2" aria-label="Technician Tabs">
                @foreach ($technicians as $tech)
                <div class="relative">
                    <div
                        @click="tab = '{{ $tech->tech_id }}'"
                        class="py-2 px-4 inline-flex items-center border border-gray-200 text-sm font-medium rounded-t-lg cursor-pointer relative"
                        :class="tab === '{{ $tech->tech_id }}' ? 'bg-[#35534c] text-white' : 'bg-gray-50 text-gray-500 hover:text-gray-700'">
                        <span class="flex items-center">
                            {{ $tech->tech_name }}
                            <!-- X Button Inside Tab -->
                            <form
                                method="POST"
                                action="{{ route('progressDelete', $tech->tech_id) }}"
                                onsubmit="event.stopPropagation(); return confirm('Remove {{ $tech->tech_name }}?')"
                                class="ml-2">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-white-500 hover:text-red-700 text-xl flex items-center justify-center leading-none"
                                    title="Remove"
                                    @click.stop>×</button>
                            </form>
                        </span>
                    </div>
                </div>
                @endforeach
            </nav>
        </div>


        <!-- Tab content -->
        <div class="mt-3">
            @foreach ($technicians as $tech)
            <div
                x-show="tab === '{{ $tech->tech_id }}'"
                x-transition
                class="p-3 sm:p-0">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $tech->tech_name }}</h2>
                <p class="text-gray-500">Company: <span class="text-gray-800">{{ $tech->company }}</span></p>
                <div class="mt-4 text-sm text-gray-500 italic">Assigned work list or progress info can go here...</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-4 rounded">
            No technicians found.
        </div>
        @endif
    </div>
    <!-- Dynamic Tabs End -->

</x-layout>