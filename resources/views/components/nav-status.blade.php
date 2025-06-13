<div class="border-b border-gray-200">
  <nav class="flex gap-x-10 py-2" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#0e4f41] disabled:opacity-50 disabled:pointer-events-none active cursor-pointer" id="diagnosed-tab" aria-selected="true" data-hs-tab="#diagnosed" aria-controls="diagnosed" role="tab">
      Diagnosed
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="in-repair-tab" aria-selected="false" data-hs-tab="#in-repair" aria-controls="in-repair" role="tab">
      In Repair
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="waiting-parts-tab" aria-selected="false" data-hs-tab="#waiting-parts" aria-controls="waiting-parts" role="tab">
      Waiting for Parts
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="repaired-tab" aria-selected="false" data-hs-tab="#repaired" aria-controls="repaired" role="tab">
      Repaired
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="waiting-pickup-tab" aria-selected="false" data-hs-tab="#waiting-pickup" aria-controls="waiting-pickup" role="tab">
      Waiting for Pickup
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="delivered-tab" aria-selected="false" data-hs-tab="#delivered" aria-controls="delivered" role="tab">
      Delivered
    </button>
    <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-[#35534c] hs-tab-active:text-[#35534c] inline-flex items-center border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-[#35534c] focus:outline-hidden focus:text-[#35534c] disabled:opacity-50 disabled:pointer-events-none cursor-pointer" id="void-tab" aria-selected="false" data-hs-tab="#void" aria-controls="void" role="tab">
      Void
    </button>
  </nav>
</div>

<!-- Main Content -->
<div class="mt-3">
  <div id="diagnosed" class="" role="tabpanel" aria-labelledby="diagnosed-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Diagnosed:
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="in-repair" class="hidden" role="tabpanel" aria-labelledby="in-repair-tab">
    <p class="text-gray-500 text-[28px]">
      This Month In Repair:
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="waiting-parts" class="hidden" role="tabpanel" aria-labelledby="waiting-parts-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Waiting for Parts:
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="repaired" class="hidden" role="tabpanel" aria-labelledby="repaired-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Repaired:
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="waiting-pickup" class="hidden" role="tabpanel" aria-labelledby="waiting-pickup-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Waiting for Pickup:
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="delivered" class="hidden" role="tabpanel" aria-labelledby="delivered-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Delivered:
      {{ $slot }}
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>

  <div id="void" class="hidden" role="tabpanel" aria-labelledby="void-tab">
    <p class="text-gray-500 text-[28px]">
      This Month Void
      {{ $slot }}
    </p>
    <div>
      {{ $slot }}
    </div>
  </div>
  
</div>