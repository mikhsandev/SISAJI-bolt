<x-filament::page>
    <div x-data class="space-y-4 my-6 mx-4 w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <x-filament::section>
                    @foreach($response->fieldsResponses as $resp)
                        @if($resp->field !== null)
                            <div class="py-2 text-ellipsis overflow-auto">
                                <p>{{ $resp->field->name ?? '' }}</p>
                                <div class="items-center flex justify-between">
                                    <p class="font-semibold mb-2">
                                        {!! ( new $resp->field->type )->entry($resp->field, $resp) !!}
                                    </p>
                                    @if($resp->form->extensions === 'LaraZeus\\BoltPro\\Extensions\\Grades')
                                        <livewire:bolt-pro.grading :response="$resp" />
                                    @endif
                                </div>
                                <hr/>
                            </div>
                        @endif
                    @endforeach
                </x-filament::section>
            </div>
            <div class="space-y-4">
                <x-filament::section>
                    <x-slot name="heading" class="text-primary-600">
                        {{ __('User Details') }}
                    </x-slot>
                    @if($response->user_id === null)
                        <span>{{ __('By') }} {{ __('Visitor') }}</span>
                    @else
                        <div class="flex gap-2 items-center">
                            <x-filament::avatar
                                    class="rounded-full"
                                    size="lg"
                                    :src="$response->user->avatar"
                                    :alt="($response->user->{config('auth.providers.users.model')::getBoltUserFullNameAttribute()}) ?? ''"
                            />
                            <p class="flex flex-col gap-1">
                                <span>{{ ($response->user->{config('auth.providers.users.model')::getBoltUserFullNameAttribute()}) ?? '' }}</span>
                                <span>{{ ($response->user->email) ?? '' }}</span>
                            </p>
                        </div>
                    @endif
                    <p class="flex flex-col my-1 gap-1">
                        <span class="text-base font-light">{{ __('created at') }}:</span>
                        <span class="font-semibold">{{ $response->created_at->format(\Filament\Infolists\Infolist::$defaultDateDisplayFormat) }}-{{ $response->created_at->format(\Filament\Infolists\Infolist::$defaultTimeDisplayFormat) }}</span>
                    </p>
                </x-filament::section>
                <x-filament::section>
                    <x-slot name="heading" class="text-primary-600">
                        <p class="text-primary-600 font-semibold">{{ __('Entry Details') }}</p>
                    </x-slot>

                    <div class="flex flex-col mb-4">
                        <span class="text-gray-600">{{ __('Form') }}:</span>
                        <span>{{ $response->form->name ?? '' }}</span>
                    </div>

                    <div class="mb-4">
                        <span>{{ __('status') }}</span>
                            @php $getStatues = $response->statusDetails() @endphp
                            <span class="{{ $getStatues['class']}}"
                                  x-tooltip="{
                                    content: @js(__('status')),
                                    theme: $store.theme,
                                  }">
                            @svg($getStatues['icon'],'w-4 h-4 inline')
                                {{ $getStatues['label'] }}
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <span>{{ __('Notes') }}:</span>
                        {!! nl2br($response->notes) !!}
                    </div>

                    <br>
                    @if($response->dokumen_permohonan_disetujui)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen permohonan disetujui' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_permohonan_disetujui) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Output Layanan' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif

                    <br>
                    @if($response->dokumen_permohonan_ditolak)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen permohonan ditolak' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_permohonan_ditolak) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Output Layanan' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif

                    <br>
                    @if($response->dokumen_pelaksanaan)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen pelaksanaan' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_pelaksanaan) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen pelaksanaan' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif

                    <br>
                    @if($response->dokumen_tagihan)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen tagihan' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_tagihan) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen tagihan' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif

                    <br>
                    @if($response->dokumen_bukti_pembayaran)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen bukti pembayaran' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_bukti_pembayaran) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Output bukti pembayaran' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif

                    <br>
                    @if($response->dokumen_output)
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen output akhir' }}:</span>
                            <a href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($response->dokumen_output) }}" target="_blank">{{ __('Click here to open in new tab') }}</a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <span>{{ 'Dokumen output akhir' }}:</span>
                            <p>{{ __('No output file available') }}</p>
                        </div>
                    @endif
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament::page>
