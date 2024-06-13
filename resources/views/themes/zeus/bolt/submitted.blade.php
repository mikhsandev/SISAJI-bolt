<div>
    <div class="text-center">
        <x-filament::section>
            <div class="bg-blue-100 p-6" style="margin: -25px">
                @if(!empty($zeusForm->options['confirmation-message']))
                    <span class="text-md text-blue-100">
                        {!! $zeusForm->options['confirmation-message'] !!}
                    </span>
                @else
                    <span class="text-md text-blue-100">
                        {{ __('the form') }}
                        <span class="font-semibold">{{ $zeusForm->name ?? '' }}</span>
                        {{ __('submitted successfully') }}.
                    </span>
                @endif

                {!! \LaraZeus\Bolt\Facades\Extensions::init($zeusForm, 'SubmittedRender', ['extensionData' => $extensionData['extInfo']['itemId'] ?? 0]) !!}
            </div>
        </x-filament::section>
    </div>
</div>
