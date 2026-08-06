<x-block class="py-12 md:py-20 bg-gray-3">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            @if ($title ?? null)
                <h2 class="mb-4 max-w-[16ch] font-sans text-[clamp(2.75rem,5.5vw,5rem)] font-normal leading-[0.96] tracking-[-0.035em] text-primary">{{ $title }}</h2>
            @endif

            @if ($description ?? null)
                <p class="text-base text-gray-600 mb-10 text-center max-w-lg mx-auto">{{ $description }}</p>
            @endif

            <form
                action="{{ route('contact.send') }}"
                method="POST"
                class="bg-white rounded-2xl border border-gray-200 p-6 md:p-10 space-y-6"
                x-data="{
                    sending: false,
                    sent: false,
                    error: null,
                    async submitForm() {
                        this.sending = true;
                        this.error = null;
                        try {
                            const form = $el;
                            const data = new FormData(form);
                            data.append('recipient', '{{ $recipient_email ?? '' }}');
                            const resp = await fetch('{{ route('contact.send') }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                body: data
                            });
                            if (!resp.ok) throw new Error(await resp.text());
                            this.sent = true;
                        } catch (e) {
                            this.error = '{{ __('Hubo un error al enviar el mensaje. Intentá de nuevo.') }}';
                        } finally {
                            this.sending = false;
                        }
                    }
                }"
                @submit.prevent="submitForm"
            >
                @csrf

                <template x-if="sent">
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                            <x-lucide-check class="w-7 h-7 text-green-600" />
                        </div>
                        <p class="text-lg font-bold text-gray-900 font-sans">{{ $success_message ?? 'Mensaje enviado correctamente' }}</p>
                    </div>
                </template>

                <template x-if="!sent">
                    <div>
                        <template x-if="error">
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700" x-text="error"></div>
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5 font-sans">{{ __('Nombre') }} *</label>
                                <input type="text" name="name" required
                                       class="w-full border border-gray-2 rounded-sm px-4 py-3 text-gray-900
                                              focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                       placeholder="{{ __('Tu nombre') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5 font-sans">{{ __('Email') }} *</label>
                                <input type="email" name="email" required
                                       class="w-full border border-gray-2 rounded-sm px-4 py-3 text-gray-900
                                              focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                       placeholder="{{ __('tu@email.com') }}">
                            </div>
                            @if ($show_phone ?? false)
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5 font-sans">{{ __('Teléfono') }}</label>
                                    <input type="tel" name="phone"
                                           class="w-full border border-gray-2 rounded-sm px-4 py-3 text-gray-900
                                                  focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                           placeholder="{{ __('+54 11 1234 5678') }}">
                                </div>
                            @endif
                            @if ($show_subject ?? false)
                                <div class="{{ $show_phone ?? false ? '' : 'sm:col-span-2' }}">
                                    <label class="block text-sm font-bold text-gray-700 mb-1.5 font-sans">{{ __('Asunto') }}</label>
                                    <input type="text" name="subject"
                                           class="w-full border border-gray-2 rounded-sm px-4 py-3 text-gray-900
                                                  focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                           placeholder="{{ __('Motivo del mensaje') }}">
                                </div>
                            @endif
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-bold text-gray-700 mb-1.5 font-sans">{{ __('Mensaje') }} *</label>
                            <textarea name="message" required rows="5"
                                      class="w-full border border-gray-2 rounded-sm px-4 py-3 text-gray-900 resize-y
                                             focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                      placeholder="{{ __('Escribí tu mensaje...') }}"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    :disabled="sending"
                                    class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 font-bold rounded-sm
                                           hover:bg-primary-hover transition-colors group disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!sending">{{ __('Enviar mensaje') }}</span>
                                <span x-show="sending">{{ __('Enviando...') }}</span>
                                <x-lucide-send class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </button>
                        </div>
                    </div>
                </template>
            </form>
        </div>
    </div>
</x-block>
