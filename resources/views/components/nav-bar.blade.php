<!-- Sidebar -->
<div id="hs-sidebar-content-push" class="p-3 w-64 h-full fixed top-0 start-0 bg-[#B8CFCE] text-black border-e border-gray-200 z-50" role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="flex flex-col h-full">

        <!-- Header -->
        <header class="p-4">
            <a class="break-words w-full font-bold text-3xl text-black focus:outline-hidden focus:opacity-80" href="#" aria-label="Brand">
                Repair Management
            </a>
        </header>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto px-2">
            <ul class="space-y-1 text-sm">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-md font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            <path d="M9 22V12h6v10" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Repair Status -->
                <li>
                    <a href="{{ route('status') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-md font-medium {{ request()->routeIs('status') ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Repair Status
                    </a>
                </li>

                <!-- New Request -->
                <li>
                    <a href="{{ route('request') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-md font-medium {{ request()->routeIs('request') ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14m7-7H5" />
                        </svg>
                        New Request
                    </a>
                </li>

                <!-- Report -->
                <li>
                    <a href="{{ route('report') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-md font-medium {{ request()->routeIs('report') ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 3v18h18V3H3z" />
                            <path d="M8 12h8M8 16h5M8 8h8" />
                        </svg>
                        Report
                    </a>
                </li>
                
                <!-- Tech Progress -->
                <li>
                    <a href="{{ route('techProgress') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-md font-medium {{ request()->routeIs('techProgress') ? 'bg-gray-100' : 'hover:bg-gray-100' }}">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                        Technician Progress
                    </a>
                </li>

                <hr class="my-3 border-gray-500">

                <!-- User Guide -->
                <li>
                    <a href="#" class="flex items-center gap-x-3 py-2 px-3 rounded-md hover:bg-gray-100">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M12 4v16m0-16L3 9l9 7" />
                        </svg>
                        User Guide
                    </a>
                </li>

                <!-- FAQ -->
                <li>
                    <a href="#" class="flex items-center gap-x-3 py-2 px-3 rounded-md hover:bg-gray-100">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 115.82 0c0 1.5-3 2-3 4" />
                            <line x1="12" y1="17" x2="12" y2="17" />
                        </svg>
                        FAQ
                    </a>
                </li>

                <!-- Help Center -->
                <li>
                    <a href="#" class="flex items-center gap-x-3 py-2 px-3 rounded-md hover:bg-gray-100">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16h.01" />
                            <path d="M12 12a2 2 0 10-2-2" />
                        </svg>
                        Help Center
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>