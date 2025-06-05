<div>
  
	<x-slot:sidebar
			drawer="main-drawer"
			collapsible
			collapse-text="Hide"
			class="bg-base-100 bg-inherit">

			{{-- BRAND --}}
			<div class="flex items-center justify-between">
					<div class="flex items-center mt-5 justify-evenly">
							<div class="pl-2" >
									<p class="text-xl"><img 
                    class="w-24 md:w-36 lg:w-40 h-auto" 
                    src="{{ asset('images/eclessia_flow_logo4.png') }}" 
                    alt="Ecclesia Flow" 
                /></p>
							</div>
					</div>
			</div>

			{{-- MENU --}}
			<x-mary-menu activate-by-route>					

					@if($user = auth()->user())
                <x-mary-menu-separator />

								@php
										$sentence = auth()->user()->name;
										preg_match_all('/[A-Z]/', $sentence, $matches);
										$firstTwo = implode('', array_slice($matches[0], 0, 2));
								@endphp

                {{-- Avatar with Logout Icon --}}
                <div class="flex items-center gap-3 px-2 py-1 rounded hover:bg-base-200 transition">
                    <x-mary-avatar placeholder="{{ $firstTwo }}"
                        subtitle="{{ auth()->user()->name }}"
                        class="!w-10"
                    />

                    <x-mary-button
                        icon="o-arrow-uturn-left"
                        class="btn-circle btn-ghost btn-md ml-auto"
                        tooltip-left="Logout"
                        no-wire-navigate
                        link="/logout"
                    />
                </div>

                <x-mary-menu-separator />
            @endif

					<x-mary-menu-item title="Dashboard" icon="o-home" link="/dashboard" />
					<x-mary-menu-separator />

					<x-mary-menu-item title="Client Profiling" icon="s-user-plus" link="/client-profiling" />
					<x-mary-menu-separator />

					<x-mary-menu-sub title="System Library" icon="o-cog-6-tooth" open>
						<x-mary-menu-item title="Barangay" icon="s-wrench-screwdriver" link="/barangay" />
						<x-mary-menu-item title="Basic Ecclesial Community" icon="s-wrench-screwdriver" link="/bec" />
						<x-mary-menu-item title="Client Category" icon="s-wrench-screwdriver" link="/client-category" />
						<x-mary-menu-item title="Client Type" icon="s-wrench-screwdriver" link="/client-type" />
						<x-mary-menu-item title="Contract Category" icon="s-wrench-screwdriver" link="/contract-category" />
						<x-mary-menu-item title="Contract Category Type" icon="s-wrench-screwdriver" link="/category-type" />
						<x-mary-menu-item title="Contract Type" icon="s-wrench-screwdriver" link="/contract-type" />
						<x-mary-menu-item title="Congregation" icon="s-wrench-screwdriver" link="/congregation" />
						<x-mary-menu-item title="Diocese" icon="s-wrench-screwdriver" link="/diocese" />
						<x-mary-menu-item title="Diocese Vicariate" icon="s-wrench-screwdriver" link="/vicariate" />
						<x-mary-menu-item title="Employee Type" icon="s-wrench-screwdriver" link="/employee-type" />
						<x-mary-menu-item title="Island Groups" icon="s-wrench-screwdriver" link="/island-groups" />
						<x-mary-menu-item title="Parish" icon="s-wrench-screwdriver" link="/parish" />
						<x-mary-menu-item title="Payment Type" icon="s-wrench-screwdriver" link="/payment-type" />
						<x-mary-menu-item title="Priest Directory" icon="s-wrench-screwdriver" link="/priest" />
						<x-mary-menu-item title="Provinces" icon="s-wrench-screwdriver" link="/provinces" />
						<x-mary-menu-item title="Religion" icon="s-wrench-screwdriver" link="/religion" />
						<x-mary-menu-item title="Roles" icon="s-wrench-screwdriver" link="/roles" />
						
						{{-- <x-mary-menu-item title="Basic Ecclesial Comm" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Cashier Transaction Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Citizenship & Nationality" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Client Category" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Client Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Congregation" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Contract Category" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Contract Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Country" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Diocese" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Employee Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Island Group" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Local Gov. Unit Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Months" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Parishes" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Priest" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Province" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Regional Center" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Region" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Religion" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="Role" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="User Type" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="System Directory" icon="s-list-bullet" link="/barangay" /> --}}
						{{-- <x-mary-menu-item title="System Directory Type" icon="s-list-bullet" link="/barangay" /> --}}
				</x-mary-menu-sub>
				<x-mary-menu-separator />

					{{-- @if(auth()->user()->hasRole(1))
							<x-mary-menu-item title="Clearance Monitoring" icon="o-home" link="/monitoring" class="py-4" />
							<x-mary-menu-separator />

							<x-mary-menu-sub title="Clearance(s)" icon="o-cog-6-tooth" open>
									<x-mary-menu-item title="HED Faculty" icon="o-home" link="/hed" class="mt-3.5"/>
									<x-mary-menu-item title="BED Faculty" icon="o-home" link="/bed" />
									<x-mary-menu-item title="Support Service Personnel" icon="o-home" link="/ssp"/>
									<x-mary-menu-item title="Maintenance" icon="o-home" link="/maintenance"/>
									<x-mary-menu-item title="BED Student" icon="o-home" link="/bed-student"/>
							</x-mary-menu-sub>
							<x-mary-menu-separator />

							<x-mary-menu-sub title="Clearance Libraries" icon="o-cog-6-tooth" open>
									<x-mary-menu-item title="Clearance Area" icon="o-home" link="/area" class="mt-3.5"/>
									<x-mary-menu-item title="Clearance Type" icon="o-home" link="/type" />
									<x-mary-menu-item title="Manage Clearance(s)" icon="o-home" link="/manage-clearance"/>
									<x-mary-menu-item title="Manage Role(s)" icon="o-home" link="/roles"/>
									<x-mary-menu-item title="Permission(s) Request" icon="o-home" link="/permission-request"/>
									<x-mary-menu-item title="System Info" icon="o-home" link="/info"/>
							</x-mary-menu-sub>

					@elseif(auth()->user()->hasRole(2))

							<x-mary-menu-sub title="Clearance(s)" icon="o-cog-6-tooth" open>
								@if(count(Clearance::on('iclearance_connection')->where('is_open',1)->where('clearance_type_id', 3)->get('clearance_type_id')) > 0)
									<x-mary-menu-item title="HED Faculty" icon="o-home" link="/hed" class="mt-3.5"/>
								@endif
								@if(count(Clearance::on('iclearance_connection')->where('is_open',1)->where('clearance_type_id', 4)->get('clearance_type_id')) > 0)
									<x-mary-menu-item title="Support Service Personnel" icon="o-home" link="/ssp"/>
								@endif
								@if(count(Clearance::on('iclearance_connection')->where('is_open',1)->where('clearance_type_id', 5)->get('clearance_type_id')) > 0)
									<x-mary-menu-item title="Maintenance" icon="o-home" link="/maintenance"/>
								@endif
								@if(count(Clearance::on('iclearance_connection')->where('is_open',1)->where('clearance_type_id', 2)->get('clearance_type_id')) > 0)
									<x-mary-menu-item title="BED Student" icon="o-home" link="/bed-student"/>
								@endif
								@if(count(Clearance::on('iclearance_connection')->where('is_open',1)->where('clearance_type_id', 1)->get('clearance_type_id')) > 0)
									<x-mary-menu-item title="BED Faculty" icon="o-home" link="/bed" />
								@endif
							</x-mary-menu-sub>

					@else



					@endif --}}

					{{-- <x-mary-menu-separator />
					<x-mary-menu-item title="Request Access Permission" icon="o-home" link="/request-access" class="py-4" />

					<x-mary-menu-separator />
					<x-mary-menu-item title="FAQ" icon="o-home" link="/faq" class="py-4" /> --}}

					{{-- @if(auth()->user()->getRoleAttribute()->role_id == 2)
								<x-mary-menu-item title="Food Substantiation" icon="o-home" link="/contributor-substantiation" class="py-4" />
					@endif --}}

					{{-- @if(auth()->user()->getRoleAttribute()->role_id == 1)
							<x-mary-menu-sub title="Food Substantiation" icon="o-cog-6-tooth" open>
									<x-mary-menu-item title="For Approval" icon="o-home" link="/food-substantiation" class="mt-3.5"/>
									<x-mary-menu-item title="Approved" icon="o-home" link="/approved-substantiation" />
							</x-mary-menu-sub>

							<x-mary-menu-separator />

							<x-mary-menu-item title="Foods" icon="o-list-bullet" link="/foods" />

							<x-mary-menu-item title="Product Brand" icon="o-list-bullet" link="/brand" />

							<x-mary-menu-item title="Product" icon="o-list-bullet" link="/product" />

							<x-mary-menu-item title="Company" icon="o-list-bullet" link="/company" />

							<x-mary-menu-item title="Health Claim" icon="o-list-bullet" link="/health-claim" />

							<x-mary-menu-item title="Health Claim Category" icon="o-list-bullet" link="/health-claim-category" />

							<x-mary-menu-item title="File Record(s) Type" icon="o-list-bullet" link="/file-record-type" />

							<x-mary-menu-item title="Food Classification" icon="o-list-bullet" link="/food-classification" />

							<x-mary-menu-item title="Food Parts" icon="o-list-bullet" link="/food-parts" />

							<x-mary-menu-item title="Functional Factor" icon="o-list-bullet" link="/functional-factor" />

							<x-mary-menu-item title="Product Form" icon="o-list-bullet" link="/product-form" />

							<x-mary-menu-item title="Test Classification" icon="o-list-bullet" link="/test-classification" />

					@endif --}}

			</x-mary-menu>
	</x-slot:sidebar>
  
</div>
  