<div x-data class="space-y-4 my-6 mx-4 w-full">
    @php
        $getRecord = $getRecord();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <x-filament::section>
                @foreach($getRecord->fieldsResponses as $resp)
                    @if($resp->field !== null)
                        <div class="py-2 text-ellipsis overflow-auto">
                            <p>{{ $resp->field->name ?? '' }}</p>

                            <div class="items-center flex justify-between">
                                <p class="font-semibold mb-2">
                                    {!! ( new $resp->field->type )->getResponse($resp->field, $resp) !!}
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
                @if($getRecord->user_id === null)
                    <span>{{ __('By') }} {{ __('Visitor') }}</span>
                @else
                    <div class="flex gap-2 items-center">
                        <p class="flex flex-col gap-1">
                            <span>{{ ($getRecord->user->{config('auth.providers.users.model')::getBoltUserFullNameAttribute()}) ?? '' }}</span>
                            <span>{{ ($getRecord->user->email) ?? '' }}</span>
                        </p>
                    </div>
                @endif
                <p class="flex flex-col my-1 gap-1">
                    <span class="text-base font-light">{{ __('created at') }}:</span>
                    <span class="font-semibold">{{ $getRecord->created_at->format(\Filament\Infolists\Infolist::$defaultDateDisplayFormat) }}-{{ $getRecord->created_at->format(\Filament\Infolists\Infolist::$defaultTimeDisplayFormat) }}</span>
                </p>
            </x-filament::section>
            <x-filament::section>
                <x-slot name="heading" class="text-primary-600">
                    <p class="text-primary-600 font-semibold">{{ __('Entry Details') }}</p>
                </x-slot>

                <div class="flex flex-col mb-4">
                    <span class="text-gray-600">{{ __('Form') }}:</span>
                    <span>{{ $getRecord->form->name ?? '' }}</span>
                </div>

                <div class="mb-4">
                    <span>{{ __('status') }}</span>
                    @php $getStatues = $getRecord->statusDetails() @endphp
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
                    {!! nl2br($getRecord->notes) !!}
                </div>

                <br><br>
                <strong>Status permohonan disetujui</strong><br>
                @if($getRecord->time_status_permohonan_disetujui)
                    <span>{{ $getRecord->time_status_permohonan_disetujui }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_permohonan_disetujui)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen permohonan disetujui' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_permohonan_disetujui) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen permohonan disetujui' }}:</span>
                        <p>{{ __('No output file available') }}</p>
                    </div>
                @endif

                <br><br>
                <strong>Status permohonan ditolak</strong><br>
                @if($getRecord->time_status_permohonan_ditolak)
                    <span>{{ $getRecord->time_status_permohonan_ditolak }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_permohonan_ditolak)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen permohonan ditolak' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_permohonan_ditolak) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen permohonan ditolak' }}:</span>
                        <p>{{ __('No output file available') }}</p>
                    </div>
                @endif

                <br><br>
                <strong>Status pelaksanaan</strong><br>
                @if($getRecord->time_status_pelaksanaan)
                    <span>{{ $getRecord->time_status_pelaksanaan }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_pelaksanaan)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen pelaksanaan' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_pelaksanaan) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen pelaksanaan' }}:</span>
                        <p>{{ __('No output file available') }}</p>
                    </div>
                @endif

                <br><br>
                <strong>Status menunggu pembayaran</strong><br>
                @if($getRecord->time_status_menunggu_pembayaran)
                    <span>{{ $getRecord->time_status_menunggu_pembayaran }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_tagihan)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen tagihan' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_tagihan) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen tagihan' }}:</span>
                        <p>{{ __('No output file available') }}</p>
                    </div>
                @endif

                <br><br>
                <strong>Bukti pembayaran</strong><br>
                @if($getRecord->time_dokumen_bukti_pembayaran)
                    <span>{{ $getRecord->time_dokumen_bukti_pembayaran }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_bukti_pembayaran)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen bukti pembayaran' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_bukti_pembayaran) }}" target="_blank">Lihat File</a>
                    </div>
                @else
                    <div class="flex flex-col">
                        <span>{{ 'Output bukti pembayaran' }}:</span>
                        <p>{{ __('No output file available') }}</p>
                    </div>
                @endif

                <br><br>
                <strong>Status pembayaran diterima</strong><br>
                @if($getRecord->time_dokumen_pembayaran_diterima)
                    <span>{{ $getRecord->time_dokumen_pembayaran_diterima }}</span>
                @else
                    <span>-</span>
                @endif

                <br><br>
                <strong>Status hasil terbit</strong><br>
                @if($getRecord->time_status_hasil_terbit)
                    <span>{{ $getRecord->time_status_hasil_terbit }}</span>
                @else
                    <span>-</span>
                @endif
                @if($getRecord->dokumen_output)
                    <div class="flex flex-col">
                        <span>{{ 'Dokumen output akhir' }}:</span>
                        <a style="color: green; font-weight: bold;" href="{{ Storage::disk(config('zeus-bolt.uploadDisk'))->url($getRecord->dokumen_output) }}" target="_blank">Lihat File</a>
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
