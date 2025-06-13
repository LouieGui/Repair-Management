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

    <!-- Add Technician Modal -->
    <div id="add-tech-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative border border-gray-200">
            <!-- X Button -->
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl font-bold" onclick="closeAddTechModal()">&times;</button>
            <h2 class="text-2xl font-bold text-[#25b4b2] mb-6">Add Technician</h2>
            <form>
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
        // Show modal when "+ Add Technician" is clicked
        document.getElementById('add-tech-btn').addEventListener('click', function() {
            document.getElementById('add-tech-modal').classList.remove('hidden');
        });

        function closeAddTechModal() {
            document.getElementById('add-tech-modal').classList.add('hidden');
        }
    </script>

    <!-- Tech Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex gap-x-10 py-2" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
            <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#0e4f41] disabled:opacity-50 disabled:pointer-events-none active cursor-pointer" id="diagnosed-tab" aria-selected="true" data-hs-tab="#diagnosed" aria-controls="diagnosed" role="tab">
                Angelito Guiaya
            </button>
            <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="in-repair-tab" aria-selected="false" data-hs-tab="#in-repair" aria-controls="in-repair" role="tab">
                Dharlyn Guiaya
            </button>
        </nav>
    </div>

    <!-- Table Contents -->
    <div>
        <div class="overflow-x-auto mt-6 border border-gray-200 rounded-md shadow">
            <div class="max-h-[500px] overflow-y-auto">
                <table class="min-w-full bg-white">
                    <thead class="sticky top-[-1px] z-10 bg-gray-200 h-16">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Serial No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Juan Dela Cruz<br><span class="text-xs text-gray-500">Laptop, Screen flickering</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">juan.delacruz@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0917-123-4567</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN123456789</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Diagnosed</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Maria Santos<br><span class="text-xs text-gray-500">Smartphone, Battery replacement</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">maria.santos@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0928-456-7890</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN987654321</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">In Repair</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Pedro Pascual<br><span class="text-xs text-gray-500">Printer, Paper jam</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">pedro.pascual@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0918-234-5678</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN112233445</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Waiting for Parts</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ana Reyes<br><span class="text-xs text-gray-500">Tablet, Charging issue</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ana.reyes@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0932-111-2222</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN556677889</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Repaired</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Carlos Mendoza<br><span class="text-xs text-gray-500">Desktop, No power</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">carlos.mendoza@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0945-333-4444</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN998877665</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Waiting for Pickup</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Liza Cruz<br><span class="text-xs text-gray-500">Monitor, Broken display</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">liza.cruz@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0917-555-6666</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN223344556</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">Delivered</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mark Villanueva<br><span class="text-xs text-gray-500">Router, Not connecting</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">mark.villanueva@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0921-777-8888</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN334455667</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800">Void</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Grace Lim<br><span class="text-xs text-gray-500">Smartwatch, Water damage</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">grace.lim@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0935-999-0000</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN445566778</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">In Repair</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rafael Gomez<br><span class="text-xs text-gray-500">Camera, Lens error</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">rafael.gomez@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0916-222-3333</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN556677889</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Repaired</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sofia Ramos<br><span class="text-xs text-gray-500">Speaker, No sound</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">sofia.ramos@email.com</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0923-444-5555</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SN667788990</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Waiting for Parts</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="#" class="bg-[#25b4b2] text-white px-3 py-1 rounded hover:bg-[#1e908f] transition">View Details</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="details-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-opacity duration-200 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-[#25b4b2] bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8 text-[#25b4b2]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" fill="none" />
                            <path d="M4 20v-1a4 4 0 014-4h8a4 4 0 014 4v1" stroke="currentColor" fill="none" />
                            <path d="M18 14v-2a6 6 0 00-12 0v2" stroke="currentColor" fill="none" />
                            <rect x="17" y="17" width="4" height="4" rx="1" fill="currentColor" opacity="0.2" />
                            <path d="M19 19v-1" stroke="currentColor" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#25b4b2]">Customer Details</h2>
                        <p class="text-gray-500 text-sm">Comprehensive information about the repair job</p>
                    </div>
                </div>
                <button type="button" class="bg-[#25b4b2] text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-[#1e908f] transition" onclick="closeModal()">Close</button>
            </div>
            <div class="flex flex-col md:flex-row gap-8">

                <!-- Left Side: Customer Info -->
                <div class="flex-1 grid grid-cols-1 gap-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Name</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" value="Juan Dela Cruz" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Customer ID</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" value="22-60014" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Department</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" value="IT Department" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Contact</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" value="0917-123-4567" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Serial No.</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" value="SN123456789" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                            <input type="text" class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-blue-50 text-blue-700 font-semibold text-sm" value="Diagnosed" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Problems</label>
                        <textarea class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" rows="2" readonly>Laptop, Screen flickering</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Technician Notes</label>
                        <textarea class="border border-gray-200 rounded-lg px-3 py-2 w-full bg-gray-50 text-gray-800 text-sm" rows="2" readonly>Screen needs replacement. Awaiting customer approval.</textarea>
                    </div>
                </div>
                <!-- Right Side: Quotation Parts -->
                <div class="flex-1 flex flex-col">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Quotation Parts</label>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 rounded h-[400px] shadow-sm">
                            <thead>
                                <tr>
                                    <th colspan="2" class="px-4 py-3 text-center text-base font-bold text-gray-800 border-b border-gray-200 bg-gray-50 tracking-widest">
                                        Receipt
                                    </th>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase border-b border-gray-100">Part Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase border-b border-gray-100">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">LCD Screen</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">₱2,000</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">Connector Cable</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">₱500</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">Battery</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">₱1,200</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">Charging Port</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-b border-dashed border-gray-200">₱800</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="px-4 py-2 font-semibold text-right text-gray-700 border-t border-gray-300">Total</td>
                                    <td class="px-4 py-2 font-semibold text-green-700 border-t border-gray-300">₱4,500</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Open modal when any "View Details" is clicked
        document.querySelectorAll('a[href="#"]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('details-modal').classList.remove('hidden');
            });
        });

        function closeModal() {
            document.getElementById('details-modal').classList.add('hidden');
        }
    </script>
</x-layout>