<x-layout>
    <!-- Header -->
    <div class="flex justify-between items-center bg-[#dfe8e6] p-6 rounded-md border border-gray-300 shadow">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-[#25b4b2]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <rect x="4" y="3" width="16" height="18" rx="3" stroke="currentColor" fill="none"/>
            <rect x="7" y="6" width="10" height="2" rx="1" fill="currentColor" opacity="0.2"/>
            <rect x="7" y="10" width="7" height="2" rx="1" fill="currentColor" opacity="0.2"/>
            <rect x="7" y="14" width="5" height="2" rx="1" fill="currentColor" opacity="0.2"/>
            <path d="M7 6h10M7 10h7M7 14h5" stroke="currentColor" stroke-linecap="round"/>
            </svg>
            <h1 class="font-bold text-3xl">Request Form</h1>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-m italic">
                <strong>Date:</strong> <span id="current-date">{{ now()->setTimezone('Asia/Manila')->format('Y-m-d') }}</span>
            </span>
            <span class="text-m italic">
                <strong>Time:</strong> <span id="current-time">{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}</span>
            </span>
        </div>
    </div>

    <form class="mt-6 space-y-6">
        <div class="flex gap-8">
            <!-- Left Side -->
            <div class="w-1/2">
                <h5 class="mb-5">Customer Information</h5>
                <hr class="my-4 border-t-2 border-gray-300">

                <!-- Full Name -->
                <div class="mb-4">
                    <label for="fullname" class="block text-gray-700 font-semibold mb-2">
                        Full Name:
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="fullname" id="fullname" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. Juan Dela Cruz" required />
                </div>

                <div class="flex gap-4 mb-4">
                    <!-- Customer ID -->
                    <div class="w-1/2">
                        <label for="customer_id" class="block text-gray-700 font-semibold mb-2">
                            Customer ID:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="customer_id" id="customer_id" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. 22-00512" required />
                    </div>
                    <!-- Department -->
                    <div class="w-1/2">
                        <label for="section" class="block text-gray-700 font-semibold mb-2">
                            Department:
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="section" id="section" class="rounded-md border w-full h-10 px-4 py-2 text-lg" required>
                            <option disabled selected>Select Department</option>
                            <option value="BSCS">BS Computer Science</option>
                            <option value="BSIT">BS Information Technology</option>
                            <option value="BSA">BS Accountancy</option>
                            <option value="BSHM">BS Hospitality Management</option>
                            <option value="BSPSYCH">BS Psychology</option>
                            <option value="BSNURSING">BS Nursing</option>
                            <option value="BSENTREP">BS Entrepreneurship</option>
                            <option value="BSECE">BS Electronics and Communications Engineering</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="w-1/3">
                        <label for="contact_number" class="block text-gray-700 font-semibold mb-2">
                            Contact Number:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_number" id="contact_number" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. 09171234567" required />
                    </div>
                    <div class="w-2/3">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">
                            Email Address:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. juan@email.com" required />
                    </div>
                </div>

                <div class="mb-4">
                    <label for="problems" class="block text-gray-700 font-semibold mb-2">
                        Problems:
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea name="problems" id="problems" rows="2" class="rounded-md border w-full px-4 py-2 text-lg" placeholder="Describe the problems" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="technician_notes" class="block text-gray-700 font-semibold mb-2">
                        Technician Notes:
                    </label>
                    <textarea name="technician_notes" id="technician_notes" rows="2" class="rounded-md border w-full px-4 py-2 text-lg" placeholder="Technician's notes"></textarea>
                </div>
            </div>

            <!-- Right Side -->
            <div class="w-1/2">
                <h5 class="mb-5">Technical Information</h5>
                <hr class="my-4 border-t-2 border-gray-300">

                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label for="serial_no" class="block text-gray-700 font-semibold mb-2">
                            Serial No.:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="serial_no" id="serial_no" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. SN123456789" required />
                    </div>
                    <div class="w-1/2">
                        <label for="status" class="block text-gray-700 font-semibold mb-2">
                            Status:
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="rounded-md border w-full h-10 px-4 py-2 text-lg" required>
                            <option disabled selected>Select Status</option>
                            <option value="diagnosed">Diagnosed</option>
                            <option value="in_repair">In Repair</option>
                            <option value="waiting_for_parts">Waiting for Parts</option>
                            <option value="repaired">Repaired</option>
                            <option value="waiting_for_pickup">Waiting for Pickup</option>
                            <option value="delivered">Delivered</option>
                            <option value="void">Void</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label for="technician_name" class="block text-gray-700 font-semibold mb-2">
                            Technician Name:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="technician_name" id="technician_name" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. Pedro Santos" required />
                    </div>
                    <div class="w-1/2">
                        <label for="technician_company" class="block text-gray-700 font-semibold mb-2">
                            Company:
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="technician_company" id="technician_company" class="rounded-md border w-full h-10 px-4 py-2 text-lg" placeholder="e.g. ABC Repairs Inc." required />
                    </div>
                </div>

                <!-- Quotation Section -->
                <div class="mb-4">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold text-gray-700">Parts Quotation</span>
                            <span class="text-lg font-semibold text-gray-700">Total: <span class="text-2xl font-bold text-[#25b4b2]">₱<span id="parts-total"> 0.00</span></span></span>
                        </div>

                        <div id="parts-list" class="space-y-2 overflow-y-auto" style="max-height: 200px; min-height: 200px;">
                            <div class="flex items-center gap-2">
                                <label class="sr-only" for="part_name[]">Part Name <span class="text-red-500">*</span></label>
                                <input type="text" name="part_name[]" class="border rounded-md px-3 py-2 w-1/2" placeholder="Part Name" required />
                                <div class="relative w-1/4">
                                    <label class="sr-only" for="part_price[]">Part Price <span class="text-red-500">*</span></label>
                                    <span class="absolute left-2 top-2.5 text-gray-500">₱</span>
                                    <input type="number" name="part_price[]" class="border rounded-md pl-6 pr-3 py-2 w-full part-price text-right" placeholder="0.00" min="0" step="0.01" required />
                                </div>
                                <button type="button" onclick="addPartRow()" class="inline-flex items-center px-3 py-2 bg-[#25b4b2] text-white text-sm rounded-md hover:bg-[#b8cfce] transition flex-1 justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 justify-end">
                    <button
                        type="submit"
                        class="bg-[#25b4b2] text-white px-6 py-2 rounded-md hover:bg-[#b8cfce] transition"
                        onclick="return confirm('Are you sure you want to save this request?')">
                        Save
                    </button>
                    <button
                        type="button"
                        class="bg-gray-400 text-white px-6 py-2 rounded-md hover:bg-gray-500 transition"
                        onclick="if(confirm('Are you sure you want to cancel? All changes will be lost.')) { window.location.href='{{ url()->previous() }}'; }">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function addPartRow() {
            const partsList = document.getElementById('parts-list');
            const lastRow = partsList.lastElementChild;
            if (lastRow) {
                const addBtn = lastRow.querySelector('button[type="button"]');
                if (addBtn) {
                    addBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg> Cancel`;
                    addBtn.className = "inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition flex-1 justify-center";
                    addBtn.onclick = function() {
                        lastRow.remove();
                        updatePartsTotal();
                    };
                }
            }

            const row = document.createElement('div');
            row.className = 'flex items-center gap-2';
            row.innerHTML = `
                <input type="text" name="part_name[]" class="border rounded-md px-3 py-2 w-1/2" placeholder="Part Name" />
                <input type="number" name="part_price[]" class="border rounded-md px-3 py-2 w-1/4 part-price text-right" placeholder="0.00" min="0" step="0.01" />
                <button type="button" onclick="addPartRow()" class="inline-flex items-center px-3 py-2 bg-[#25b4b2] text-white text-sm rounded-md hover:bg-[#b8cfce] transition flex-1 justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg> Add
                </button>`;
            partsList.appendChild(row);
            attachPriceListeners();
        }

        function updatePartsTotal() {
            let total = 0;
            document.querySelectorAll('.part-price').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            document.getElementById('parts-total').textContent = total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        }

        function attachPriceListeners() {
            document.querySelectorAll('.part-price').forEach(input => {
                input.removeEventListener('input', updatePartsTotal);
                input.addEventListener('input', updatePartsTotal);
            });
        }

        attachPriceListeners();
    </script>
</x-layout>