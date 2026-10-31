{{--
    /quote: one <form>, four short steps (07_LEAD_GENERATION.md §7.2).

    With JavaScript there is one step per screen with a progress bar. Without it,
    every step renders in sequence (.js-panel / .js-only in the layout's noscript),
    and every type's questions show under their own heading: the server reads only
    the chosen type's answers. No response-time promise and no "free" until the
    owner decides them (Q4, Q5).
--}}
@php
    use App\Support\Quote;
    use Illuminate\Support\Js;

    $brand = config('brand');
    $steps = ['What you need', 'About the work', 'Budget and timing', 'Your details'];

    $type = old('project_type', $selected);
    $type = array_key_exists((string) $type, $types) ? $type : null;
    $defs = collect($types)->keys()->mapWithKeys(fn ($t) => [$t => Quote::type($t)]);
    $messages = $defs->map(fn ($d) => $d['message']);
    $budgetSet = $defs->map(fn ($d) => $d['budget']);
    $setNames = ['project' => 'Project budget', 'service' => 'Project budget', 'monthly' => 'Monthly budget'];
    $usedSets = $budgetSet->unique()->values();

    // Open on the first step with an error; a preset type skips step 1.
    $stepOf = fn (string $key) => match (true) {
        $key === 'project_type' => 1,
        $key === 'message' || str_starts_with($key, 'answers.') => 2,
        in_array($key, ['budget_band', 'timeframe'], true) => 3,
        default => 4,
    };
    $start = $errors->any()
        ? collect($errors->keys())->map($stepOf)->min()
        : ($type ? 2 : 1);

    $radio = 'h-4 w-4 shrink-0 accent-accent';
    $pill = 'flex min-h-11 cursor-pointer items-center gap-2.5 rounded-xl border border-brand/15 bg-white px-4 py-2.5 text-sm text-brand transition-colors hover:border-accent/50 has-[:checked]:border-accent has-[:checked]:bg-accent/[0.06] has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-accent/40';
    $chip = str_replace(['items-center', 'py-2.5'], ['items-start', 'py-4'], $pill);
    $input = 'mt-1.5 w-full rounded-lg border border-brand/20 bg-white px-3.5 py-2.5 text-base text-brand focus:border-accent focus:ring-0 sm:text-sm';
@endphp

<x-ui.layout overlay title="Get a Quote"
    description="Tell us about the software, automation or IT work you need. Four short steps, about two minutes, and a reply from the people who would do the work.">

    <section class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-10 pt-8 sm:pt-12 lg:pb-14 lg:pt-14">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Get a quote
            </p>
            <h1 class="t-display mt-5 max-w-3xl text-white">Tell us what you need built or fixed.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">Four short steps, about two minutes. It goes to the people who would do the work, not to a call centre.</p>
        </div>
    </section>

    <section class="band-paper section-v2">
        <div class="container-wide grid gap-12 lg:grid-cols-12 lg:gap-16">

            <div class="lg:col-span-8">
                @if ($errors->any())
                    <div role="alert" class="mb-8 rounded-xl border border-red-200 bg-red-50 p-5">
                        <p class="text-sm font-semibold text-red-800">A few details still need your attention.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach ($errors->unique() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('quote.store') }}" novalidate
                      x-data="{
                          step: {{ $start }},
                          total: {{ count($steps) }},
                          names: {{ Js::from($steps) }},
                          type: {{ Js::from($type) }},
                          messages: {{ Js::from($messages) }},
                          sets: {{ Js::from($budgetSet) }},
                          get message() { return this.messages[this.type] ?? ['Tell us about the work', true] },
                          choose(t) {
                              if (this.sets[t] !== this.sets[this.type]) {
                                  this.$root.querySelectorAll('input[name=budget_band]').forEach(el => el.checked = false);
                              }
                              this.type = t;
                          },
                          valid() {
                              for (const el of this.$refs['step' + this.step].querySelectorAll('input, select, textarea')) {
                                  if (! el.disabled && ! el.checkValidity()) { el.reportValidity(); return false; }
                              }
                              return true;
                          },
                          go(n) {
                              if (n > this.step && ! this.valid()) return;
                              this.step = n;
                              this.$nextTick(() => {
                                  this.$refs['title' + n].focus({ preventScroll: true });
                                  this.$root.scrollIntoView({ block: 'start', behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                              });
                          },
                          submit(e) {
                              for (let n = 1; n <= this.total; n++) {
                                  for (const el of this.$refs['step' + n].querySelectorAll('input, select, textarea')) {
                                      if (! el.disabled && ! el.checkValidity()) { e.preventDefault(); this.step = n; this.$nextTick(() => el.reportValidity()); return; }
                                  }
                              }
                          },
                      }"
                      x-on:submit="submit($event)"
                      class="scroll-mt-28">
                    @csrf
                    <input type="hidden" name="from" value="{{ old('from', $from) }}">

                    {{-- Honeypot. Hidden from people, irresistible to bots. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="website">Leave this field blank</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- Progress: JavaScript only, since without it every step is on screen. Hidden by the
                         layout's <noscript> rule, not x-cloak, so it is in place at first paint (no shift). --}}
                    <div class="js-only mb-10">
                        <p class="text-sm font-semibold text-brand" aria-live="polite">
                            Step <span x-text="step">{{ $start }}</span> of {{ count($steps) }}<span class="text-muted">: <span x-text="names[step - 1]">{{ $steps[$start - 1] }}</span></span>
                        </p>
                        <div class="mt-3 h-1 overflow-hidden rounded-full bg-brand/10" aria-hidden="true">
                            <div class="h-full rounded-full bg-accent transition-[width] duration-300 motion-reduce:transition-none"
                                 :style="'width:' + (step / total * 100) + '%'" style="width: {{ $start / count($steps) * 100 }}%"></div>
                        </div>
                    </div>

                    {{-- Step 1 --}}
                    <fieldset x-ref="step1" x-show="step === 1" @if ($start !== 1) style="display: none" @endif class="js-panel">
                        <legend x-ref="title1" tabindex="-1" class="t-subtitle text-brand outline-none">What do you need?</legend>
                        <p class="mt-2 text-secondary">Pick the closest. If nothing fits, choose "Something else".</p>

                        <div class="mt-7 grid gap-3 sm:grid-cols-2">
                            @foreach ($types as $slug => $t)
                                <label class="{{ $chip }}">
                                    <input type="radio" name="project_type" value="{{ $slug }}" required
                                           class="{{ $radio }} mt-0.5" @checked($type === $slug) x-on:change="choose('{{ $slug }}')">
                                    <span>
                                        <span class="block font-semibold">{{ $t['label'] }}</span>
                                        <span class="mt-0.5 block text-secondary">{{ $t['blurb'] }}</span>
                                        @if ($t['preview'])
                                            <span class="mt-2 inline-flex rounded-full border border-dashed border-brand/30 px-2 py-0.5 text-xs font-semibold text-muted">Preview only</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('project_type') <p class="mt-3 text-sm font-medium text-red-600">{{ $message }}</p> @enderror

                        <p class="mt-6 text-sm text-secondary">
                            Looking for a website for your agency, dealership, guesthouse or business?
                            <a href="{{ route('packages.index') }}" class="font-semibold text-accent underline-offset-4 hover:underline">Our platforms have a flat monthly price &rarr;</a>
                        </p>

                        <div class="js-only mt-10 flex justify-end">
                            <button type="button" x-on:click="go(2)" class="btn-v2 btn-v2-lg bg-accent text-white hover:brightness-110">Next: about the work</button>
                        </div>
                    </fieldset>

                    {{-- Step 2 --}}
                    <fieldset x-ref="step2" x-show="step === 2" @if ($start !== 2) style="display: none" @endif class="js-panel">
                        <legend x-ref="title2" tabindex="-1" class="t-subtitle text-brand outline-none">About the work</legend>
                        <p class="mt-2 text-secondary">A few quick answers so the first conversation is useful.</p>

                        @foreach ($types as $slug => $t)
                            @continue (empty($defs[$slug]['questions']))
                            <fieldset x-show="type === '{{ $slug }}'" :disabled="type !== '{{ $slug }}'"
                                      @if ($type !== $slug) style="display: none" @endif class="js-panel mt-8 space-y-7">
                                <legend class="sr-only">{{ $t['label'] }}</legend>
                                <p class="nojs-show hidden text-xs font-semibold uppercase tracking-wider text-muted">If you chose {{ $t['label'] }}</p>
                                @foreach ($defs[$slug]['questions'] as $id => [$question, $options])
                                    <fieldset>
                                        <legend class="text-sm font-semibold text-brand">{{ $question }}</legend>
                                        <div class="mt-3 flex flex-wrap gap-2.5">
                                            @foreach ($options as $value => $label)
                                                <label class="{{ $pill }}">
                                                    <input type="radio" name="answers[{{ $slug }}][{{ $id }}]" value="{{ $value }}" class="{{ $radio }}"
                                                           :required="true"
                                                           @checked(old("answers.{$slug}.{$id}") === $value)>
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error("answers.{$slug}.{$id}") <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                                    </fieldset>
                                @endforeach
                            </fieldset>
                        @endforeach

                        <div class="mt-8">
                            <label for="message" class="block text-sm font-semibold text-brand">
                                <span x-text="message[0]">{{ $type ? $messages[$type][0] : 'Tell us about the work' }}</span>
                                <span class="font-normal text-muted" x-show="! message[1]" @if (! $type || $messages[$type][1]) style="display: none" @endif>(optional)</span>
                            </label>
                            <textarea name="message" id="message" rows="5" maxlength="4000" :required="message[1]"
                                      @if ($errors->has('message')) aria-invalid="true" aria-describedby="message-error" @endif
                                      class="{{ $input }}"
                                      placeholder="What happens today, what you would like to happen instead, and anything already in place.">{{ old('message') }}</textarea>
                            @error('message') <p id="message-error" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="js-only mt-10 flex items-center justify-between gap-4">
                            <button type="button" x-on:click="go(1)" class="btn-v2 border border-brand/15 bg-white text-brand hover:border-accent/50">Back</button>
                            <button type="button" x-on:click="go(3)" class="btn-v2 btn-v2-lg bg-accent text-white hover:brightness-110">Next: budget and timing</button>
                        </div>
                    </fieldset>

                    {{-- Step 3 --}}
                    <fieldset x-ref="step3" x-show="step === 3" @if ($start !== 3) style="display: none" @endif class="js-panel">
                        <legend x-ref="title3" tabindex="-1" class="t-subtitle text-brand outline-none">Budget and timing</legend>
                        <p class="mt-2 text-secondary">Bands, excluding VAT. "Not sure yet" is a perfectly good answer.</p>

                        @foreach ($usedSets as $set)
                            <fieldset x-show="sets[type] === '{{ $set }}' || (! type && '{{ $set }}' === 'project')"
                                      :disabled="type ? sets[type] !== '{{ $set }}' : '{{ $set }}' !== 'project'"
                                      @if (($budgetSet[$type] ?? 'project') !== $set) style="display: none" @endif class="js-panel mt-8">
                                <legend class="text-sm font-semibold text-brand">{{ $setNames[$set] }}</legend>
                                <div class="mt-3 flex flex-wrap gap-2.5">
                                    @foreach (config("quote.budgets.{$set}") as $value => [$label])
                                        <label class="{{ $pill }}">
                                            <input type="radio" name="budget_band" value="{{ $value }}" required class="{{ $radio }}"
                                                   @checked(old('budget_band') === $value && ($budgetSet[$type] ?? 'project') === $set)>
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                        @error('budget_band') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror

                        <fieldset class="mt-8">
                            <legend class="text-sm font-semibold text-brand">When would you like to start?</legend>
                            <div class="mt-3 flex flex-wrap gap-2.5">
                                @foreach (config('quote.timeframes') as $value => $label)
                                    <label class="{{ $pill }}">
                                        <input type="radio" name="timeframe" value="{{ $value }}" required class="{{ $radio }}" @checked(old('timeframe') === $value)>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            @error('timeframe') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                        </fieldset>

                        <div class="js-only mt-10 flex items-center justify-between gap-4">
                            <button type="button" x-on:click="go(2)" class="btn-v2 border border-brand/15 bg-white text-brand hover:border-accent/50">Back</button>
                            <button type="button" x-on:click="go(4)" class="btn-v2 btn-v2-lg bg-accent text-white hover:brightness-110">Next: your details</button>
                        </div>
                    </fieldset>

                    {{-- Step 4 --}}
                    <fieldset x-ref="step4" x-show="step === 4" @if ($start !== 4) style="display: none" @endif class="js-panel">
                        <legend x-ref="title4" tabindex="-1" class="t-subtitle text-brand outline-none">Your details</legend>
                        <p class="mt-2 text-secondary">So the right person can reach you.</p>

                        <div class="mt-8 grid gap-6 sm:grid-cols-2">
                            <x-form-field name="name" label="Your name" required autocomplete="name" />
                            <x-form-field name="company" label="Company" autocomplete="organization" />
                            <x-form-field name="email" label="Email address" type="email" required autocomplete="email" />
                            <x-form-field name="phone" label="Telephone or WhatsApp" type="tel" required autocomplete="tel" />
                            <x-form-field name="role" label="Your role" autocomplete="organization-title" />
                            <div>
                                <label for="company_size" class="block text-sm font-medium text-brand">Company size</label>
                                <select name="company_size" id="company_size" required
                                        @if ($errors->has('company_size')) aria-invalid="true" aria-describedby="company_size-error" @endif
                                        class="{{ $input }}">
                                    <option value="">Choose one</option>
                                    @foreach (config('quote.company_sizes') as $value => $label)
                                        <option value="{{ $value }}" @selected(old('company_size') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('company_size') <p id="company_size-error" class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <fieldset class="mt-7">
                            <legend class="text-sm font-medium text-brand">How should we contact you?</legend>
                            <div class="mt-3 flex flex-wrap gap-2.5">
                                @foreach (config('quote.preferred_contact') as $value => $label)
                                    <label class="{{ $pill }}">
                                        <input type="radio" name="preferred_contact" value="{{ $value }}" required class="{{ $radio }}"
                                               @checked(old('preferred_contact', 'phone') === $value)>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <x-popia-notice class="mt-8"
                            purpose="to answer this enquiry and, if you go ahead, to scope and deliver the work. We also note which page brought you here, so we know what is worth keeping on the site" />

                        <div class="mt-5 flex gap-3">
                            <input type="checkbox" name="consent" id="consent" value="1" required @checked(old('consent'))
                                   class="mt-0.5 h-4 w-4 shrink-0 accent-accent">
                            <label for="consent" class="text-sm leading-relaxed text-secondary">
                                I agree that {{ $brand['name'] }} may use the details above to answer this enquiry
                                and to prepare a proposal if I ask for one.
                            </label>
                        </div>
                        @error('consent') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror

                        <div class="mt-10 flex items-center justify-between gap-4">
                            <button type="button" x-on:click="go(3)" class="js-only btn-v2 border border-brand/15 bg-white text-brand hover:border-accent/50">Back</button>
                            <button type="submit" class="btn-v2 btn-v2-lg ml-auto bg-accent text-white hover:brightness-110">Send my enquiry</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            {{-- A div, not <aside>: a complementary landmark may not sit inside <main>. --}}
            <div class="lg:col-span-4 lg:sticky lg:top-28 lg:self-start">
                <div class="rounded-2xl border border-hairline bg-white p-6 sm:p-7">
                    <h2 class="font-display text-base font-bold text-brand">What happens next</h2>
                    <ol class="mt-5 space-y-4 text-sm text-secondary">
                        @foreach ([
                            'We read it and reply the way you asked us to.',
                            'A conversation about the work, with someone who would build it.',
                            'A written proposal: scope, price and timeline.',
                            'Nothing to pay unless you accept it.',
                        ] as $i => $line)
                            <li class="flex gap-3">
                                <span class="t-index flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand text-[11px] text-white">{{ $i + 1 }}</span>
                                <span class="leading-relaxed">{{ $line }}</span>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-7 border-t border-hairline pt-5 text-sm">
                        <p class="font-semibold text-brand">Rather talk now?</p>
                        <p class="mt-2">
                            <a href="tel:{{ preg_replace('/\D+/', '', $brand['contact']['phone']) }}" class="text-secondary hover:text-accent">{{ $brand['contact']['phone'] }}</a>
                        </p>
                        <p class="mt-2"><x-whatsapp-link class="text-secondary hover:text-accent" label="WhatsApp us" /></p>
                        <p class="mt-2">
                            <a href="mailto:{{ $brand['contact']['sales_email'] }}" class="text-secondary underline underline-offset-2 hover:text-accent">{{ $brand['contact']['sales_email'] }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-ui.layout>
