<x-layout>
    <!-- Header -->
    <div class="flex justify-between items-center bg-[#dfe8e6] p-6 mb-5 rounded-md border border-gray-300 shadow">
        <div class="flex items-center gap-3">
            <!-- Technician Support SVG Icon -->
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
            <button id="add-tech-btn" class="bg-[#25b4b2] text-white px-4 py-2 rounded hover:bg-[#1e908f] transition">+ Add Technician</button>
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
                    <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition" onclick="closePinModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#25b4b2] text-white hover:bg-[#1e908f] transition">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Technician Modal -->
    <div id="add-tech-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative border border-gray-200"> 
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold" onclick="closeAddTechModal()">&times;</button>
            <h2 class="text-2xl font-bold text-[#25b4b2] mb-6">Add Technician</h2>
            <!-- Connection will do here -->
            <form id="add-tech-form">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="tech-name">Name</label>
                    <input id="tech-name" name="name" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="tech-company">Company</label>
                    <input id="tech-company" name="company" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition" onclick="closeAddTechModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#25b4b2] text-white hover:bg-[#1e908f] transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // PIN input focus/auto-advance logic
        document.querySelectorAll('[data-hs-pin-input-item]').forEach((input, idx, arr) => {
            input.addEventListener('input', function(e) {
                if (this.value.length === 1 && idx < arr.length - 1) {
                    arr[idx + 1].focus();
                }
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && idx > 0) {
                    arr[idx - 1].focus();
                }
            });
        });

        // Show PIN modal when "+ Add Technician" is clicked
        document.getElementById('add-tech-btn').addEventListener('click', function() {
            document.getElementById('pin-modal').classList.remove('hidden');
            setTimeout(function() {
                document.getElementById('pin-input-1').focus();
            }, 100);
        });

        function closePinModal() {
            document.getElementById('pin-modal').classList.add('hidden');
            document.querySelectorAll('[data-hs-pin-input-item]').forEach(input => input.value = '');
        }

        function closeAddTechModal() {
            document.getElementById('add-tech-modal').classList.add('hidden');
            document.getElementById('add-tech-form').reset();
        }

        // PIN form logic
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

        // Add Technician form logic (example: just closes modal and resets form)
        document.getElementById('add-tech-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would send data to backend via AJAX or form submit
            // For now, just close modal and reset
            closeAddTechModal();
            alert('Technician added!');
        });
    </script>

    <div class="p-4">
        <h1 class="text-xl font-bold mb-4">Technicians List</h1>

        @if ($message)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            {{ $message }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ $errors->first('error') }}
        </div>
        @endif

        @if (!$technicians->isEmpty())
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Name</th>
                    <th class="py-2 px-4 border">Company</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($technicians as $tech)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border">{{ $tech->tech_id }}</td>
                    <td class="py-2 px-4 border">{{ $tech->tech_name }}</td>
                    <td class="py-2 px-4 border">{{ $tech->company }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</x-layout>