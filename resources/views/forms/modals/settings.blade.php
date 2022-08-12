<x-zeus::modal.dialog wire:model.defer="modals.FormSettings" x-cloak>

    <x-slot name="title">
        <p class="text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            Form Settings
        </p>
    </x-slot>

    <x-slot name="content">
        <div class="space-y-10">
            <x-zeus::input.group inline for="form.is_active" label="Activate the form">
                <x-zeus::input.toggle wireTo="form.is_active" />
            </x-zeus::input.group>

            <x-zeus::input.group inline for="form.options.requireLogin" label="require Login">
                <x-slot name="cite">
                    User must be logged in or create an account before can submit a new entry
                </x-slot>
                <x-zeus::input.toggle wireTo="form.options.requireLogin" />
            </x-zeus::input.group>

            <div>
                {{--@if($form->options['requireLogin'])
                    <x-zeus::input.group inline for="form.options.oneEntryPerUser" label="One Entry Per User">
                        <x-slot name="cite">
                            to check if the user already submitted an entry in this form
                        </x-slot>
                        <x-zeus::input.toggle wireTo="form.options.oneEntryPerUser" />
                    </x-zeus::input.group>
                @endif--}}
            </div>

            <x-zeus::input.group inline for="form.options.sectionsToPages" label="sections To Pages">
                <x-slot name="cite">
                    instead of showing all section in one page, separate them in multiple pages with next and previous buttons
                </x-slot>
                <x-zeus::input.toggle wireTo="form.options.sectionsToPages" />
            </x-zeus::input.group>
        </div>
    </x-slot>
    <x-slot name="footer">
        <x-zeus::elements.button wire:click="$toggle('modals.FormSettings')">Save</x-zeus::elements.button>
    </x-slot>
</x-zeus::modal.dialog>
